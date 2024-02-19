<?php

use App\Http\Controllers\ContaController;
use Illuminate\Support\Facades\Route;


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

/* Route::get('/', function () {
    return view('welcome');
}); */

//Contas
Route::controller(ContaController::class)->group(function(){
    Route::get('/index-conta', 'index')->name('conta.index');
    Route::get('/create-conta', 'create')->name('conta.create');
    Route::post('/store-conta', 'store')->name('conta.store');
    Route::get('/show-conta/{conta}', 'show')->name('conta.show');
    Route::get('/edit-conta/{conta}', 'edit')->name('conta.edit');
    Route::put('/update-conta/{conta}', 'update')->name('conta.update');
    Route::delete('/destroy-conta/{conta}', 'destroy')->name('conta.destroy');
    Route::get('/gerar-pdf-conta', 'gerarPdf')->name('conta.gerar-pdf');
    Route::get('/gerar-csv-conta', 'gerarCsv')->name('conta.gerar-csv');
});