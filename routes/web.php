<?php

if (!isset($_SESSION)) session_start();

use Warkim\core\Route;
use Warkim\core\Request;
use Warkim\controllers\ApiController;
use Warkim\controllers\UserController;

Route::get('/home/{title}', fn(Request $request, $title) => view('home', ['title' => $title]));
Route::get('/home2/{nama}', function (Request $request, $nama) {
    return view('home', [
        'title' => $nama
    ]);
});

Route::get('/', function () {
    return view('home', [
        'title' => 'Halaman Utama'
    ]);
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/index', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::post('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/api', [ApiController::class, 'get']);
