<?php

namespace App\Livewire\Admin;

use App\Exports\FinancialReportExport;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class FinancialReports extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $dateRange = 'this_month';

    public $startDate;

    public $endDate;

    public $reportType = 'summary';

    protected $queryString = [
        'dateRange' => ['except' => 'this_month'],
        'reportType' => ['except' => 'summary'],
    ];

    public function mount(): void
    {
        $this->setDateRange();
    }

    public function setDateRange(): void
    {
        switch ($this->dateRange) {
            case 'this_month':
                $this->startDate = now()->startOfMonth();
                $this->endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $this->startDate = now()->subMonth()->startOfMonth();
                $this->endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_quarter':
                $this->startDate = now()->startOfQuarter();
                $this->endDate = now()->endOfQuarter();
                break;
            case 'this_year':
                $this->startDate = now()->startOfYear();
                $this->endDate = now()->endOfYear();
                break;
            case 'custom':
                // Keep existing dates
                break;
            default:
                $this->startDate = now()->startOfMonth();
                $this->endDate = now()->endOfMonth();
                break;
        }
    }

    public function updatedDateRange(): void
    {
        $this->setDateRange();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTransactionsQuery())
            ->columns([
                TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable()
                    ->label('Date'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record): string => $record->category),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->description ?? 'No description'),

                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable()
                    ->alignRight()
                    ->weight('bold'),

                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn ($record): string => $record->creator?->name ?? 'System'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ])
                    ->label('Transaction Type'),

                SelectFilter::make('category')
                    ->options(function () {
                        $categories = Transaction::query()
                            ->whereBetween('date', [$this->startDate, $this->endDate])
                            ->distinct('category')
                            ->pluck('category')
                            ->sort();

                        return $categories->mapWithKeys(fn ($category) => [$category => $category]);
                    })
                    ->searchable()
                    ->label('Category'),
            ])
            ->headerActions([
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(fn () => $this->exportToPDF()),

                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(fn () => $this->exportToExcel()),
            ])
            ->striped()
            ->deferLoading();
    }

    protected function getTransactionsQuery(): Builder
    {
        return Transaction::query()
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'approved')
            ->with(['creator']);
    }

    public function getReportDataProperty(): array
    {
        $query = $this->getTransactionsQuery();

        $totalIncome = (float) $query->clone()->where('type', 'income')->sum('amount');
        $totalExpenses = (float) $query->clone()->where('type', 'expense')->sum('amount');
        $netIncome = $totalIncome - $totalExpenses;

        // Income breakdown by category
        $incomeByCategory = $query->clone()
            ->where('type', 'income')
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Expense breakdown by category
        $expensesByCategory = $query->clone()
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Monthly trend
        $monthlyTrend = Transaction::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('MONTH(date) as month'),
            DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expenses')
        )
            ->where('status', 'approved')
            ->whereDate('date', '>=', now()->subMonths(12))
            ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
            ->orderBy(DB::raw('YEAR(date)'), 'asc')
            ->orderBy(DB::raw('MONTH(date)'), 'asc')
            ->get();

        return [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netIncome' => $netIncome,
            'incomeByCategory' => $incomeByCategory,
            'expensesByCategory' => $expensesByCategory,
            'monthlyTrend' => $monthlyTrend,
            'transactionCount' => (int) $query->count(),
            'averageTransaction' => (float) $query->avg('amount'),
        ];
    }

    public function exportToPDF()
    {
        try {
            $data = $this->reportData;

            // Clean data before passing to view
            $cleanData = $this->cleanDataForPdf($data);

            $pdf = Pdf::loadView('livewire.admin.reports.pdf-statement', [
                'data' => $cleanData,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'reportType' => $this->reportType,
            ]);

            // Return as response to avoid Livewire JSON serialization issues
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'financial-statement-'.now()->format('Y-m-d').'.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="financial-statement-'.now()->format('Y-m-d').'.pdf"',
            ]);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: '.$e->getMessage());
            $this->dispatch('error', 'Failed to generate PDF: '.$e->getMessage());

            return null;
        }
    }

    private function debugUtf8Issues($data, $path = '')
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->debugUtf8Issues($value, $path.'['.$key.']');
            }
        } elseif (is_object($data)) {
            // Convert object to array for checking
            $arrayData = json_decode(json_encode($data), true);
            $this->debugUtf8Issues($arrayData, $path);
        } elseif (is_string($data)) {
            if (! mb_check_encoding($data, 'UTF-8')) {
                Log::warning('Invalid UTF-8 at '.$path.': '.substr($data, 0, 100));
            }
        }
    }

    private function cleanDataForPdf($data)
    {
        // Convert to array if it's an object
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        // Recursively clean the data
        array_walk_recursive($data, function (&$value, $key) {
            if (is_string($value)) {
                // Remove any invalid UTF-8 characters
                $value = $this->cleanString($value);
            }
        });

        return $data;
    }

    private function cleanString($string)
    {
        if (empty($string)) {
            return $string;
        }

        // First, try to detect and fix encoding
        if (! mb_check_encoding($string, 'UTF-8')) {
            // Try to detect encoding
            $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'ASCII', 'Windows-1252'], true);

            if ($encoding && $encoding !== 'UTF-8') {
                $string = mb_convert_encoding($string, 'UTF-8', $encoding);
            } else {
                // Force UTF-8 cleaning if detection fails
                $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
            }
        }

        // Remove any remaining invalid UTF-8 characters more aggressively
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
        $string = preg_replace('/[\xC0-\xC1][\x80-\xBF]/', '', $string); // Overlong 2-byte
        $string = preg_replace('/[\xC0-\xFF][\x80-\xBF]{0,2}$/', '', $string); // Incomplete sequences
        $string = preg_replace('/[\xE0-\xEF][\x80-\xBF]{0,1}[\xC0-\xFF]/', '', $string); // Invalid 3-byte

        return trim($string);
    }

    public function exportToExcel()
    {
        try {
            // Clean data before export
            $cleanData = $this->cleanExportData($this->reportData);

            return Excel::download(
                new FinancialReportExport($cleanData, $this->startDate, $this->endDate, $this->reportType),
                'financial-statement-'.now()->format('Y-m-d').'.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Excel Export Error: '.$e->getMessage());
            session()->flash('error', 'Failed to generate Excel file: '.$e->getMessage());

            return null;
        }
    }

    private function cleanExportData($data)
    {
        // Convert collections to arrays
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->toArray();
        }

        // Recursively clean the data
        array_walk_recursive($data, function (&$value, $key) {
            if (is_string($value)) {
                $value = $this->cleanString($value);
                // Remove HTML tags
                $value = strip_tags($value);
            }
        });

        return $data;
    }

    public function render(): View
    {
        // Clean all public properties to prevent UTF-8 issues
        $this->cleanAllProperties();

        return view('livewire.admin.financial-reports', [
            'reportData' => $this->reportData,
        ]);
    }

    private function cleanAllProperties(): void
    {
        $properties = ['dateRange', 'startDate', 'endDate', 'reportType'];

        foreach ($properties as $property) {
            if (property_exists($this, $property) && is_string($this->$property)) {
                $this->$property = $this->cleanString($this->$property);
            }
        }
    }
}
