<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CheckTime;

class ChecktimeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'wh_id' => 'required|exists:wharehouses,id', // Ensure the warehouse exists
            'check_date' => 'required|date', // Ensure the check_date is a valid date
        ]);

        // Add the authenticated user's ID to the validated data
        $validatedData['created_by'] = Auth::id();

        // Create the CheckTime record
        $checktime = CheckTime::create($validatedData);

        // Return the created CheckTime record as a JSON response
        return response()->json($checktime, 201); // HTTP 201 status code for "Created"
    }
    public function index()
    {
        $checktime = CheckTime::join('wharehouses', 'wharehouses.id', '=', 'check_times.wh_id')->select('wharehouses.name', 'check_times.check_date')->get();
        return response()->json($checktime);
    }
    public function getbydate($date = null)
    {
        try {
            // Base query with join and select
            $query = CheckTime::join('wharehouses', 'wharehouses.id', '=', 'check_times.wh_id')
                ->select('wharehouses.name', 'check_times.check_date');

            // Apply date filter if $date is provided
            if ($date) {
                $query->whereDate('check_times.check_date', '=', $date);
            }

            // Execute the query
            $checktime = $query->get();

            // Check if any records were found
            if ($checktime->isEmpty()) {
                return response()->json(['message' => 'No records found.'], 404);
            }

            // Return the results
            return response()->json($checktime);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'An error occurred while fetching records.'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $checktime = CheckTime::find($id);
        $validatedData = $request->validate([
            'wh_id' => 'sometimes|exists:wharehouses,id', // Ensure the warehouse exists
            'check_date' => 'sometimes|date', // Ensure the check_date is a valid date
        ]);
        $validatedData['updated_by'] = $user->id;

        $checktime->update($validatedData);
        return response()->json(['message' => 'updated']);
    }
    public function delete($id)
    {
        $checktime = CheckTime::find($id);
        $checktime->delete();
        return response()->json(['message' => 'deleted']);
    }
}