<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\School::create([
            'name' => 'SMAN 1 Nagreg',
            'address' => 'Jl. Raya Nagreg No. 123, Nagreg, Bandung, Jawa Barat',
            'phone' => '022-5956789',
            'email' => 'info@smansan.sch.id',
            'website' => 'https://www.smansan.sch.id',
            'description' => 'SMA Negeri 1 Nagreg adalah sekolah menengah atas negeri yang berlokasi di Nagreg, Kabupaten Bandung, Jawa Barat.'
        ]);
    }
}
