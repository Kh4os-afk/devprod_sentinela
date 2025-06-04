<div class="space-y-6">
    <div>
        <flux:heading size="xl" level="1">Criar Consulta</flux:heading>

        <flux:subheading size="lg">Criar Consulta</flux:subheading>
    </div>
    <flux:separator variant="subtle"/>
    <div>
        <flux:callout color="emerald">
            <flux:callout.heading icon="exclamation-triangle">Atenção</flux:callout.heading>

            <flux:callout.text>Os campos do select devem ser especificados.</flux:callout.text>
        </flux:callout>
    </div>
    <flux:card>
        <form wire:submit="submit" class="grid grid-cols-12 gap-4">
            <div class="col-span-12">
                <flux:select wire:model="modulo" label="Modulo">
                        <flux:select.option selected value="null">Selecione o Modulo</flux:select.option>
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


            <div class="col-span-12">
                <flux:textarea label="Consulta" rows="auto" placeholder="select matricula,nome,cpf,rg from dual;" wire:model="consulta"/>
            </div>

            <div class="col-span-12">
                <flux:button variant="primary" type="submit">Salvar</flux:button>
            </div>
        </form>
    </flux:card>
</div>



