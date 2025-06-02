<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') ?? 'Page Title' }}</title>
    <link rel="icon" href="{{ asset('imagens/logo_preta.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet"/>
    @vite('resources/css/app.css')
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <flux:brand href="/" logo="{{ asset('imagens/logo_preta.png') }}" name="{{ config('app.name','Devprod Sentinela') }}" class="px-2 dark:hidden"/>
    <flux:brand href="/" logo="{{ asset('imagens/logo_branco.png') }}" name="{{ config('app.name','Devprod Sentinela') }}" class="px-2 hidden dark:flex"/>

    <livewire:command-search/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="/">Dashboard</flux:navlist.item>

        @forelse(\App\Models\Module::where('extra',0)->get() as $module)
            @if(auth()->user()->userModules->contains('module_id',$module->id))
                <flux:navlist.item icon="{{ $module->icon }}" wire:navigate.hover href="{{ route('estoque.index',['modulo' => $module->modulo]) }}">{{ $module->modulo }}</flux:navlist.item>
            @endif
        @empty
        @endforelse

        <flux:navlist.item icon="chevron-down" wire:navigate.hover href="{{ route('criar.consulta') }}">Criar Consulta</flux:navlist.item>
        <flux:navlist.item icon="chevron-double-down" wire:navigate.hover href="{{ route('criar.submodulo') }}">Cadastrar Sub Modulo</flux:navlist.item>


        {{--<flux:navlist.group expandable heading="Favorites" class="hidden lg:grid">
            <flux:navlist.item href="#">Marketing site</flux:navlist.item>
            <flux:navlist.item href="#">Android app</flux:navlist.item>
            <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
        </flux:navlist.group>--}}
    </flux:navlist>

    <flux:spacer/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="cog-6-tooth" href="/configuracoes">Configurações</flux:navlist.item>
        <flux:navlist.item icon="information-circle" href="#">Ajuda / <i>0.0.1</i></flux:navlist.item>
    </flux:navlist>

    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <div class="flex space-x-1">
            <flux:profile avatar="https://fluxui.dev/img/demo/user.png" name="{{ ucwords(mb_strtolower(Str::words(auth()->user()->name ?? 'Administrador',2,''))) }}"/>

            <flux:separator vertical variant="subtle" class="my-2"/>
            <flux:dropdown x-data align="end">
                <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
                    <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini" class="text-zinc-500 dark:text-white"/>
                    <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini" class="text-zinc-500 dark:text-white"/>
                    <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini"/>
                    <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini"/>
                </flux:button>

                <flux:menu>
                    <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Claro</flux:menu.item>
                    <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Escuro</flux:menu.item>
                    <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">Automático</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio checked>{{ ucwords(mb_strtolower(Str::words(auth()->user()->name ?? 'Administrador',2,''))) }}</flux:menu.radio>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout">Sair</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:spacer/>

    <flux:dropdown position="top" alignt="start">
        <flux:profile avatar="https://fluxui.dev/img/demo/user.png"/>

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio checked>{{ ucwords(mb_strtolower(Str::words(auth()->user()->name ?? 'Administrador',2,''))) }}</flux:menu.radio>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout">Sair</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>

@fluxScripts
<flux:toast position="top right"/>
</body>
</html>
