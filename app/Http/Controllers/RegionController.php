<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getKota()
    {
        $kota = Region::select('kota_kabupaten')->distinct()->pluck('kota_kabupaten');
        return response()->json($kota);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = Region::where('kota_kabupaten', $request->kota)
            ->select('kecamatan')->distinct()->pluck('kecamatan');
        return response()->json($kecamatan);
    }

    public function getDesa(Request $request)
    {
        $desa = Region::where('kota_kabupaten', $request->kota)
            ->where('kecamatan', $request->kecamatan)
            ->pluck('desa_kelurahan');
        return response()->json($desa);
    }
}