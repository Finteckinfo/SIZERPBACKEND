<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DailyGoal;
use App\Models\Creators;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DailyGoalController extends Controller
{
    /**
     * Get daily goals for a specific creator
     */
    public function getCreatorDailyGoals(Request $request, $creatorId): JsonResponse
    {
        try {
            // Check if creator exists
            $creator = Creators::findOrFail($creatorId);
            if (!$creator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Creator not found',
                ], 404);
            }

            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $dailyGoals = DailyGoal::where('creator_id', $creatorId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $dailyGoals->items(),
                'pagination' => [
                    'current_page' => $dailyGoals->currentPage(),
                    'per_page' => $dailyGoals->perPage(),
                    'total' => $dailyGoals->total(),
                    'last_page' => $dailyGoals->lastPage(),
                    'from' => $dailyGoals->firstItem(),
                    'to' => $dailyGoals->lastItem(),
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Creator not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve daily goals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new daily goal
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'creator_id' => 'required|exists:creators,id',
                'month' => 'required|string|max:255',
                'shift_goal_date_range' => 'required|string|max:255',
                'shift_goal' => 'required|numeric|min:0',
                'percentage_increase' => 'required|numeric|min:0|max:100',
                'number_of_shifts' => 'required|integer|min:0',
                'daily_goal' => 'required|numeric|min:0',
            ]);

            $dailyGoal = DailyGoal::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Daily goal created successfully',
                'data' => $dailyGoal
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create daily goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing daily goal
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $dailyGoal = DailyGoal::findOrFail($id);

            $validatedData = $request->validate([
                'month' => 'sometimes|required|string|max:255',
                'shift_goal_date_range' => 'sometimes|required|string|max:255',
                'shift_goal' => 'sometimes|required|numeric|min:0',
                'percentage_increase' => 'sometimes|required|numeric|min:0|max:100',
                'number_of_shifts' => 'sometimes|required|integer|min:0',
                'daily_goal' => 'sometimes|required|numeric|min:0',
            ]);

            $dailyGoal->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Daily goal updated successfully',
                'data' => $dailyGoal
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Daily goal not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update daily goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a daily goal
     */
    public function destroy($id): JsonResponse
    {
        try {
            $dailyGoal = DailyGoal::findOrFail($id);
            $dailyGoal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Daily goal deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Daily goal not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete daily goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific daily goal
     */
    public function show($id): JsonResponse
    {
        try {
            $dailyGoal = DailyGoal::with('creator')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $dailyGoal
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Daily goal not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve daily goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
