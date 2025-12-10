<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Region::all(), 200);
    }

    // obtener las comunas asociadas a una región
    public function communes($region_id)
    {
        $region = Region::with('communes')->find($region_id);

        if (!$region) {
            return response()->json(['error' => 'Region not found'], 404);
        }

        return response()->json($region->communes, 200);
    }

}
