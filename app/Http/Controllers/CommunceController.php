<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Communce;
class CommunceController extends Controller
{
    //
    public function index($districtid = null)
    {
        if ($districtid) {
            $commnuce = Communce::where('district_id', $districtid)->get();
            return response()->json($commnuce);
        } else {
            $communce = Communce::all();
            return response()->json($communce);
        }
    }
}
