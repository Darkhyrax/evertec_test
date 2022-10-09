<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TiendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('tienda.index');
});


Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');

Route::get('/tienda/nueva_orden', [TiendaController::class, 'create'])->name('tienda.create');

Route::post('/tienda/store', [TiendaController::class, 'store'])->name('tienda.store');

Route::post('/tienda/validar_cliente', [TiendaController::class, 'validar_cliente'])->name('tienda.validar_cliente');