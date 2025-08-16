<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SchedulesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Schedule::with(['subject', 'teacher', 'classRoom'])
                      ->orderBy('day')
                      ->orderBy('start_time')
                      ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
            'Mata Pelajaran',
            'Kelas',
            'Guru',
            'Ruangan',
            'Status',
            'Dibuat Tanggal',
            'Terakhir Diubah'
        ];
    }

    public function map($schedule): array
    {
        static $number = 1;
        
        return [
            $number++,
            $schedule->day ?? '-',
            $schedule->start_time ?? '-',
            $schedule->end_time ?? '-',
            $schedule->subject->name ?? '-',
            $schedule->classRoom->name ?? '-',
            $schedule->teacher->name ?? '-',
            $schedule->room ?? '-',
            ($schedule->is_active ?? true) ? 'Aktif' : 'Tidak Aktif',
            $schedule->created_at ? $schedule->created_at->format('d/m/Y H:i') : '-',
            $schedule->updated_at ? $schedule->updated_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:K1')->applyFromArray([
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
        $sheet->getStyle('A2:K' . $highestRow)->applyFromArray([
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
                $sheet->getStyle('A' . $i . ':K' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA'],
                    ],
                ]);
            }
        }

        // Center align certain columns
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal('center'); // No
        $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal('center'); // Hari
        $sheet->getStyle('C2:C' . $highestRow)->getAlignment()->setHorizontal('center'); // Jam Mulai
        $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal('center'); // Jam Selesai
        $sheet->getStyle('I2:I' . $highestRow)->getAlignment()->setHorizontal('center'); // Status

        return [];
    }
}
