<?php

namespace Warkim\controllers;

use Warkim\helpers\Request;
use Warkim\models\User;

class UserController
{

    public function index()
    {
        $users = User::all();
        return view('users.index');
    }

    public function edit(Request $request, $data,  $a)
    {
        echo '<pre>';
        var_dump($data, $a);
        echo '</pre>';
    }


    public function update(Request $request, $data)
    {
        echo '<pre>';
        var_dump($request->input('nama'));
        var_dump($data);
        echo '</pre>';
    }
}
