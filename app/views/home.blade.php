@extends('layouts.guest')

@section('title', !empty($title) ? $title : 'Halaman Utama')

@section('content')

SELAMAT DATANG {{ $nama ?? ($title ?? 'GUEST') }}

@endsection