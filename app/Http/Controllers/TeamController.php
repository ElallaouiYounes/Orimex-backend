<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Team::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|unique:team,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team,email',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'password' => 'required|string|min:8', // Password validation
            'status' => 'required|in:working,vacation,retired', // Updated status validation
        ]);

        $team = Team::create([
            ...$validated,
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json($team, 201);
    }

    public function show(string $id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team);
    }

    public function update(Request $request, string $id)
    {
        $team = Team::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:team,email,' . $team->id,
            'phone' => 'sometimes|string|max:20',
            'department' => 'sometimes|string|max:100',
            'role' => 'sometimes|string|max:100',
            'password' => 'sometimes|string|min:8', // Password validation
            'status' => 'sometimes|in:working,vacation,retired', // Updated status validation
        ]);

        // Hash the password if it's being updated
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $team->update($validated);

        return response()->json($team);
    }

    public function destroy(string $id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return response()->json(['message' => 'Employee deleted']);
    }
}
