<div class="flex min-h-screen">
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center opacity-50">
                <a href="/" class="group flex items-center gap-3">
                    <div>
                        <img class="h-5" src="{{ asset('imagens/branco.png') }}">
                    </div>

                    <span class="text-xl font-semibold text-zinc-800 dark:text-white">Devprod Sentinela</span>
                </a>
            </div>

            <flux:heading class="text-center" size="xl">Login</flux:heading>

            <flux:separator/>

            <form wire:submit="login" class="flex flex-col gap-6">
                <flux:field>
                    <div class="mb-3 flex justify-between">
                        <flux:label>Email</flux:label>

                    </div>

                    <flux:input type="email" placeholder="Seu email" wire:model="email"/>
                </flux:field>

                <flux:field>
                    <div class="mb-3 flex justify-between">
                        <flux:label>Senha</flux:label>

                        <flux:link href="#" variant="subtle" class="text-sm">Esqueceu a senha?</flux:link>
                    </div>

                    <flux:input type="password" placeholder="Sua senha" wire:model="password" viewable/>
                </flux:field>

                <flux:button variant="primary" class="w-full" type="submit">Entrar</flux:button>
            </form>

            <flux:subheading class="text-center">
                Primeira vez por aqui? <flux:link href="#">Cadastre-se</flux:link>
            </flux:subheading>
        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden">
        <div class="text-white relative rounded-lg h-full w-full bg-zinc-900 flex flex-col items-start justify-end p-16" style="background-image: url('{{ asset('imagens/auth_aurora_2x.png') }}'); background-size: cover">
            <div class="flex gap-2 mb-4">
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
            </div>

            <div class="mb-6 italic font-base text-3xl xl:text-4xl">
                Projetar, construir e entregar mais rápido do que nunca.
            </div>

            <div class="flex gap-4">
                <flux:avatar src="{{ asset('imagens/logo_branco.png') }}" class="size-12" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">Devprod Solutions</div>
                    <div class="text-zinc-300">ti@barataodacarne.com.br</div>
                </div>
            </div>
        </div>
    </div>
</div>