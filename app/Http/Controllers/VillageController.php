<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    //
    public function index($communceid=null)
    {
        if($communceid)
        {
            $village= Village::where('communce_id',$communceid)->get();
            return response()->json($village);
        }
        else
        {
            $village= Village::all();
            return response()->json($village);
        }

    }
}
