<?php

use Warkim\controllers\Contact;
use Warkim\helpers\Route;
use Warkim\models\User;

Route::get('/', function () {
    echo "Selamat datang di Homepage";
});

Route::get('/users/index', function () {
    $users = User::all();
    return view('users.index', ['users' => $users]);
});

Route::get('/kontak', [Contact::class, 'index']);
