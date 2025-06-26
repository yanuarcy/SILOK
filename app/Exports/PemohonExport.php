<?php

namespace App\Exports;

use App\Models\Pemohon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Contracts\View\View;

class PemohonExport implements FromView, WithColumnWidths, WithStyles
{
    protected $data;
    protected $metadata;

    public function __construct($data, $metadata = [])
    {
        $this->data = $data;
        $this->metadata = $metadata;
    }

    /**
     * Return a view for the Excel export
     */
    public function view(): View
    {
        return view('admin.masterdata.Pemohon.ExportExcel', [
            'data' => $this->data,
            'start_date' => $this->metadata['start_date'] ?? now(),
            'end_date' => $this->metadata['end_date'] ?? now(),
            'generated_at' => $this->metadata['generated_at'] ?? now(),
            'filters' => $this->metadata['filters'] ?? []
        ]);
    }

    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // Tanggal
            'C' => 25,  // Nama
            'D' => 15,  // No WhatsApp
            'E' => 35,  // Alamat
            'F' => 20,  // Jenis Layanan
            'G' => 30,  // Keterangan
            'H' => 15,  // Jenis Antrian
            'I' => 20,  // Jenis Pengiriman
            'J' => 15,  // Status
            'K' => 20,  // Dilayani Oleh
            'L' => 20,  // Tanggal Dilayani
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // ✅ Header styling (row 1-7 untuk metadata dan header)
        $sheet->getStyle('A1:L7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // ✅ Title styling (row 1)
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2c5aa0']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fa']
            ]
        ]);

        // ✅ Stats cards styling (row 4-5)
        $sheet->getStyle('A4:C5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366f1']
            ],
            'font' => ['color' => ['rgb' => 'ffffff']]
        ]);

        $sheet->getStyle('D4:F5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10b981']
            ],
            'font' => ['color' => ['rgb' => 'ffffff']]
        ]);

        $sheet->getStyle('G4:I5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ef4444']
            ],
            'font' => ['color' => ['rgb' => 'ffffff']]
        ]);

        $sheet->getStyle('J4:L5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f59e0b']
            ],
            'font' => ['color' => ['rgb' => 'ffffff']]
        ]);

        // ✅ Table header styling (row 7)
        $sheet->getStyle('A7:L7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'ffffff']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '374151']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // ✅ Data rows styling (from row 8 onwards)
        $dataRowCount = $this->data->count();
        if ($dataRowCount > 0) {
            $lastRow = 7 + $dataRowCount;

            $sheet->getStyle("A8:L{$lastRow}")->applyFromArray([
                'font' => ['size' => 10],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'dee2e6']
                    ]
                ]
            ]);

            // ✅ Zebra striping for data rows
            for ($row = 8; $row <= $lastRow; $row++) {
                if (($row - 8) % 2 == 0) {
                    $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'f8f9fa']
                        ]
                    ]);
                }
            }

            // ✅ Center alignment for specific columns
            $sheet->getStyle("A8:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
            $sheet->getStyle("B8:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal
            $sheet->getStyle("D8:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // WhatsApp
            $sheet->getStyle("H8:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jenis Antrian
            $sheet->getStyle("I8:I{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Pengiriman
            $sheet->getStyle("J8:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status
            $sheet->getStyle("K8:K{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Dilayani Oleh
            $sheet->getStyle("L8:L{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal Dilayani
        }

        // ✅ Set row heights
        $sheet->getRowDimension(1)->setRowHeight(25); // Title
        $sheet->getRowDimension(4)->setRowHeight(20); // Stats title
        $sheet->getRowDimension(5)->setRowHeight(25); // Stats values
        $sheet->getRowDimension(7)->setRowHeight(30); // Table header

        // ✅ Auto-fit row heights for data
        for ($row = 8; $row <= 7 + $dataRowCount; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(-1); // Auto height
        }

        return $sheet;
    }
}
