<?php

use Warkim\core\Route;
use Warkim\controllers\UserController;

Route::get('/', function () {
    $html = '<h1>Selamat datang di Homepage</h1>';
    $html .= '<p>Silahkan gunakan route untuk mengelola uris</p>';
    echo $html;
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users/store', [UserController::class, 'store']);

// Route::get('/users', [UserController::class, 'index']);
// Route::post('/users/update/{id}', [UserController::class, 'update']);
// Route::get('/users/{id}', function ($request, $id) {
    //     return view('users.show', ['user' => User::get($id)]);
    // });
    
    // Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    // Route::get('/users/{id}/{a}/edit', [UserController::class, 'edit']);
    
    // Route::get('/contact', function ($kontak) {
    //     return print_r($kontak);
    // });
