@extends('layouts.guest')

@section('title', 'Daftar Users')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ flash('success') }}
</div>
@elseif(session('error'))
<div class="alert alert-danger">
    {{ flash('error') }}
</div>
@endif

<h1>Daftar Users</h1>
{{-- <a class="btn btn-primary my-2" href="?= route('users.create'); ?>">Tambah User Baru</a> --}}

@component('components.Button', ['url' => route('users.create'), 'target' => '_self', 'class' => 'btn
btn-primary my-2'])
Tambah User Baru
@endcomponent


<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th class="align-middle text-center">ID</th>
            <th class="align-middle text-left">Nama</th>
            <th class="align-middle text-center">Umur</th>
            <th class="align-middle text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
        <tr>
            <td class="align-middle text-center">
                <?= $user->id ?>
            </td>
            <td class="align-middle align-left fw-bold">
                <?= $user->nama ?>
            </td>
            <td class="align-middle text-center">
                <?= $user->umur ?>
            </td>
            <td class="d-flex gap-1">

                @component('components.Button', ['url' => route('/users/'.$user->id.'/edit'), 'class'
                =>
                'btn btn-primary btn-sm'])
                Edit
                @endcomponent
                <form action="{{ route('users/'. $user->id.'') }}" method="post">
                    @method('DELETE')
                    @component('components.Button', ['class' =>
                    'btn btn-danger btn-sm', 'type' => 'submit'])
                    Delete
                    @endcomponent
                </form>

            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

@push('script')
<script>
    console.log('HALO SAYA JAVASCRIPT')
</script>
@endpush

@endsection