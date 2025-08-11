<?php

namespace App\Exports;

use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ClassRoom::with(['homeroomTeacher', 'students' => function($query) {
            $query->where('status', 'active');
        }])
        ->where('is_active', true)
        ->orderBy('grade')
        ->orderBy('name')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Kelas',
            'Nama Kelas',
            'Tingkat',
            'Kapasitas',
            'Jumlah Siswa',
            'Wali Kelas',
            'NIP Wali Kelas',
            'Ruang Kelas',
            'Tahun Ajaran',
            'Deskripsi',
            'Status'
        ];
    }

    /**
     * @param mixed $class
     * @return array
     */
    public function map($class): array
    {
        static $no = 1;
        
        return [
            $no++,
            $class->code,
            $class->name,
            $class->grade,
            $class->capacity,
            $class->students->count(),
            $class->homeroomTeacher ? $class->homeroomTeacher->name : 'Belum ada wali kelas',
            $class->homeroomTeacher ? $class->homeroomTeacher->nip : '-',
            $class->room ?? 'Belum ditentukan',
            $class->academic_year,
            $class->description ?? 'Tidak ada deskripsi',
            $class->is_active ? 'Aktif' : 'Tidak Aktif'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
