@extends('layouts.frontend')


@section('content')

  @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('send.notification') }}" method="POST">
        @csrf
        <button type="submit">Kirim Notifikasi Email</button>
    </form>

@endsection 

