<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReqController;
use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/user',function (Request $request){
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout',[AuthController::class, 'logOut']);

Route::post('/req', [ReqController::class, 'store']); 
Route::put('/req/{id}/finalizar', [ReqController::class, 'finalizar']);