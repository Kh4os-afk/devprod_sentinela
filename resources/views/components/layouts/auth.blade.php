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
    @fluxAppearance
</head>
<body>
{{ $slot }}
@fluxScripts
<flux:toast position="top right"/>
</body>
</html>
