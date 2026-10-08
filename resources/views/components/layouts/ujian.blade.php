<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ujian Sedang Berlangsung' }} · Kuiz Digital</title>
    <link rel="icon" href="/favicon.ico?v=2" sizes="any">
    <link rel="icon" href="/favicon.png?v=2" type="image/png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">
    <link rel="stylesheet" href="{{ asset('fonts/filament/filament/inter/index.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    {{ $slot }}
    @include('partials.sesi-berakhir')
    @livewireScripts
</body>
</html>
