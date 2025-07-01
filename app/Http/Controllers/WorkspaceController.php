<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{


    public function createWorkspace(Request $request)
    {
        //check if the request is coming from the superadmin/verifying on the Frontend is unsafe
        $userObj = $request->input('userObj');
        $userId = $userObj['id'];
        $user = User::find($userId);

        if (!$user || $user->role !== 'superadmin') {
            return response()->json([
                "message" => "Unauthorized! Only superadmins can create workspaces"
            ], 403);
        } else {

            //server side validation
            $request->validate([
                "workspaceName" => "required|string|max:255|unique:workspaces,workspace_name",
                "workspaceDescription" => "required | string | max:255",
            ]);

            $access_token = Str::random(20);

            Workspace::create([
                'workspace_name' => $request->input('workspaceName'),
                'workspace_description' => $request->input('workspaceDescription'),
                'access_token' => $access_token,
                'createdby_id' => $user->id,
                'createdby_name' => $user->name,
            ]);
            // Return a success response

            return response()->json([
                "message" => "Workspace created successfully"
            ], 200);
        }
    }

    public function getWorkspaces()
    {
        $workspaces = Workspace::select('id', 'workspace_name')->get();

        return response()->json($workspaces);
    }

    public function getSpecificWorkspace($id)
    {

        $workspace = Workspace::find($id);
   
        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        return response()->json($workspace);
    }
}
