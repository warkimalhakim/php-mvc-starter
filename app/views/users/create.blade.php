@extends('layouts.guest')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ flash('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ flash('error') }}
        </div>
    @endif

    <h2>Tambahkan User</h2>

    <div class="row">
        <div class="col-md-6 col-xl-4">
            <form action="<?= route('users.store') ?>" method="post">
                <div class="form-group mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="umur">Umur</label>
                    <input type="number" name="umur" id="umur" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
@endsection
