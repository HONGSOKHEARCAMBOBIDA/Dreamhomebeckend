<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wharehouse;
use Illuminate\Support\Facades\Auth;

class WharehouseController extends Controller // Fixed typo in class name
{
    /**
     * Store a newly created warehouse in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_warehouse' => 'required|integer',
            'status' => 'required|integer',// Corrected validation rule
            'village_id' => 'required|exists:villages,id',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Add the authenticated user's ID to the validated data
        $validated['created_by'] = $user->id;

        // Create the warehouse
        $warehouse = Wharehouse::create($validated);

        // Return the created warehouse as JSON response
        return response()->json($warehouse, 201); // 201 status code for resource creation
    }
    public function index()
    {
        // Check if the user is authenticated
       
            // Fetch the authenticated user's details with joins
            $warehouse = Wharehouse::
                join('villages', 'villages.id', '=', 'wharehouses.village_id')
                ->join('communces', 'communces.id', '=', 'villages.communce_id')
                ->join('districts', 'districts.id', '=', 'communces.district_id')
                ->join('provinces', 'provinces.id', '=', 'districts.province_id')
                ->select(
                    'wharehouses.id as wharehouses_id',
                    'wharehouses.name as wharehouses_name',
                    'wharehouses.type_warehouse as type_warehouse' ,
                    'wharehouses.status as status',
                    'provinces.name as province_name',
                    'districts.name as district_name',
                    'communces.name as communce_name',
                    'villages.name as village_name'
                )->get();
                 // Fetch only the authenticated user's data
                // Use `first()` instead of `get()` since we're fetching a single user
    
            // Return the user data as JSON
            return response()->json($warehouse);
       
    }
    public function update(Request $request, $id)
    {
        // Find the warehouse by ID or fail with a 404 error
        $warehouse = Wharehouse::findOrFail($id);
    
        // Validate the request data
        $validatedData = $request->validate([
     'name' => 'required|string|max:255',
        ]);
    
        // Update the warehouse with the validated data
        $warehouse->update($validatedData);
    
        // Return the updated warehouse as a JSON response
        return response()->json($warehouse);
    }
    public function delete($id)
    {
        // Find the warehouse by ID or fail with a 404 error
        $warehouse = Wharehouse::findOrFail($id);
    
        // Update the status to 0 (soft delete)
        $warehouse->update(['status' => 0]);
    
        // Return a success message
        return response()->json(['message' => 'Warehouse status updated to 0 (soft deleted)']);
    }
}