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
        // Using the same endpoint as observed in ModalCariSekolah but simpler search
        $response = Http::get('https://api.data.belajar.id/data-portal-backend/v1/master-data/satuan-pendidikan/search', [
            'keyword' => $keyword,
            'bentukPendidikan' => 'dikmen', // Assuming we mostly want secondary education as implied by the HighSchool context, but can be removed if general search is needed.
             // Based on user request context ("Jurusan SMA/Sederajat"), restricting to Dikmen (SMA/SMK/MA) seems appropriate or just general search.
             // The modal used "dikmen" in one of the URLs. Let's try general first or stick to what seems to be the default for the modal which was seemingly complex.
             // Actually, the modal had separate logic for province->city->district. The search endpoint I saw in browser history used `keyword=negeri&bentukPendidikan=dikmen`.
             // I will use `bentukPendidikan` to keep it relevant to High Schools.
             'limit' => 20,
        ]);

        $data = $response->json('data') ?? [];

        $formatted = collect($data)->map(function ($item) {
            return [
                'id' => $item['npsn'], // Using NPSN as the ID for Select2 value
                'text' => $item['nama'],
                // Extra data for client-side handling
                'satuanPendidikanId' => $item['satuanPendidikanId'],
                'npsn' => $item['npsn'],
                'name' => $item['nama'],
                'address' => $item['alamatJalan'],
                'village' => $item['namaDesa'],
                // 'city' => ... the API response doesn't seem to have city/province names directly in the flat list I saw,
                // but checking the previous `view_text_website` output:
                // It has `namaDesaDagri` etc.
                // Wait, the API response I saw earlier: `{"satuanPendidikanId":..., "namaDesa":...}`
                // It does NOT seem to return City or Province names directly in the search list, just `namaDesa`.
                // However, the `ModalCariSekolah` implementation didn't save city/province either, only name, address, village.
                // So I will stick to that.
            ];
        });

        return response()->json([
            'results' => $formatted
        ]);
    }
}
