<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Send a notification to a user
    public function sendNotification(Request $request)
    {
        // Validate the request
        $request->validate([
            'from' => 'required|max:255',
            'to_userid' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'budget_allocated' => 'required|integer',
            'message' => 'required|string',
            'task_id' => 'nullable|exists:tasks,id',
            'workspace_id' => 'nullable|exists:workspaces,id',
            'links' => 'nullable|string',
        ]);

        // Create the notification
        Notification::create([
            'from' => $request->input('from'),
            'to_userid' => $request->input('to_userid'),
            'title' => $request->input('title'),
            'budget_allocated' => $request->input('budget_allocated'),
            'message' => $request->input('message'),
            'task_id' => $request->input('task_id'),
            'workspace_id' => $request->input('workspace_id'),
            'links' => $request->input('links'),
            'is_read' => false, // Default notification status
        ]);

        return response()->json(['message' => 'Notification sent successfully'], 201);
    }

    // Fetch unread notifications for the authenticated user
    public function fetchNotifications($userId)
    {
        $notifications = Notification::where('to_userid', $userId)
            ->orderBy('created_at', 'desc') // Order by latest notifications
            ->get();

        return response()->json($notifications, 200);
    }

    // Mark a notification as read
    public function markNotificationRead(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Notification marked as read'], 200);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }

    // Delete a notification
    public function deleteNotification(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted successfully'], 200);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }
}
