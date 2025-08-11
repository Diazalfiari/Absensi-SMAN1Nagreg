<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::with(['classRoom', 'user'])
                     ->orderBy('status', 'desc')
                     ->orderBy('name')
                     ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NISN',
            'NIS',
            'Nama Lengkap',
            'Email',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'No. HP Siswa',
            'No. HP Orang Tua',
            'Kelas',
            'Tanggal Masuk',
            'Status',
            'Tanggal Export'
        ];
    }

    /**
     * @param mixed $student
     * @return array
     */
    public function map($student): array
    {
        return [
            $student->nisn,
            $student->nis,
            $student->name,
            $student->email,
            $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
            $student->birth_place,
            $student->birth_date ? $student->birth_date->format('d/m/Y') : '',
            $student->address,
            $student->phone ?? '-',
            $student->parent_phone ?? '-',
            $student->classRoom->name ?? 'Belum ada kelas',
            $student->entry_date ? $student->entry_date->format('d/m/Y') : '',
            $student->status === 'active' ? 'Aktif' : 'Tidak Aktif',
            now()->format('d/m/Y H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
        ];
    }
}
