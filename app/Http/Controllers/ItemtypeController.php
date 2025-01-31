<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ItemType;
use Validator;
class ItemtypeController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the request data
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id', // Ensure category_id exists in the categories table
            ]);

            // Add the authenticated user's ID to the validated data
            $validate['created_by'] = $user->id;

            // Create the item type
            $itemtype = ItemType::create($validate);

            // Return the created item type as a JSON response with a 201 status code
            return response()->json([
                'message' => 'Item type created successfully',
               
            ], 201);

        } catch (\Exception $e) {
            // Handle any exceptions (e.g., database errors)
            return response()->json([
                'message' => 'Failed to create item type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getItemTypebycategory($CategoryId = null)
    {
        if ($CategoryId) {
            $itemtype = ItemType::where('category_id', $CategoryId)->get();
        } else {
            $itemtype = ItemType::all();
        }
        return response()->json($itemtype);
    }
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $itemtype = ItemType::findOrFail($id); // Corrected method name

        $validate = $request->validate([
            'name' => 'sometimes|string|max:255', // Corrected validation rule
            'category_id' => 'sometimes|exists:categories,id', // Corrected validation rule
        ]);

        $validate['updated_by'] = $user->id; // Ensure the key is 'updated_by' if that's what you intend

        $itemtype->update($validate); // Update the item type with validated data

        return response()->json(['message' => 'updated']); // Corrected response method
    }
    public function delete($id)
    {
        $itemtype = ItemType::findOrFail($id);
        $itemtype->delete(); // Use delete() on the instance
        return response()->json(['message' => 'deleted']);
    }
}
