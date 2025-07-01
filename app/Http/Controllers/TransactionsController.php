<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    // List all transactions or filter by creator or user
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('creator_id')) {
            $query->where('creator_id', $request->creator_id);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->orderBy('processed_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    // Show single transaction
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    // Create new transaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'creator_id' => 'required|integer|exists:creators,id',
            'transaction_id' => 'required|string|unique:transactions,transaction_id',
            'order_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'currency' => 'required|string|size:3',
            'platform_fee' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_gateway' => 'required|string',
            'items' => 'nullable|array',
            'type' => 'nullable|string',
            'status' => 'required|string',
            'processed_at' => 'nullable|date',
            'refunded_at' => 'nullable|date',
        ]);

        // JSON encode items if present
        if (isset($validated['items'])) {
            $validated['items'] = json_encode($validated['items']);
        }

        $transaction = Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully',
            'data' => $transaction,
        ], 201);
    }

    // Update transaction
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'creator_id' => 'sometimes|integer|exists:creators,id',
            'transaction_id' => 'sometimes|string|unique:transactions,transaction_id,' . $id,
            'order_id' => 'sometimes|string',
            'amount' => 'sometimes|numeric|min:0',
            'payment_type' => 'sometimes|string',
            'currency' => 'sometimes|string|size:3',
            'platform_fee' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|string',
            'payment_gateway' => 'sometimes|string',
            'items' => 'sometimes|array',
            'type' => 'sometimes|string',
            'status' => 'sometimes|string',
            'processed_at' => 'sometimes|date',
            'refunded_at' => 'sometimes|date',
        ]);

        if (isset($validated['items'])) {
            $validated['items'] = json_encode($validated['items']);
        }

        $transaction->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'data' => $transaction,
        ]);
    }

    // Delete transaction
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully',
        ]);
    }
}
