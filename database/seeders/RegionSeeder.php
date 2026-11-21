<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            // === KOTA SURABAYA ===
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Genteng', 'desa_kelurahan' => 'Embong Kaliasin'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Genteng', 'desa_kelurahan' => 'Ketabang'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Tegalsari', 'desa_kelurahan' => 'Dr. Soetomo'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Tegalsari', 'desa_kelurahan' => 'Keputran'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Wonokromo', 'desa_kelurahan' => 'Wonokromo'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Wonokromo', 'desa_kelurahan' => 'Jagir'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Dukuh Pakis', 'desa_kelurahan' => 'Dukuh Pakis'],
            ['kota_kabupaten' => 'Surabaya', 'kecamatan' => 'Dukuh Pakis', 'desa_kelurahan' => 'Pradah Kalikendal'],

            // === KABUPATEN GRESIK ===
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Gresik', 'desa_kelurahan' => 'Lumpur'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Gresik', 'desa_kelurahan' => 'Sukodono'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Cerme', 'desa_kelurahan' => 'Cerme Lor'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Cerme', 'desa_kelurahan' => 'Cerme Kidul'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Bungah', 'desa_kelurahan' => 'Bungah'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Bungah', 'desa_kelurahan' => 'Sidorejo'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Driyorejo', 'desa_kelurahan' => 'Driyorejo'],
            ['kota_kabupaten' => 'Gresik', 'kecamatan' => 'Driyorejo', 'desa_kelurahan' => 'Bambe'],
        ];

        DB::table('regions')->insert($regions);
    }
}