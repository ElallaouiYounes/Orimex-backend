<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        $employee = Team::where('email', $request->email)->first();

        if(!$employee || !Hash::check($request->password, $employee->password))
        {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $allowedRoles = ['Admin', 'Manager', 'HR', 'Developer', 'Accountant'];
        if(!in_array($employee->role, $allowedRoles))
        {
            throw ValidationException::withMessages([
                'email' => __('auth.unauthorized'),
            ]);
        }

        $user = User::firstOrCreate(
            ['employee_id' => $employee->id],
            ['employee_id' => $employee->id]
        );

        Auth::login($user);

        $token = $user->createToken('orimex-dashboard')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->load('employee') // Eager load employee data
        ]);
//        $credentials = $request->only('email', 'password');
//
//        if(Auth::attempt($credentials)){
//            $user = Auth::user();
//            $token = $user->createToken('orimex-dashboard')->plainTextToken;
//
//            return response()->json([
//                'message' => 'Login successful',
//                'token' => $token,
//                'user' => $user
//            ]);
//        }

//        return response()->json(['message' => 'Invalid credentials'], 401);
    }

//    public function logout(Request $request)
//    {
//        Auth::guard('web')->logout();
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
//
//        return response()->json(['message' => 'Logout successful']);
//    }
    public function logout(Request $request)
    {
        try {
            // Check if user is authenticated via API
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }

            // Handle web logout
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Logout successful',
                'success' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
