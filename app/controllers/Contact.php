<?php

namespace Warkim\controllers;

use Warkim\models\User;

class Contact
{
    public function index()
    {

        $app_name = config('APP_NAME');
        $users = User::all();


        return view('contact.contact', [
            'app_name' => $app_name,
            'users' => $users
        ]);
    }
}
