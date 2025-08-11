<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeachersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Teacher::with(['user', 'subjects'])
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
            'NIP',
            'Nama Lengkap',
            'Email',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'No. HP',
            'Tingkat Pendidikan',
            'Jurusan/Bidang Studi',
            'Mata Pelajaran yang Diampu',
            'Tanggal Mulai Mengajar',
            'Status',
            'Tanggal Export'
        ];
    }

    /**
     * @param mixed $teacher
     * @return array
     */
    public function map($teacher): array
    {
        // Get subjects as comma-separated string
        $subjects = $teacher->subjects->pluck('name')->implode(', ');
        
        return [
            $teacher->nip ?? '-',
            $teacher->name,
            $teacher->email,
            $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan',
            $teacher->birth_place ?? '-',
            $teacher->birth_date ? $teacher->birth_date->format('d/m/Y') : '-',
            $teacher->address ?? '-',
            $teacher->phone ?? '-',
            $this->getEducationLevel($teacher->education_level),
            $teacher->major ?? '-',
            $subjects ?: 'Belum ada mata pelajaran',
            $teacher->hire_date ? $teacher->hire_date->format('d/m/Y') : '-',
            ucfirst($teacher->status),
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
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set background color for header
            'A1:N1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E3F2FD']
                ]
            ],
        ];
    }

    /**
     * Get formatted education level
     */
    private function getEducationLevel($level)
    {
        switch ($level) {
            case 'S1':
                return 'S1 (Sarjana)';
            case 'S2':
                return 'S2 (Magister)';
            case 'S3':
                return 'S3 (Doktor)';
            default:
                return $level ?? '-';
        }
    }
}
