<?php
//
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\AuthController;
//use App\Http\Controllers\OrderController;
//use App\Http\Controllers\DeliveryController;
//use App\Http\Controllers\TransactionController;
//use App\Http\Controllers\InventoryController;
//use App\Http\Controllers\LogisticController;
//use App\Http\Controllers\ProductController;
//use App\Http\Controllers\CustomerController;
//use App\Http\Controllers\TeamController;
//use App\Http\Controllers\WarehouseController;
//
///*
//|--------------------------------------------------------------------------
//| API Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register API routes for your application. These
//| routes are loaded by the RouteServiceProvider and all of them will
//| be assigned to the "api" middleware group. Make something great!
//|
//*/
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user()->load('employee');
//});
//
//Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
//
//// Public route
//Route::post('login', [AuthController::class, 'login']);
//
//// Protected routes
//Route::middleware('auth:sanctum')->group(function () {
//
//    Route::apiResource('orders', OrderController::class);
//    Route::apiResource('deliveries', DeliveryController::class);
//    Route::apiResource('transactions', TransactionController::class);
//    Route::apiResource('inventory', InventoryController::class);
//    Route::apiResource('logistics', LogisticController::class);
//    Route::apiResource('products', ProductController::class);
//    Route::apiResource('customers', CustomerController::class);
//    Route::apiResource('team', TeamController::class);
//    Route::apiResource('warehouses', WarehouseController::class);
//});


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\WarehouseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|
*/

// 👤 Public Routes
Route::post('/login', [AuthController::class, 'login']);

// 🔐 Protected Routes (Require Sanctum Auth)
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user info (with employee relationship)
    Route::get('/user', function (Request $request) {
        return $request->user()->load('employee');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Resource routes
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('deliveries', DeliveryController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('inventory', InventoryController::class);
    Route::apiResource('logistics', LogisticController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('team', TeamController::class);
    Route::apiResource('warehouses', WarehouseController::class);
});
