<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Página principal de prueba';
});


Route::get('/test', function () {
    return view('test', [
        'titulo' => 'TechConnect - Prueba',
        'mensaje' => 'La aplicación está funcionando correctamente'
    ]);
});
