<div>
    <flux:heading size="xl" level="1">Sentinela</flux:heading>

    <flux:subheading size="lg" class="mb-6">Monitore, audite e organize os processos da sua empresa com eficiência.</flux:subheading>

    <flux:separator variant="subtle"/>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6 mt-8 space-y-8">
            <flux:callout icon="shield-check" color="blue" inline>
                <flux:callout.heading>Atualize suas informações</flux:callout.heading>

                <flux:callout.text>
                    Mantenha seu cadastro sempre atualizado. Verifique se seus dados estão corretos, como e-mail, telefone e foto de perfil.
                </flux:callout.text>

                <x-slot name="actions" class="@md:h-full m-0!">
                    <flux:button href="/usuario" class="animate__animated animate__headShake animate__infinite animate__slower animate__delay-5s">
                        <flux:icon.bell class="size-4 text-black!"></flux:icon.bell>
                        Atualizar agora
                    </flux:button>
                </x-slot>
            </flux:callout>

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

            {{--<flux:card class="hidden 2xl:block">
                <flux:heading level="1" size="lg" class="pb-4">Acessos Diarios</flux:heading>
                <flux:chart wire:model="data" class="aspect-3/1">
                    <flux:chart.svg>
                        <flux:chart.line field="visitors" class="text-pink-500 dark:text-pink-400" />

                        <flux:chart.axis axis="x" field="date">
                            <flux:chart.axis.line />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.cursor />
                    </flux:chart.svg>

                    <flux:chart.tooltip>
                        <flux:chart.tooltip.heading field="date" :format="['year' => 'numeric', 'month' => 'numeric', 'day' => 'numeric']" />
                        <flux:chart.tooltip.value field="visitors" label="Visitors" />
                    </flux:chart.tooltip>
                </flux:chart>
            </flux:card>--}}
        </div>
        <div class="col-span-6 flex mt-4">
            <img src="{{ asset('imagens/devprod_white.svg') }}" class="block dark:hidden animate__animated animate__backInRight">

            <!-- Tema escuro -->
            <img src="{{ asset('imagens/devprod_black.svg') }}" class="hidden dark:block animate__animated animate__backInRight">
        </div>
    </div>
</div>