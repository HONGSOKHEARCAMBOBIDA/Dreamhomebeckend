<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
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
        $category = Category::create($validate);

        // Return the created category as a JSON response
        return response()->json($category);
    }
    public function index()
    {
        $category = Category::all();
        return response()->json($category);
    }
    public function update(Request $request, $id)
    {
        // Find the category by ID or return a 404 error if not found
        $category = Category::findOrFail($id);
        $user = Auth::user();

        // Validate the request data
        $validate = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $validate['updated_by'] = $user->id;

        // Update the category with the validated data
        $category->update($validate);

        // Return a JSON response with the updated category and a success message
        return response()->json([
            'message' => 'Category updated successfully',

        ], 200);
    }
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete(); // Use delete() on the instance
        return response()->json(['message' => 'deleted']);
    }
}
