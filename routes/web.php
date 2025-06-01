<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/update-dev-password', function() {
//    // Security checks
//    if (!app()->environment('local')) {
//        abort(403, 'This operation is only allowed in local development');
//    }
//
//    $email = 'm.elamrani@orimex.ma';
//    $newPassword = 'Amrani012';
//
//    // Find the user
//    $user = \App\Models\Team::where('email', $email)->first();
//
//    if (!$user) {
//        return response()->json([
//            'success' => false,
//            'message' => 'User not found'
//        ], 404);
//    }
//
//    // Update the password
//    $user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
//    $user->save();
//
//    return response()->json([
//        'success' => true,
//        'message' => 'Password updated successfully',
//        'user' => [
//            'email' => $user->email,
//            'name' => $user->name
//        ]
//    ]);
//});
