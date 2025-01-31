<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Measurement;
use Validator;
class MeasurementController extends Controller
{
    //
    public function store(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Validate the request data
        $validate = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Add the authenticated user's ID to the validated data
        $validate['created_by'] = $user->id;


        // Create the category
        $measurement = Measurement::create($validate);

        // Return the created category as a JSON response
        return response()->json($measurement);
    }
    public function index()
    {
        $measurement = Measurement::all();
        return response()->json($measurement);
    }
    public function update(Request $request, $id)
    {
        // Find the category by ID or return a 404 error if not found
        $measurement = Measurement::findOrFail($id);
        $user = Auth::user();

        // Validate the request data
        $validate = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $validate['updated_by'] = $user->id;

        // Update the category with the validated data
        $measurement->update($validate);

        // Return a JSON response with the updated category and a success message
        return response()->json([
            'message' => 'Category updated successfully',

        ], 200);
    }
    public function delete($id)
    {
        $measurement = Measurement::findOrFail($id);
        $measurement->delete(); // Use delete() on the instance
        return response()->json(['message' => 'deleted']);
    }
}
