<?php

use Warkim\models\User;
use Warkim\helpers\Route;
use Warkim\controllers\UserController;

Route::get('/', function () {
    echo "Selamat datang di Homepage";
});

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/update/{id}', [UserController::class, 'update']);
Route::get('/users/{id}', function ($request, $id) {
    return view('users.show', ['user' => User::get($id)]);
});

Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::get('/users/{id}/{a}/edit', [UserController::class, 'edit']);
