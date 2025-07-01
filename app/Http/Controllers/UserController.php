<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class UserController
{
    public function fetchAdmins()
    {
        // Fetch admin details
        $admin = User::select('id', 'role', 'name')->where('role', 'admin')->get();

        return response()->json($admin);
    }

    public function fetchUsers(Request $request)
    {
        $currentUser = $request->user(); // Get the currently authenticated user

        if (!$currentUser || $currentUser->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::select('id', 'role', 'email', 'name')
            ->where('role', 'admin')
            ->get();

        return response()->json($users);
    }

    public function checkuser(Request $request)
    {
        $user = $request->user(); // Get the logged-in user

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->fullname,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }



    public function deleteUser(Request $request, $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function fetchEmployees()
    {

        $employee = Employee::select('id', 'profile_picture', 'full_name', 'email', 'country', 'designation', 'department', 'gender', 'employment_type', 'work_anniversary')->get();
        return response()->json($employee);
    }

    public function addEmployee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'gender' => 'required|in:Male,Female,Other',
            'country' => 'required|string|max:255',
            'employment_type' => 'required|in:Full-Time,Part-Time,Freelancer,Intern',
            'work_anniversary' => 'required|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employee_id',
            'profile_picture',
            'full_name',
            'email',
            'country',
            'designation',
            'department',
            'gender',
            'work_anniversary',
            'date_of_birth',
            'supervisor',
            'about',
            'employment_type',
            'passport_id'

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        } else {
            $imagePath = null;
        }

        try {
            $employee = Employee::create([
                'full_name' => $request->name,
                'department' => $request->department,
                'designation' => $request->designation,
                'email' => $request->email,
                'gender' => $request->gender,
                'country' => $request->country,
                'employment_type' => $request->employment_type,
                'work_anniversary' => $request->work_anniversary,
                'profile_picture' => $imagePath,
            ]);

            return response()->json([
                'message' => 'Employee added successfully',
                'employee' => $employee
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add employee. Please try again.'
            ], 500);
        }
    }

    public function updateEmployee(Request $request, $id)
    {

        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'country' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'employment_type' => 'required|in:Full-Time,Part-Time,Freelancer,Intern',
            'work_anniversary' => 'required|date',
            // Add other fields as needed
        ]);


        $employee->update($validatedData);
        return response()->json($employee);
    }

    public function deleteEmployee(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request->password, $employee->password)) {
            return response()->json(['message' => 'Incorrect password'], 403);
        }

        $employee->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
