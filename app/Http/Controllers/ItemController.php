<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Item;
use Validator;
class ItemController extends Controller
{
    //
    public function store(Request $request)
    {
        $user = Auth::user();
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'item_type_id' => 'required|exists:item_types,id',
            'measurement_id' => 'required|exists:measurements,id',
            'value_measurement' => 'required|string|max:255',

        ]);
        $validate['created_by'] = $user->id;
        $item = Item::create($validate);
        return response()->json($item);

    }
    public function getItembyitemType($id = null)
    {
        if ($id) {
            $item = Item::join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->join('item_types', 'item_types.id', '=', 'items.item_type_id')
                ->where('item_types.id', $id)
                ->select(
                    'items.name as item_name',
                    'items.value_measurement',
                    'item_types.name as item_type_name',
                    'measurements.name as measurement_name'
                )
                ->get();
        } else {
            $item = Item::join('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->join('item_types', 'item_types.id', '=', 'items.item_type_id')
                ->select(
                    'items.name as item_name',
                    'items.value_measurement',
                    'item_types.name as item_type_name',
                    'measurements.name as measurement_name'
                )
                ->get();
        }

        return $item;
    }
    public function update(Request $request, $id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Find the item or fail with a 404 error
        $item = Item::findOrFail($id);

        // Validate the request data
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'item_type_id' => 'sometimes|exists:item_types,id', // Corrected to 'sometimes'
            'measurement_id' => 'sometimes|exists:measurements,id', // Corrected to 'sometimes'
            'value_measurement' => 'sometimes|string|max:255', // Use 'numeric' if it's a number
        ]);

        // Add the authenticated user's ID to the validated data
        $validate['updated_by'] = $user->id;

        // Update the item with the validated data
        $item->update($validate);

        // Return a JSON response with the updated item
        return response()->json([
            'message' => 'Item updated successfully',

        ], 200); // HTTP status code 200 for success
    }
    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->delete(); // Use delete() on the instance
        return response()->json(['message' => 'deleted']);
    }
}
