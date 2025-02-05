<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
class DistrictController extends Controller
{
    //
    public function index($provinceid = null)
    {
        if ($provinceid) {
            $district = District::where('province_id', $provinceid)->get();
            return response()->json($district);

        } else {
            $district = District::all();
            return response()->json($district);
        }

    }
}
