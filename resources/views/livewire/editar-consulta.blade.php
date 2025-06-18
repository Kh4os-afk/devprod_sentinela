<span>
     <flux:modal.trigger :name="'editar-consulta-'.$query->id">
        <flux:button square variant="ghost" icon="pencil"></flux:button>
    </flux:modal.trigger>

    <flux:modal :name="'editar-consulta-'.$query->id" class="min-w-8/12 text-left" :dismissible="false">
        <form wire:submit="editar" class="space-y-6">
            <flux:heading size="lg">Editar Consulta</flux:heading>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <flux:select wire:model="modulo" label="Modulo">
                        @forelse(\App\Models\Module::all() as $module)
                            <flux:select.option value="{{ $module->id }}">{{ $module->modulo }}</flux:select.option>
                        @empty
                            <flux:select.option>Nenhum Valor Disponivel</flux:select.option>
                        @endforelse
                    </flux:select>
                </div>
                <div class="col-span-6">
                    <flux:input wire:model="titulo" label="Titulo"/>
                </div>
                <div class="col-span-6">
                    <flux:select variant="listbox" searchable indicator="checkbox" multiple label="Horário de Envio" wire:model="horario_execucao">
                        @forelse(\App\Models\Horario::all() as $horario)
                            <flux:select.option>
                                <div class="flex items-center gap-2">
                                    <flux:icon.clock variant="mini" class="text-zinc-400"/>
                                    {{ $horario->horario }}
                                </div>
                            </flux:select.option>
                        @empty
                            <flux:select.option value="00:00">
                                <div class="flex items-center gap-2">
                                    <flux:icon.x-circle variant="mini" class="text-zinc-400"/>
                                    Sem Horários Disponíveis
                                </div>
                            </flux:select.option>
                        @endforelse
                    </flux:select>
                </div>
                <div class="col-span-12">
                    <flux:textarea label="Whatsapp Prompt" placeholder="Whatsapp Prompt" rows="auto" wire:model="whatsapp_prompt"/>
                </div>
                <div class="col-span-12">
                    <flux:textarea label="Consulta" placeholder="Consulta" rows="auto" wire:model="consulta"/>
                </div>
                <div class="col-span-12">
                    <flux:callout color="emerald">
                        <flux:callout.heading icon="exclamation-triangle">Atenção</flux:callout.heading>

                        <flux:callout.text>Ao editar uma consulta, seus dados são atualizados.</flux:callout.text>
                    </flux:callout>
                </div>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button type="submit">Salvar</flux:button>
                    <flux:button variant="ghost" x-on:click="$flux.modal('editar-consulta').close()">Cancelar</flux:button>
                </flux:modal.close>
            </div>
        </form>
    </flux:modal>
</span>