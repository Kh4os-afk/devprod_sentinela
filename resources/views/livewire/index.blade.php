<div class="space-y-6">
    <flux:heading size="xl" level="1">Registro de Usuários</flux:heading>

    <flux:subheading size="lg">Cadastre novos usuários e gerencie suas permissões de acesso.</flux:subheading>

    <flux:separator variant="subtle"/>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6 mr-8 space-y-4">
            <flux:callout icon="chat-bubble-oval-left-ellipsis" color="green" inline>
                <flux:callout.heading>Novidade: Envio automático via WhatsApp</flux:callout.heading>

                <flux:callout.text>
                    Agora o sistema conta com uma nova funcionalidade que permite o envio automático de mensagens via WhatsApp. Programe alertas e notificações para serem enviados diretamente aos usuários de forma prática e eficiente.
                </flux:callout.text>

                <x-slot name="actions" class="@md:h-full m-0!">
                </x-slot>
            </flux:callout>

            @if(!auth()->user()->email || !auth()->user()->fone || !auth()->user()->foto)
                <flux:callout icon="shield-check" color="blue" inline>
                    <flux:callout.heading>Atualize suas informações</flux:callout.heading>

                    <flux:callout.text>
                        Mantenha seu cadastro sempre atualizado. Verifique se seus dados estão corretos, como e-mail, telefone e foto de perfil.
                    </flux:callout.text>

                    <x-slot name="actions" class="@md:h-full m-0!">
                        <flux:button href="/usuario" class="animate__animated animate__headShake animate__infinite animate__slower animate__delay-5s">
                            <flux:icon.bell class="size-4 text-black! dark:text-white!"></flux:icon.bell>
                            Atualizar agora
                        </flux:button>
                    </x-slot>
                </flux:callout>
            @endif

            <flux:card>
                <flux:heading size="lg" class="mb-2">Me Motive!</flux:heading>

                <flux:heading class="mb-1" id="typedtext"></flux:heading>

                <flux:text id="typedauthor"></flux:text>
            </flux:card>

            <flux:card>
                <flux:accordion transition>
                    <flux:accordion.item expanded>
                        <flux:accordion.heading>Como funciona o monitoramento de dados?</flux:accordion.heading>

                        <flux:accordion.content>
                            O sistema monitora automaticamente eventos, movimentações e atualizações nas bases de dados configuradas. Qualquer inconsistência ou alteração crítica é registrada para auditoria e pode gerar notificações.
                        </flux:accordion.content>
                    </flux:accordion.item>

                    <flux:accordion.item>
                        <flux:accordion.heading>Como configurar notificações por WhatsApp ou e-mail?</flux:accordion.heading>

                        <flux:accordion.content>
                            Acesse o menu de
                            <flux:link href="/usuario">Meu Perfil</flux:link>
                            cadastre os contatos desejados. Você poderá definir quais tipos de eventos devem gerar notificações e o canal preferido para cada um.
                        </flux:accordion.content>
                    </flux:accordion.item>

                    <flux:accordion.item>
                        <flux:accordion.heading>Meus dados estão seguros?</flux:accordion.heading>

                        <flux:accordion.content>
                            Sim. Todas as conexões são criptografadas e o acesso é controlado por autenticação segura. Os dados são armazenados conforme as melhores práticas de segurança da informação.
                        </flux:accordion.content>
                    </flux:accordion.item>
                </flux:accordion>
            </flux:card>
        </div>

        <div class="col-span-6 mt-8">
            <img src="{{ asset('imagens/devprod_white.svg') }}" class="block dark:hidden animate__animated animate__backInRight">

            <!-- Tema escuro -->
            <img src="{{ asset('imagens/devprod_black.svg') }}" class="hidden dark:block animate__animated animate__backInRight">
        </div>
    </div>
</div>