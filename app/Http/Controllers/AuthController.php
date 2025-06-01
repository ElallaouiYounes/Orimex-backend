<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\Team;
//use App\Models\User;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\ValidationException;
//use Illuminate\Support\Facades\Log;
//
//class AuthController extends Controller
//{
//    public function login(Request $request)
//    {
//        $request->validate([
//            'email' => 'required|string|email',
//            'password' => 'required',
//        ]);
//
//        $employee = Team::where('email', $request->email)->first();
//
//        if(!$employee || !Hash::check($request->password, $employee->password))
//        {
//            throw ValidationException::withMessages([
//                'email' => __('auth.failed'),
//            ]);
//        }
//
//        $allowedRoles = ['Admin', 'Manager', 'HR', 'Developer', 'Accountant'];
//        if(!in_array($employee->role, $allowedRoles))
//        {
//            throw ValidationException::withMessages([
//                'email' => __('auth.unauthorized'),
//            ]);
//        }
//
//        $user = User::firstOrCreate(
//            ['employee_id' => $employee->id],
//            ['employee_id' => $employee->id]
//        );
//
//        Auth::login($user);
//
//        $token = $user->createToken('orimex-dashboard')->plainTextToken;
//
//        return response()->json([
//            'message' => 'Login successful',
//            'token' => $token,
//            'user' => $user->load('employee') // Eager load employee data
//        ]);
//
//    }
//
//
//    public function logout(Request $request)
//    {
//        try {
//            // Delete token only if the user is authenticated via API token
//            if ($request->user('sanctum')) {
//                $request->user('sanctum')->currentAccessToken()?->delete();
//            }
//
//            // Also log out the user from the session (web guard)
//            Auth::guard('web')->logout();
//            $request->session()->invalidate();
//            $request->session()->regenerateToken();
//
//            return response()->json([
//                'message' => 'Logout successful',
//                'success' => true,
//            ]);
//
//        } catch (\Throwable $e) {
//            Log::error('Logout failed: ' . $e->getMessage());
//
//            return response()->json([
//                'message' => 'Logout failed',
//                'error' => $e->getMessage(),
//                'success' => false,
//            ], 500);
//        }
//    }
//
//}


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
    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find employee by email
        $employee = Team::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$employee || !Hash::check($request->password, $employee->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Restrict roles to allowed list
        $allowedRoles = ['Admin', 'Manager', 'HR', 'Developer', 'Accountant'];
        if (!in_array($employee->role, $allowedRoles)) {
            throw ValidationException::withMessages([
                'email' => __('auth.unauthorized'),
            ]);
        }

        // Create or retrieve corresponding user
        $user = User::firstOrCreate(
            ['employee_id' => $employee->id],
            ['employee_id' => $employee->id]
        );

        // Login user using Sanctum
        Auth::login($user);

        // Create access token
        $token = $user->createToken('orimex-dashboard')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->load('employee'), // Include employee relation
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        try {
            // Revoke the current token if using Sanctum
            if ($request->user('sanctum')) {
                $request->user('sanctum')->currentAccessToken()?->delete();
            }

            // Invalidate session (if using web guard)
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Logout successful',
                'success' => true,
            ]);
        } catch (\Throwable $e) {
            Log::error('Logout failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
