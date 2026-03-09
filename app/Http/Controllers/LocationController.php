<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
