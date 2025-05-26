@extends('layouts.guest')

@section('title', !empty($title) ? $title : 'Halaman Utama')

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2>Users from API</h2>

                @if ($users)
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">#</th>
                                <th class="align-middle text-center">Photo</th>
                                <th class="align-middle text-left">Full Name</th>
                                <th class="align-middle text-center">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">
                                        @if ($user->image)
                                            <img src="{{ $user->image }}" width="50px"
                                                class="img border rounded-circle" />
                                        @endif
                                    </td>
                                    <td class="align-middle fw-bold">
                                        {{ ($user->firstName ?? '') . ' ' . ($user->lastName ?? '') }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $user->email ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>

@endsection
