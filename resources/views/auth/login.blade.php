@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }

    // Dynamic customization based on portal
    $portal = $portal ?? null;
    $customTitle = $title ?? __('adminlte::adminlte.login_message');
    $themeColor = $theme ?? 'primary';

    // Convert theme to AdminLTE card class
    $cardClass = 'card-' . $themeColor;
    // Map theme to button class
    $btnClass = 'btn-' . $themeColor;
    if ($themeColor == 'dark') $btnClass = 'btn-dark';
@endphp

@section('auth_header', $customTitle)

@section('auth_body')
    {{-- Override the card class via JS or inline if possible, but adminlte structure is rigid.
         However, we can inject a style or script to manipulate the parent card if needed,
         or just color the button and header text.
         AdminLTE auth-page yields 'auth_body' inside a card-body. The card header is 'login-logo'.
         Actually, adminlte::auth.auth-page structure:
         <div class="login-box">... <div class="card card-outline card-primary"> ...
         It uses `config('adminlte.classes_auth_card', 'card-outline card-primary')`.
         To change this dynamically per view is hard without changing config.
         Workaround: Use JavaScript to swap the class or accept the limit.

         Better: Re-implement the wrapper here to have full control?
         No, let's try to inject a script to change the class based on theme.
    --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var card = document.querySelector('.login-card-body').closest('.card');
            if(card) {
                // Remove default primary
                card.classList.remove('card-primary');
                // Add new theme
                card.classList.add('card-{{ $themeColor }}');
            }
        });
    </script>

    <form action="{{ $loginUrl }}" method="post">
        @csrf

        @if($portal)
            <input type="hidden" name="portal" value="{{ $portal }}">
        @endif

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-{{ $themeColor }}" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block {{ $btnClass }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a href="{{ $passResetUrl }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
        <p class="my-0">
            <a href="{{ $registerUrl }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif

    <p class="mt-3 text-center">
        <a href="{{ route('gateway') }}" class="text-muted small">&larr; Kembali ke Portal Utama</a>
    </p>
@stop
