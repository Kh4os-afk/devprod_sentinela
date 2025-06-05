<div class="space-y-6">
    <div>
        <flux:heading size="xl" level="1">Sentinela</flux:heading>
        <flux:subheading size="lg">Auditar {{ ucfirst(mb_strtolower($modulo->modulo)) }}</flux:subheading>
    </div>
    <flux:separator variant="subtle"/>

    @forelse($consultasAgrupadas as $submoduloId => $consultas)
        <!-- Submodule Header -->
        <div class="mb-2">
            <flux:heading size="lg">
                @if($submoduloId === 'null')
                    Sem Submódulo
                @else
                    {{ $submodulos->get($submoduloId)?->submodulo ?? 'Sem Submódulo' }}
                @endif
            </flux:heading>
        </div>

        <flux:card wire:poll.keep-alive.10s class="py-0">
            <!-- Table for this submodule -->
            <flux:table class="table table-hover">
                <flux:table.columns>
                    <flux:table.column align="start" class="min-w-60">Regra</flux:table.column>
                    <flux:table.column align="end">Situação</flux:table.column>
                    <flux:table.column align="end">Ocorrência</flux:table.column>
                    <flux:table.column align="end">Data Atualização</flux:table.column>
                    <flux:table.column align="end">Histórico</flux:table.column>
                    <flux:table.column align="end">Operações</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($consultas as $consulta)
                        <flux:table.row :key="$consulta->id">
                            <flux:table.cell align="start" class="py-0! truncate max-w-60">{{ $consulta->titulo }}</flux:table.cell>
                            <flux:table.cell align="end" class="py-0!">
                                @if($consulta->values and count(json_decode($consulta->values->valor)) > 0 and count(json_decode($consulta->values->valor)) <= 100)
                                    {{--<i style="color: #f8bb86" class="app-menu__icon fa fa-exclamation-triangle fa-2x"></i>--}}
                                    <flux:icon.exclamation-triangle variant="solid" class="text-yellow-500"/>
                                @elseif($consulta->values and count(json_decode($consulta->values->valor)) > 100)
                                    {{--<i style="color: #f27474" class="app-menu__icon fa fa-times fa-2x"></i>--}}
                                    <flux:icon.x-circle variant="solid" class="text-red-500"/>
                                @else
                                    {{--<i style="color: #a5dc86" class="app-menu__icon fa fa-check fa-2x"></i>--}}
                                    <flux:icon.check-circle variant="solid" class="text-green-500"/>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell align="end" class="py-0!">
                                {{ $consulta->values ? count(json_decode($consulta->values->valor)) : 0 }}
                            </flux:table.cell>
                            <flux:table.cell align="end" class="py-0!">
                                {{ $consulta->values ? $consulta->values->updated_at->format('d/m/Y H:i:s') : '' }}
                            </flux:table.cell>
                            <flux:table.cell align="end" class="py-0!">
                                {{--<flux:chart :value="[15, 18, 16, 19, 22, 25, 28, 25, 29, 28, 32, 35,30,17,15,10,5,2,1]" class="w-[7rem] aspect-[4/1]">
                                    <flux:chart.svg gutter="0">
                                        <flux:chart.line class="text-green-500 dark:text-green-400"/>
                                    </flux:chart.svg>
                                </flux:chart>--}}
                                Em Produção
                            </flux:table.cell>
                            <flux:table.cell align="end" class="py-0!">
                                <div>
                                    <flux:button square variant="ghost" icon="document-text" wire:click="relatorio({{ $consulta->id }})"></flux:button>
                                    <flux:button square variant="ghost" :icon="$consulta->runningJob ? 'loading' : 'arrow-path'" wire:click="atualizar({{ $consulta->id }})"></flux:button>
                                    @if(auth()->user()->is_admin)
                                        <flux:button square variant="ghost" icon="pencil" wire:click="editarConsulta({{ $consulta->id }})"></flux:button>
                                        <flux:button square variant="ghost" icon="trash" wire:click="deletarConsulta({{$consulta->id}})"></flux:button>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card>
    @empty
    @endforelse

    <!-- Modals remain the same -->
    <flux:modal name="deletar-consulta" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Deletar Métrica?</flux:heading>
                <flux:text class="mt-2">
                    <p>Você está prestes a excluir esta métrica.</p>
                    <p>Essa ação não poderá ser desfeita.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" x-on:click="$flux.modal('deletar-consulta').close()">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deletar">Deletar métrica</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="editar-consulta" class="min-w-8/12">
        <form wire:submit="editar" class="space-y-6">
            <flux:heading size="lg">Editar Consulta</flux:heading>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <flux:select wire:model="modulo_modal" label="Modulo">
                        @forelse(\App\Models\Module::all() as $module)
                            <flux:select.option value="{{ $module->id }}">{{ $module->modulo }}</flux:select.option>
                        @empty
                            <flux:select.option>Nenhum Valor Disponivel</flux:select.option>
                        @endforelse
                    </flux:select>
                </div>
                <div class="col-span-12">
                    <flux:input wire:model="titulo_modal" label="Titulo"/>
                </div>
                <div class="col-span-6">
                    <flux:select variant="listbox" searchable indicator="checkbox" multiple label="Tempo de Atualização" wire:model="horario_execucao">
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
                <div class="col-span-6">
                    <flux:field>
                        <flux:label>Sub Modulo</flux:label>

                        <flux:button.group>
                            <flux:select wire:model="submodulo">
                                <flux:select.option value=0>Sem Modulo</flux:select.option>
                                @foreach($this->submodulos as $submodulo)
                                    <flux:select.option value="{{ $submodulo->id }}">{{ $submodulo->submodulo }}</flux:select.option>
                                @endforeach
                            </flux:select>

                            <flux:modal.trigger name="criar_submodulo">
                                <flux:button icon="plus" {{--href="{{ route('criar.submodulo',['modulo' => $modulo_modal]) }}" target="_blank"--}}/>
                            </flux:modal.trigger>
                        </flux:button.group>

                        <flux:modal name="criar_submodulo" variant="flyout">
                            <livewire:criar-sub-modulo :modulo="$modulo_modal"/>
                        </flux:modal>

                        <flux:error name="submodulo"/>
                    </flux:field>
                </div>
                <div class="col-span-12">
                    <flux:textarea label="Consulta" placeholder="Consulta" rows="auto" wire:model="consulta_modal"/>
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
</div>