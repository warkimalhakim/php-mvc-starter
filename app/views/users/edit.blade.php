@extends('layouts.guest')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ flash('success') }}</div>
@elseif(session('error'))
<div class="alert alert-danger">{{ flash('error') }}</div>
@endif

<h2>Edit User</h2>

<form class="form" action="<?= route('users', $user->id); ?>" method="post">
    <div class="form-group mb-3">
        <label for="nama">Name</label>
        <input type="text" name="nama" id="nama" class="form-control" value="{{ $user->nama }}">
    </div>
    <div class="form-group mb-3">
        <label for="umur">Umur</label>
        <input type="number" name="umur" id="umur" class="form-control" value="{{ $user->umur }}">
    </div>
    <button type="submit" class="btn btn-success my-3">Update</button>
</form>



@endsection