<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Str;

class SendWorkspaceInviteController extends Controller
{
    public function sendInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        $email = $request->input('email');
        $workspaceId = $request->input('workspace_id');

        // Generate a unique token and expiration time (4 days)
        $token = Str::random(32);
        $expirationDate = Carbon::now()->addDays(4);

        // Save the invite in the database
        $invite = Invite::create([
            'email' => $email,
            'workspace_id' => $workspaceId,
            'token' => $token,
            'expires_at' => $expirationDate,
        ]);

        // Send invitation email
        $inviteLink = route('validate-invite', ['token' => $token]);

        Mail::to($email)->send(new \App\Mail\WorkspaceInvite($inviteLink, $expirationDate));

        return response()->json(['message' => 'Invitation sent successfully'], 200);
    }

    public function validateInvite($token)
    {
        $invite = Invite::where('token', $token)->first();

        if (!$invite || $invite->expires_at->isPast()) {
            return response()->json(['message' => 'Invalid or expired invite token'], 400);
        }

        return response()->json(['message' => 'Token valid', 'workspace_id' => $invite->workspace_id], 200);
    }
}
