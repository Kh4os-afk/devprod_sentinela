<div class="relative flex items-center justify-center w-full h-full">
    <div class="relative w-48 h-48 flex items-center justify-center animate__animated animate__fadeIn animate__faster">
        <!-- Spinner girando ao redor -->
        <div class="absolute inset-0 rounded-full border-4 border-t-transparent animate-spin opacity-75"></div>

        <!-- Tema claro -->
        <img src="{{ asset('imagens/preto.png') }}" class="w-24 opacity-75 block dark:hidden">

        <!-- Tema escuro -->
        <img src="{{ asset('imagens/branco.png') }}" class="w-24 opacity-75 hidden dark:block">
    </div>
</div>