<?php

namespace Warkim\controllers;

use Warkim\models\User;
use Warkim\core\Request;
use Warkim\core\Session;
use Warkim\core\Validator;
use Warkim\core\BaseController;

class UserController extends BaseController
{

    public function index()
    {
        $users = User::all('DESC');

        return view('users.index', [
            'title' => 'Daftar Users',
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('users.create', [
            'user' => User::all(),
        ]);
    }

    public function show(Request $request, $id)
    {
        return view('users.show', [
            'user' => User::find($id),
        ]);
    }



    public function edit(Request $request, $id)
    {
        // DIBATASI/TIDAK BOLEH MELAKUKAN QUERY DI CONTROLLER
        // Menggunakan method inheritance
        // $user = User::find($id);
        // Method yang dibuat sendiri di model User
        $user = User::customQuery($id);

        return view('users.edit', [
            'title' => 'Edit User',
            'user'  => $user
        ]);
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => ['required'],
            'umur' => ['required']
        ]);

        if ($validasi->fails()) {
            session()->put('error', 'Gagal ditambahkan');
            return redirect()->back();
        }

        $save = User::create([
            'nama' => $request->input('nama'),
            'umur' => $request->input('umur')
        ]);

        ($save) ?
            session()->put('success', 'Berhasil ditambahkan') :
            session()->put('error', 'Gagal ditambahkan');

        return redirect(route('users.index'));
    }


    public function update(Request $request, $user_id)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => ['required',],
            'umur' => ['required']
        ]);

        if ($validasi->fails()) {

            $errors = $validasi->errors();

            // Pesan Umur
            if (in_array('umur', array_keys($errors))) {
                Session::put('error', $errors['umur'][0]);
            } else {
                Session::put('error', 'Gagal diupdate');
            }

            return redirect()->back(); //->back() sertakan back() untuk me-redirect;
        }

        $user = User::find($user_id);

        if (!$user) {
            Session::put('error', 'USER TIDAK DITEMUKAN');
            return redirect()->back();
        };

        $update = User::update([
            'nama' => $request->input('nama'),
            'umur' => $request->input('umur')
        ], $user->id);

        if ($update) {
            return redirect()->with('success', 'BERHASIL DIUPDATE');
        } else {
            return redirect()->with('success', 'GAGAL DIUPDATE');
        }
    }

    public function destroy(Request $request, $user_id)
    {
        $delete = User::delete($user_id);

        ($delete) ? Session::put('success', 'Berhasil dihapus') : Session::put('error', 'Gagal dihapus');
        return redirect()->back();
    }
}
