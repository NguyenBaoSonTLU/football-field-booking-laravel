<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root{--hero-field:url('{{ asset('images/hero-field.svg') }}');--auth-field:url('{{ asset('images/auth-field.svg') }}')}</style>
    @stack('styles')
</head>
<body>
<div class="admin-shell">
    @include('partials.admin-sidebar')
    <main class="admin-main">
        @include('partials.flash')
        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
