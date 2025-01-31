<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'wh_id' => 'required|exists:wharehouses,id', // Ensure the warehouse exists
            'item_id' => 'required|exists:items,id', // Ensure the item exists
            'qty_in' => 'nullable|numeric', // Quantity in (optional)
            'qty_out' => 'nullable|numeric', // Quantity out (optional)
            'number' => 'required|numeric', // Number for transaction
            'type_transaction' => 'required|numeric', // Type of transaction
            'wh_export_to_id' => 'nullable|exists:wharehouses,id', // Warehouse to export to (optional)
            'description' => 'required|string|max:255'
        ]);

        // Add the authenticated user's ID to the validated data
        $validatedData['user_id'] = Auth::id();

        try {
            // Handle different types of transactions
            switch ($validatedData['type_transaction']) {
                case 1: // Purchase In
                    $validatedData['qty_in'] = $validatedData['number']; // Set qty_in to the number
                    $validatedData['qty_out'] = 0; // Set qty_out to 0
                    break;

                case 2: // Export Out
                    // Validate that wh_export_to_id is provided for export transactions
                    if (empty($validatedData['wh_export_to_id'])) {
                        return response()->json(['message' => 'Export warehouse ID is required for export transactions.'], 400);
                    }

                    // Create the export out transaction
                    $transactionOut = Transaction::create([
                        'wh_id' => $validatedData['wh_id'],
                        'item_id' => $validatedData['item_id'],
                        'qty_in' => 0,
                        'qty_out' => $validatedData['number'],
                        'type_transaction' => $validatedData['type_transaction'],
                        'user_id' => $validatedData['user_id'],
                        'description' => $validatedData['description'],
                    ]);

                    // Create the import transaction for the destination warehouse
                    $transactionIn = Transaction::create([
                        'wh_id' => $validatedData['wh_export_to_id'],
                        'item_id' => $validatedData['item_id'],
                        'qty_in' => $validatedData['number'],
                        'qty_out' => 0,
                        'type_transaction' => $validatedData['type_transaction'],
                        'user_id' => $validatedData['user_id'],
                        'description' => $validatedData['description'],
                    ]);

                    return response()->json([
                        'message' => 'Export transactions created successfully.',
                        'transactions' => [$transactionOut, $transactionIn]
                    ], 201);

                case 3: // Damaged Goods
                    $validatedData['qty_in'] = 0; // Set qty_in to 0
                    $validatedData['qty_out'] = $validatedData['number']; // Set qty_out to the number
                    break;

                default:
                    return response()->json(['message' => 'Invalid transaction type.'], 400);
            }

            // Create the Transaction record for type 1 or 3
            $transaction = Transaction::create($validatedData);

            // Return the created Transaction record as a JSON response
            return response()->json([
                'message' => 'Transaction created successfully.',

            ], 201); // HTTP 201 status code for "Created"

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error("Error in store function: " . $e->getMessage());

            // Handle any exceptions that occur during the transaction creation
            return response()->json([
                'message' => 'Failed to create transaction.',
                'error' => $e->getMessage()
            ], 500); // HTTP 500 status code for "Internal Server Error"
        }
    }
    public function sum($warehouseID = null)
    {
        // Initialize the query
        $query = Transaction::join('items', 'items.id', '=', 'transactions.item_id')
            ->join('wharehouses', 'wharehouses.id', '=', 'transactions.wh_id')
            ->select(
                'items.id as item_id', // Include item_id for grouping
                'items.name',

                'wharehouses.name as warehouse_name',
                DB::raw('SUM(qty_in) as total_item_in'),
                DB::raw('SUM(qty_out) as total_item_out'),
                DB::raw('SUM(qty_in) - SUM(qty_out) as total_balance')
            );

        // Apply the warehouse filter if provided
        if ($warehouseID) {
            $query->where('transactions.wh_id', $warehouseID);
        }

        // Group by item_id, name, and wh_id to avoid SQL errors
        $sum = $query->groupBy('items.id', 'items.name', 'wharehouses.name')->get();

        // Return the result as JSON
        return response()->json($sum);
    }
    public function brokenitem($warehouseID = null)
    {
        $query = Transaction::join('items', 'items.id', '=', 'transactions.item_id')
            ->join('wharehouses', 'wharehouses.id', '=', 'transactions.wh_id')
            ->select(
                'items.id as item_id', // Include item_id for grouping
                'items.name',

                'wharehouses.name as warehouse_name',
                DB::raw('SUM(qty_in) as total_item_in'),
                DB::raw('SUM(qty_out) as total_item_out'),
                DB::raw('SUM(qty_in) - SUM(qty_out) as total_balance')
            )->where('transactions.type_transaction', 3);

        // Apply the warehouse filter if provided
        if ($warehouseID) {
            $query->where('transactions.wh_id', $warehouseID);
        }

        // Group by item_id, name, and wh_id to avoid SQL errors
        $sum = $query->groupBy('items.id', 'items.name', 'wharehouses.name')->get();

        // Return the result as JSON
        return response()->json($sum);
    }
    public function delete($id)
    {
        // Corrected method name
        $transaction = Transaction::findOrFail($id);

        // Use the delete method on the instance
        $transaction->delete();

        return response()->json(['message' => 'deleted']);
    }
}