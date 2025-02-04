<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//SEGURIDAD
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);

    //PRODUCTOS
    Route::middleware('role:Vendedor,Administrador')->prefix('products')->group(function () {
        Route::get('/', [ProductsController::class, 'index']);
        Route::post('crear', [ProductsController::class, 'store']);
        Route::put('actualizar/{id}', [ProductsController::class, 'update']);
        Route::get('buscar/{id}', [ProductsController::class, 'show']);
    });
    Route::middleware('role:Administrador')->prefix('products')->group(function () {
        Route::delete('borrar/{id}', [ProductsController::class, 'destroy']);
    });

    //VENTAS
    Route::middleware('role:Vendedor,Administrador')->prefix('ventas')->group(function () {
        Route::get('/', [SalesController::class, 'index']);
        Route::post('/crear', [SalesController::class, 'store']);
        Route::get('/{id}', [SalesController::class, 'show']);
        Route::get('/{id}/detalle', [SalesController::class, 'detail']);
    });
    

    //CLIENTES
    Route::middleware('role:Vendedor,Administrador')->prefix('clients')->group(function () {
        Route::get('/', [ClientsController::class, 'index']);
        Route::post('crear', [ClientsController::class, 'store']);
        Route::put('actualizar/{id}', [ClientsController::class, 'update']);
        Route::get('buscar/{id}', [ClientsController::class, 'show']);
    });

        Route::middleware('role:Administrador')->prefix('clients')->group(function () {
        Route::delete('borrar/{id}', [ClientsController::class, 'destroy']);
    });

    //REPORTES
    Route::middleware(('role:Administrador'))->prefix('reportes')->group(function () {
        Route::get('product-top', [ReportController::class, 'getTopSellingProducts']);
        Route::get('sales-report', [ReportController::class, 'getTopSellingProducts']);
    });

});
