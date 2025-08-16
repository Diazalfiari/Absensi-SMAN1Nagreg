<?php

namespace App\Exports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SubjectsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Subject::with('schedules')->orderBy('category')->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Mata Pelajaran',
            'Nama Mata Pelajaran',
            'Kategori',
            'Jam per Minggu',
            'Deskripsi',
            'Status Jadwal',
            'Jumlah Jadwal',
            'Dibuat Tanggal',
            'Terakhir Diubah'
        ];
    }

    public function map($subject): array
    {
        static $number = 1;
        
        return [
            $number++,
            $subject->code ?? '-',
            $subject->name ?? '-',
            $subject->category ?? '-',
            ($subject->credit_hours ?? 0) . ' Jam',
            $subject->description ?: '-',
            ($subject->schedules && $subject->schedules->count() > 0) ? 'Terjadwal' : 'Belum Dijadwalkan',
            ($subject->schedules ? $subject->schedules->count() : 0),
            $subject->created_at ? $subject->created_at->format('d/m/Y H:i') : '-',
            $subject->updated_at ? $subject->updated_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style for data rows
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:J' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Alternating row colors
        for ($i = 2; $i <= $highestRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':J' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA'],
                    ],
                ]);
            }
        }

        // Center align certain columns
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal('center'); // No
        $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal('center'); // Kode
        $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal('center'); // Kategori
        $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal('center'); // Jam
        $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal('center'); // Status
        $sheet->getStyle('H2:H' . $highestRow)->getAlignment()->setHorizontal('center'); // Jumlah

        return [];
    }
}
