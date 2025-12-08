<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\BudgetCategory;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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
                    ->icon('heroicon-o-table')
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
        
        $totalIncome = $query->clone()->where('type', 'income')->sum('amount');
        $totalExpenses = $query->clone()->where('type', 'expense')->sum('amount');
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
                'YEAR(date) as year',
                'MONTH(date) as month',
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expenses')
            )
            ->where('status', 'approved')
            ->where('date', '>=', now()->subMonths(12))
            ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
            ->orderBy(DB::raw('YEAR(date), MONTH(date)'))
            ->get();

        return [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netIncome' => $netIncome,
            'incomeByCategory' => $incomeByCategory,
            'expensesByCategory' => $expensesByCategory,
            'monthlyTrend' => $monthlyTrend,
            'transactionCount' => $query->count(),
            'averageTransaction' => $query->avg('amount'),
        ];
    }

    public function exportToPDF(): void
    {
        $data = $this->reportData;
        
        // Generate PDF content
        $content = view('livewire.admin.reports.pdf-statement', [
            'data' => $data,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'reportType' => $this->reportType,
        ])->render();

        // Return PDF download response
        response()->streamDownload(
            fn () => print($content),
            'financial-statement-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function exportToExcel(): void
    {
        $data = $this->reportData;
        
        // Generate Excel content
        $content = view('livewire.admin.reports.excel-statement', [
            'data' => $data,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'reportType' => $this->reportType,
        ])->render();

        // Return Excel download response
        response()->streamDownload(
            fn () => print($content),
            'financial-statement-' . now()->format('Y-m-d') . '.csv'
        );
    }

    public function render(): View
    {
        return view('livewire.admin.financial-reports', [
            'reportData' => $this->reportData,
        ]);
    }
}