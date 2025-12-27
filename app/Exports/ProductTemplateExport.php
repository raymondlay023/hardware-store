<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductTemplateExport implements FromArray, WithStyles, WithColumnWidths, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row (row 1)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8B5CF6'], // Purple
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Style sample data rows
            '2:4' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3F4F6'], // Light gray
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // name
            'B' => 18, // brand (NEW)
            'C' => 15, // category
            'D' => 10, // unit
            'E' => 12, // price
            'F' => 10, // stock
            'G' => 20, // supplier
            'H' => 30, // aliases (NEW)
            'I' => 20, // low_stock_threshold
            'J' => 22, // critical_stock_threshold
            'K' => 15, // auto_reorder
            'L' => 18, // reorder_quantity
        ];
    }
}
