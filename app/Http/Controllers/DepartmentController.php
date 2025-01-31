<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department; // Import the Department model

class DepartmentController extends Controller
{
    // Store a new department
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Corrected validation rule
        ]);

        // Create the department
        $department = Department::create($validated);

        // Return a response
        return response()->json([
            'message' => 'Department created successfully!',

        ], 201); // HTTP status code 201: Created
    }

    // Get all departments
    public function index()
    {
        $departments = Department::all(); // Corrected variable name to plural
        return response()->json($departments);
    }
    public function update(Request $request, $id)
    {
        // Find the department by ID or throw a 404 error if not found
        $department = Department::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Corrected validation rule
        ]);

        // Update the department with the validated data
        $department->update($validated);

        // Return a JSON response with the updated department
        return response()->json([
            'message' => 'Department updated successfully!',
            
        ]);
    }
    public function delete($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->delete();
            return response()->json(['message' => 'Deleted'], 200);
        } else {
            // Return a response indicating the record was not found
            return response()->json(['message' => 'Department not found'], 404);
        }
    }

}