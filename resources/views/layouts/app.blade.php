<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="Website đặt lịch sân bóng đá 7 người trực tuyến.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root{--hero-field:url('{{ asset('images/hero-field.svg') }}');--auth-field:url('{{ asset('images/auth-field.svg') }}')}</style>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    @include('partials.flash')

    <main>@yield('content')</main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
