<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function cities(Request $request)
    {
        $search = $request->get('q');
        
        $cities = DB::table(config('laravolt.indonesia.table_prefix') . 'cities')
            ->select('name as id', 'name as text')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json([
            'results' => $cities
        ]);
    }

    public function searchSchools(Request $request)
    {
        $keyword = $request->get('q');

        if (!$keyword) {
            return response()->json(['results' => []]);
        }

        // Call the external API
        $response = Http::get('https://api.data.belajar.id/data-portal-backend/v1/master-data/satuan-pendidikan/search', [
            'keyword' => $keyword,
            'bentukPendidikan' => 'dikmen',
            'limit' => 20,
        ]);

        $data = $response->json('data') ?? [];

        $formatted = collect($data)->map(function ($item) {
            return [
                'id' => $item['npsn'] ?? uniqid(), // Fallback ID if NPSN missing
                'text' => $item['nama'] ?? 'Sekolah Tanpa Nama',
                'satuanPendidikanId' => $item['satuanPendidikanId'] ?? null,
                'npsn' => $item['npsn'] ?? null,
                'name' => $item['nama'] ?? 'Sekolah Tanpa Nama',
                'address' => $item['alamatJalan'] ?? '',
                'village' => $item['namaDesa'] ?? '',
            ];
        });

        return response()->json([
            'results' => $formatted
        ]);
    }
}
