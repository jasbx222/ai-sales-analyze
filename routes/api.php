<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    
    // Dashboard Stats
    Route::get('/dashboard/stats', [\App\Http\Controllers\DashboardController::class, 'stats']);
    
    // Clients
    Route::apiResource('clients', \App\Http\Controllers\ClientController::class)->only(['index', 'store', 'show']);
    
    // Interactions (History)
    Route::apiResource('interactions', \App\Http\Controllers\InteractionController::class)->only(['index', 'show']);
    
    // Sales Analysis Endpoint
    Route::post('/sales/analyze', [SalesController::class, 'analyze']);
});
