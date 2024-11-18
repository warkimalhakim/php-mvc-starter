<?php

namespace Warkim\controllers;

use Warkim\models\User;
use Warkim\core\Request;
use Warkim\core\Validator;

class UserController
{

    public function index()
    {
        $users = User::all();
        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('users.create', [
            'user' => User::where('id', '!=', 1)->get(),
        ]);
    }

    public function edit(Request $request, $data,  $a)
    {
        echo '<pre>';
        var_dump($data, $a);
        echo '</pre>';
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => ['nullable'],
            'umur' => ['required']
        ]);

        if ($validasi->fails()) {
            echo "ADA ERROR BRO!";
            return false;
        }

        $save = User::create([
            'nama' => $request->input('nama'),
            'umur' => $request->input('umur')
        ]);

        echo '<pre>';
        var_dump($save);
        echo '</pre>';
        exit;
    }


    public function update(Request $request, $data)
    {
        echo '<pre>';
        var_dump($request->input('nama'));
        var_dump($data);
        echo '</pre>';
    }
}
