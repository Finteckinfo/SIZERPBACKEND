<?php

namespace App\Http\Controllers;

use App\Models\Creators;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreatorsController extends Controller
{
    /**
     * Fetch all creators with optional sorting
     */
    public function fetchallcreators(Request $request)
    {
        $query = Creators::query();

        // Optional: Sort by total gross if requested
        if ($request->has('sort') && $request->sort === 'gross') {
            $query->orderBy('total_gross', 'desc');
        }

        $creators = $query->get();

        return response()->json([
            'success' => true,
            'data' => $creators,
        ]);
    }

    public function addCreator(Request $request)
    {
        try {
            $validated = $request->validate([
                'profile' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:creators,email|max:255',
                'password' => 'required|string|min:6|max:255',
                'creator_type' => ['required', Rule::in(['Direct Pay', 'Non-Direct Pay'])],
                'on_board_date' => 'nullable|date',
                'off_board_date' => 'nullable|date',
                'google_sheet_name' => 'required|string|max:255',
                'google_sheet_name_new_template' => 'required|string|max:255',
                'account_group' => ['required', Rule::in(['FreePages', 'Default'])],
                'on_platform_date' => 'nullable|date',
                'off_platform_date' => 'nullable|date',
                'is_active' => 'boolean',
                'on_platform' => 'boolean',
                'archived' => 'boolean',
            ]);

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Set defaults for optional fields
            $validated['is_active'] = $validated['is_active'] ?? true;
            $validated['on_platform'] = $validated['on_platform'] ?? true;
            $validated['archived'] = $validated['archived'] ?? false;

            $creator = Creators::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Creator added successfully',
                'data' => $creator,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create creator',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update (Edit) a creator
     */
    public function update(Request $request, $id)
    {
        $creator = Creators::find($id);

        if (!$creator) {
            return response()->json([
                'success' => false,
                'message' => 'Creator not found',
            ], 404);
        }

        $validated = $request->validate([
            'profile' => 'sometimes|nullable|string|max:255',
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('creators')->ignore($id)],
            'password' => 'sometimes|string|min:6|max:255',
            'creator_type' => ['sometimes', Rule::in(['Direct Pay', 'Non-Direct Pay'])],
            'on_board_date' => 'sometimes|nullable|date',
            'off_board_date' => 'sometimes|nullable|date',
            'google_sheet_name' => 'sometimes|string|max:255',
            'google_sheet_name_new_template' => 'sometimes|string|max:255',
            'account_group' => ['sometimes', Rule::in(['FreePages', 'Default'])],
            'on_platform_date' => 'sometimes|nullable|date',
            'off_platform_date' => 'sometimes|nullable|date',
            'is_active' => 'sometimes|boolean',
            'on_platform' => 'sometimes|boolean',
            'archived' => 'sometimes|boolean',
        ]);

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $creator->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Creator updated successfully',
            'data' => $creator,
        ]);
    }

    /**
     * Delete a creator
     */
    public function destroy($id)
    {
        $creator = Creators::find($id);

        if (!$creator) {
            return response()->json([
                'success' => false,
                'message' => 'Creator not found',
            ], 404);
        }

        $creator->delete();

        return response()->json([
            'success' => true,
            'message' => 'Creator deleted successfully',
        ]);
    }

    /**
     * Fetch a single creator by ID
     */
    public function fetchsinglecreator($id)
    {
        $creator = Creators::find($id);

        if (!$creator) {
            return response()->json([
                'success' => false,
                'message' => 'Creator not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $creator,
        ]);
    }

    /**
     * Fetch a single creator by ID
     */
    public function show($id)
    {
        $creator = Creators::find($id);

        if (!$creator) {
            return response()->json([
                'success' => false,
                'message' => 'Creator not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $creator,
        ]);
    }

    /**
     * Fetch a creator with transactions
     */
    public function showWithTransactions($id)
    {
        $creator = Creators::with('transactions')->find($id);

        if (!$creator) {
            return response()->json([
                'success' => false,
                'message' => 'Creator not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $creator,
        ]);
    }

    public function fetchCreatorsTopSpenders($id)
    {
        // Check if creator exists
        $creator = Creators::find($id);
        if (!$creator) {
            return response()->json(['message' => 'Creator not found'], 404);
        }

        // Calculate date ranges
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $lastYearStart = now()->subYear()->startOfYear();
        $lastYearEnd = now()->subYear()->endOfYear();

        // Get all spenders for this creator with time-based calculations
        $spenders = DB::table('spenders')
            ->selectRaw(
                '*,
            CASE
                WHEN created_at >= ? AND created_at <= ?
                THEN total_spent_net
                ELSE 0
            END as lastMonthSpent,
            CASE
                WHEN created_at >= ? AND created_at <= ?
                THEN total_spent_net
                ELSE 0
            END as lastYearSpent',
                [$lastMonthStart, $lastMonthEnd, $lastYearStart, $lastYearEnd]
            )
            ->where('creators_id', $id)
            ->orderByDesc('total_spent_net')
            ->get();

        // Process the data to create different top spender lists
        $allSpenders = $spenders->sortByDesc('total_spent_net')->values();

        // Top 5 spenders for last month (only those who spent last month)
        $lastMonthTopSpenders = $spenders
            ->filter(function ($spender) {
                return $spender->lastMonthSpent > 0;
            })
            ->sortByDesc('lastMonthSpent')
            ->take(5)
            ->values();

        // Top 5 spenders for last year (only those who spent last year)
        $lastYearTopSpenders = $spenders
            ->filter(function ($spender) {
                return $spender->lastYearSpent > 0;
            })
            ->sortByDesc('lastYearSpent')
            ->take(5)
            ->values();

        // Top 5 all-time spenders
        $allTimeSpenders = $spenders
            ->sortByDesc('total_spent_net')
            ->take(5)
            ->values();

        return response()->json([
            'creator' => $creator,
            'allSpenders' => $allSpenders,
            'lastMonthTopSpenders' => $lastMonthTopSpenders,
            'lastYearTopSpenders' => $lastYearTopSpenders,
            'allTimeSpenders' => $allTimeSpenders
        ]);
    }
}
