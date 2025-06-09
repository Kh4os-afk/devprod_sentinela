<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('imagens/logo_preta.png') }}">
    <title>{{ config('app.name') ?? 'Page Title' }}</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="{{ asset('assets/animate_star.css') }}"/>
    @fluxAppearance
</head>
<body>
{{ $slot }}
@fluxScripts
<script src="{{ asset('assets/typewriter.js') }}"></script>
<flux:toast position="top right"/>
</body>
</html>
