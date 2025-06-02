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
                    {{ $submodulos->get($submoduloId)?->submodulo ?? 'Submódulo não encontrado' }}
                @endif
            </flux:heading>
        </div>

        <flux:card wire:poll.keep-alive.10s class="py-0">
            <!-- Table for this submodule -->
            <flux:table class="table table-hover">
                <flux:table.columns>
                    <flux:table.column>Regra</flux:table.column>
                    <flux:table.column class="text-center" align="center">Situação</flux:table.column>
                    <flux:table.column class="text-center">Ocorrência</flux:table.column>
                    <flux:table.column class="text-center">Data Atualização</flux:table.column>
                    <flux:table.column class="text-center">Próxima Atualização</flux:table.column>
                    <flux:table.column class="text-center">Operações</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($consultas as $consulta)
                        <flux:table.row>
                            <flux:table.cell class="py-0!">{{ $consulta->titulo }}</flux:table.cell>
                            <flux:table.cell class="py-0!">
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
                            <flux:table.cell class="py-0! text-center">
                                {{ $consulta->values ? count(json_decode($consulta->values->valor)) : 0 }}
                            </flux:table.cell>
                            <flux:table.cell class="py-0! text-center">
                                {{ $consulta->values ? $consulta->values->updated_at->format('d/m/Y H:i:s') : '' }}
                            </flux:table.cell>
                            @php
                                $lastUpdate = $consulta->values ? $consulta->values->updated_at : null;
                                $nextUpdate = $consulta->values ? $consulta->values->updated_at->copy()->addHours($consulta->atualizacao) : null;
                                $progressPercentage = 0;

                                if ($lastUpdate && $nextUpdate) {
                                    $totalDuration = $lastUpdate->diffInSeconds($nextUpdate);
                                    $elapsedDuration = $lastUpdate->diffInSeconds(Carbon\Carbon::now());
                                    $progressPercentage = ($elapsedDuration / $totalDuration) * 100;
                                    $progressPercentage = min(max($progressPercentage, 0), 100);
                                }
                            @endphp
                            <flux:table.cell class="py-0! align-middle">
                                <div class="progress progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px; background-color: #0d6efd">
                                    <div class="progress-bar" style="width: {{ $progressPercentage }}%;background-color: #e9ecef"></div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="py-0! text-center">
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
                    <flux:input wire:model.live="tabela_modal" label="Tabela"/>
                </div>
                <div class="col-span-6">
                    <flux:select label="Tabelas Existentes">
                        {{--@foreach($sugestoes as $sugestao)
                            <flux:select.option value="{{ $sugestao }}">{{ $sugestao }}</flux:select.option>
                        @endforeach--}}
                    </flux:select>
                </div>
                <div class="col-span-6">
                    <flux:input wire:model="atualizacao_modal" label="Tempo de Atualização em Horas"/>
                </div>
                <div class="col-span-6">
                    <flux:select wire:model="submodulo" label="Sub Modulo">
                        @forelse(\App\Models\SubModulo::where('modulo_id', $modulo_id)->get() as $submodulo)
                            <flux:select.option value="{{ $submodulo->id }}">{{ $submodulo->id }} - {{ $submodulo->submodulo }}</flux:select.option>
                        @empty
                            <flux:select.option value="">Nenhum Valor Disponivel</flux:select.option>
                        @endforelse
                    </flux:select>
                </div>
                <div class="col-span-12">
                    <flux:textarea label="Consulta" placeholder="Consulta" wire:model="consulta_modal"/>
                </div>
                <div class="col-span-12">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"> Editar uma consulta faz com que seus dados sejam atualizados</i>
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