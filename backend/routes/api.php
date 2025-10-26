<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReqController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/reg', [AuthController::class, 'Reg']);


Route::middleware('auth:sanctum')->get('/user',function (Request $request){
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
   Route::post('/req', [ReqController::class, 'store']); 
Route::put('/req/{id}/finalizar', [ReqController::class, 'finalizar']);
Route::get('/req/activas', [ReqController::class, 'getRequisicionesActivas']);





// Rutas especiales para admin y tecnicos
Route::middleware('role:admin,tecnico')->group(function () {
        Route::get('/req/historial', [ReqController::class, 'getRequisicionesCerradas']);
        Route::get('/notificaciones', [NotificationController::class, 'index']);
        Route::put('/notificaciones/{id}/leida', [NotificationController::class, 'marcarLeida']);
    });

    // Rutas especiales para admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [AuthController::class, 'obtenerUsuarios']);
        Route::put('/usuarios/{id}/rol', [AuthController::class, 'updateRole']);
        Route::put('/requis/{id}/prioridad', [ReqController::class, 'cambiarPrioridad']);
        
    });
});