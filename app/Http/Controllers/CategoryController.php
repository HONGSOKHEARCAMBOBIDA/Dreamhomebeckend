<?php

namespace App\Http\Controllers;

use DB;
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
        DB::beginTransaction();
        try {
            $category = Category::create($validate);
            DB::commit();
            return response()->json($category, 201);


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create category',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function index()
    {
        DB::beginTransaction();
        try
        {
            $category = Category::all();
            return response()->json($category);

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['message'=>$e->getMessage()]);
        }
       
    }
    public function update(Request $request, $id)
    {
        // Find the category by ID or return a 404 error if not found
        $category = Category::findOrFail($id);
        $user = Auth::user();
    
        // Validate the request data with custom messages
        $validate = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The category name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
        ]);
    
        // Add the updater's user ID
        $validate['updated_by'] = $user->id;
    
        // Check authorization (e.g., using Laravel policies)
       
    
        DB::beginTransaction();
        try {
            // Update the category with the validated data
            $category->update($validate);
            DB::commit(); // Commit the transaction
    
            // Return the updated category and success message
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category->fresh(), // Return refreshed data
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to update category (ID: $id): " . $e->getMessage());
    
            return response()->json([
                'message' => 'Failed to update category',
                'error' => $e->getMessage(),
            ], 500); // Include HTTP status code for errors
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $category->delete(); // Use delete() on the instance
    
            DB::commit(); // Commit the transaction
    
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error
    
            // Log the error for debugging purposes
            \Log::error('Error deleting category: ' . $e->getMessage());
    
            // Return a generic error message
            return response()->json(['message' => 'Failed to delete category'], 500);
        }
    }
}
