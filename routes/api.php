<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tienda/checkresponse/{orden}', [TiendaController::class, 'checkresponse'])->name('tienda.checkresponse');

Route::get('/tienda/reintentarpago/{idorden}', [TiendaController::class, 'reintentarpago'])->name('tienda.reintentarpago');

Route::get('/tienda/reportarpago/{idorden}', [TiendaController::class, 'reportarpago'])->name('tienda.reportarpago');