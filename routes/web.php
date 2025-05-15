<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);


Route::get('/test', function () {
    return view('test', [
        'titulo' => 'TechConnect - Prueba',
        'mensaje' => 'La aplicación está funcionando correctamente'
    ]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
