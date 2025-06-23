<div>
    <flux:heading size="xl">Meu Perfil</flux:heading>

    <flux:separator variant="subtle" class="my-8"/>

    <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
        <div class="w-80">
            <flux:heading size="lg">Perfil</flux:heading>
            <flux:subheading>É assim que os outros te verão no site.</flux:subheading>
        </div>

        <form wire:submit="salvar" class="flex-1 space-y-6">
                <flux:fieldset class="grid grid-cols-12 gap-4">
                    <div class="col-span-8">
                        <flux:input
                                wire:model="nome"
                                label="Nome de usuário"
                                placeholder="Francisco"
                        />
                    </div>

                    <div class="col-span-4">
                        <flux:input
                                wire:model="foto"
                                label="Foto de usuário"
                                type="file"
                        />
                    </div>
                </flux:fieldset>

            <flux:input
                    wire:model="email"
                    label="Email principal"
                    placeholder="francisco@gmail.com"
                    type="email"
            />

            <flux:input
                    wire:model="fone"
                    label="Celular"
                    placeholder="(92) 99999-9999"
                    type="text"
                    mask="(99) 99999-9999"
            />

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">Salvar perfil</flux:button>
            </div>
        </form>
    </div>

    <flux:separator variant="subtle" class="my-8"/>

    <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
        <div class="w-80">
            <flux:heading size="lg">Preferências</flux:heading>
            <flux:subheading>Personalize seu layout e preferências de notificação.</flux:subheading>
        </div>

        <div class="flex-1 space-y-6">
            <flux:fieldset>
                <flux:legend>Módulos</flux:legend>

                <flux:description>Escolha os módulos que você é responsável.</flux:description>

                <flux:checkbox.group wire:model="usuarioModulos" class="flex gap-4 *:gap-x-2">
                    @forelse($this->modulos as $modulo)
                        <flux:checkbox value="{{ $modulo->id }}" :label="$modulo->modulo" :disabled="!$usuarioPermissao->contains($modulo->id)"/>
                    @empty
                    @endforelse
                </flux:checkbox.group>
            </flux:fieldset>

            <flux:separator variant="subtle" class="my-8"/>

            <flux:radio.group label="Notifique-me sobre..." class="max-sm:flex-col" wire:model="notificacao">
                <flux:radio value="1" label="Todas as novas mensagens"/>
                <flux:radio value="0" label="Nada"/>
            </flux:radio.group>

            <div class="flex justify-end">
                <flux:button type="button" variant="primary" wire:click="salvarPreferencias">Salvar preferências</flux:button>
            </div>
        </div>
    </div>

    <flux:separator variant="subtle" class="my-8"/>

    {{--<div class="flex flex-col lg:flex-row gap-4 lg:gap-6 pb-10">
        <div class="w-80">
            <flux:heading size="lg">Notificações por email</flux:heading>
            <flux:subheading>Escolha quais emails você gostaria de receber de nós.</flux:subheading>
        </div>

        <div class="flex-1 space-y-6">
            <flux:fieldset class="space-y-4">
                <flux:switch checked label="Emails de comunicação" description="Receba emails sobre a atividade da sua conta."/>

                <flux:separator variant="subtle"/>

                <flux:switch checked label="Emails de marketing" description="Receba emails sobre novos produtos, recursos e mais."/>

                <flux:separator variant="subtle"/>

                <flux:switch label="Emails sociais" description="Receba emails sobre solicitações de amizade, seguidores e mais."/>

                <flux:separator variant="subtle"/>

                <flux:switch label="Emails de segurança" description="Receba emails sobre a atividade e segurança da sua conta."/>
            </flux:fieldset>
        </div>
    </div>--}}
</div>
