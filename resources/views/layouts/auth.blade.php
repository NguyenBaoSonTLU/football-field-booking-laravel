<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root{--hero-field:url('{{ asset('images/hero-field.svg') }}');--auth-field:url('{{ asset('images/auth-field.svg') }}')}</style>
</head>
<body class="auth-page">
    @yield('content')
</body>
</html>
