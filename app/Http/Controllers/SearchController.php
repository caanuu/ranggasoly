<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class SearchController extends Controller
{
    public function liveSearch(Request $request)
    {
        $query = $request->input('query');

        $employees = Employee::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'nomor_pegawai']);

        return response()->json($employees);
    }
    
}