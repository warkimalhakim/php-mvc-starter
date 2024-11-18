@extends('layouts.guest')

@section('title', 'Daftar Users')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-xl-8">
            <h1>Daftar Users</h1>
            <a class="btn btn-primary my-2" href="<?= route('users.create'); ?>">Tambah User Baru</a>

            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Nama</td>
                        <td>Umur</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                    <tr>
                        <td>
                            <?= $user->id ?>
                        </td>
                        <td>
                            <?= $user->nama ?>
                        </td>
                        <td>
                            <?= $user->umur ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection