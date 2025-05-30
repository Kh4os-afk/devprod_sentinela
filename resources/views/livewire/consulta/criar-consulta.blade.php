<div class="space-y-6">
    <div>
        <flux:heading size="xl" level="1">Criar Consulta</flux:heading>

        <flux:subheading size="lg">Criar Consulta</flux:subheading>
    </div>
    <flux:separator variant="subtle"/>
    <flux:card>
        <form wire:submit="submit" class="grid grid-cols-12 gap-4">
            <div class="col-span-12">
                <flux:select wire:model="modulo" label="Modulo">
                    @forelse(\App\Models\Module::all() as $module)
                        <flux:select.option value="{{ $module->id }}">{{ $module->modulo }}</flux:select.option>
                    @empty
                        <option value="null">Nenhum Valor Disponivel</option>
                    @endforelse
                </flux:select>
            </div>

            <div class="col-span-12">
                <flux:input label="Titulo" wire:model="titulo"/>
            </div>

            <div class="col-span-6">
                <flux:input label="Tabela" wire:model.live="tabela" @focus="atualizarSugestoes"/>
            </div>

            <div class="col-span-6">
                <flux:select wire:model="tabela" label="Tabelas Existentes">
                    @foreach($sugestoes as $sugestao)
                        <option value="{{ $sugestao }}">{{ $sugestao }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div class="col-span-12">
                <flux:input label="Tempo de Atualização em Horas" wire:model.live="atualizacao"/>
            </div>

            <div class="col-span-12">
                <flux:textarea label="Consulta" placeholder="select matricula,nome,cpf,rg from dual;" wire:model="consulta"/>
            </div>

            <div class="col-span-12 text-danger">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"> Atenção os campos do select devem ser especificados</i>
            </div>

            <div class="col-span-12">
                <flux:button variant="primary" type="submit">Salvar</flux:button>
            </div>
        </form>
    </flux:card>
</div>



