<!doctype html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'FraxionFX') }} · Admin</title>

    @php
        $favicon = \App\Models\Setting::get('favicon');
        $primary = \App\Models\Setting::get('primary_color', '#7c3aed');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <style>:root { --admin-primary: {{ $primary }}; }</style>

    @routes
    @viteReactRefresh
    @vite(['resources/css/admin.css', 'resources/js/admin/app.tsx'])
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>
