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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team,email',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:100',
            'password' => 'required|string|min:8', // Password validation
            'status' => 'required|in:working,vacation,retired', // Updated status validation
        ]);

        $last = Team::latest('created_at')->first();
        $lastId = $last ? (int)substr($last->id, 5) : 0;
        $newId = 'TEAM-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $team = Team::create([
            'id' => $newId,
            'password' => Hash::make($validated['password']), // Hash the password before saving
            ...$validated
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
            'position' => 'sometimes|string|max:100',
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
