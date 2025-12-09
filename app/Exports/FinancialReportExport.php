<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class FinancialReportExport implements FromView, WithEvents, WithTitle
{
    protected $data;

    protected $startDate;

    protected $endDate;

    protected $reportType;

    public function __construct($data, $startDate, $endDate, $reportType)
    {
        // Clean data for export
        $this->data = $this->cleanExportData($data);
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reportType = $reportType;
    }

    public function view(): View
    {
        return view('livewire.admin.reports.excel-sheet', [
            'data' => $this->data,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'reportType' => $this->reportType,
        ]);
    }

    public function title(): string
    {
        return 'Financial Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set UTF-8 encoding
                $event->sheet->getDelegate()->getParent()->getProperties()
                    ->setCreator(config('app.name'))
                    ->setTitle('Financial Statement Report')
                    ->setDescription('Financial statement report generated on '.now()->format('Y-m-d'));

                // Auto-size columns
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
            },
        ];
    }

    private function cleanExportData($data)
    {
        // Convert collection to array if needed
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->toArray();
        }

        // Recursively clean the data
        array_walk_recursive($data, function (&$value) {
            if (is_string($value)) {
                // Remove any non-UTF-8 characters
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                // Remove any HTML tags
                $value = strip_tags($value);
                // Trim whitespace
                $value = trim($value);
            }
        });

        return $data;
    }
}
