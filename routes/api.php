<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CreatorsController;
use App\Http\Controllers\DashboardDataController;
use App\Http\Controllers\DailyGoalController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Superadmin-exclusive routes (Nuclear options)
Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {
    Route::post('/system/flush-cache', [SystemController::class, 'flushCache']);
    Route::post('/users/promote-to-admin', [UserController::class, 'promoteToAdmin']);
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/validate-invite/{token}', [WorkspaceInviteController::class, 'validateInvite']);

Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardDataController::class, 'getDashboardData']);
    // Agent Workspaces
    Route::apiResource('workspaces', WorkspaceController::class)->except(['create', 'edit']);
    Route::get('/workspaces/{workspace}/tasks', [TaskController::class, 'getTasksByWorkspace']);
    Route::post('/workspaces/{workspace}/invite', [WorkspaceInviteController::class, 'sendInvite']);
    // Assign Manage Tasks
    Route::apiResource('tasks', TaskController::class)->except(['create', 'edit']);
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'sendNotification']);
    // Agency Users
    Route::get('/user',  [UserController::class, 'fetchuser']);
    Route::get('/checkuser', [UserController::class, 'checkuser']);
    Route::get('/users/admins', [UserController::class, 'fetchAdmins']);
    Route::get('/users/regular', [UserController::class, 'fetchUsers']);
    // Creators
    Route::get('/creators', [CreatorsController::class, 'fetchAllCreators']);
    Route::post('/creators', [CreatorsController::class, 'AddCreator']);
    Route::get('/creators/{id}', [CreatorsController::class, 'show']);
    Route::get('/creators/{id}/earnings', [CreatorsController::class, 'showWithEarnings']);
    Route::put('/creators/{id}', [CreatorsController::class, 'update']);
    Route::delete('/creators/{id}', [CreatorsController::class, 'destroy']);
    // Creators spenders
    Route::get('/creators/{creatorId}/top-spenders', [CreatorsController::class, 'fetchCreatorsTopSpenders']);
    // Creator transactions
    Route::get('/transactions/{creatorId}', [TransactionsController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionsController::class, 'show']);
    Route::post('/transactions', [TransactionsController::class, 'store']);
    Route::put('/transactions/{id}', [TransactionsController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionsController::class, 'destroy']);
    // Daily goals - get goals for a specific creator
    Route::get('/creators/{creatorId}/daily-goals', [DailyGoalController::class, 'getCreatorDailyGoals']);
    // CRUD operations for daily goals
    Route::post('/daily-goals', [DailyGoalController::class, 'store']);
    Route::get('/daily-goals/{id}', [DailyGoalController::class, 'show']);
    Route::put('/daily-goals/{id}', [DailyGoalController::class, 'update']);
    Route::delete('/daily-goals/{id}', [DailyGoalController::class, 'destroy']);
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});


// Verification link on their emails
Route::get('/email/verify', function (Request $request) {
    $request->validate([
        'id' => 'required|integer',
        'hash' => 'required|string',
    ]);

    $user = User::findOrFail($request->id);

    if (!hash_equals($request->hash, sha1($user->email))) {
        abort(403, 'Invalid verification link');
    }

    if (!URL::hasValidSignature($request)) {
        abort(403, 'Invalid or expired verification link');
    }

    $verified = false;
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        $verified = true;
    }

    return redirect(config('app.frontend_url') . '/email-verified?verified=' . ($verified ? 'true' : 'already'));
})->middleware('signed')->name('verification.verify');

Route::get('/php-version', function () {
    return response()->json([
        'app_version' => env('APP_VERSION'),
        'php_version' => phpversion(),
    ]);
});

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'clear';
});
