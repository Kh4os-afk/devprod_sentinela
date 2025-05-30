<div class="space-y-6">
    <div>
        <flux:heading size="xl" level="1">Criar Sub Modulo</flux:heading>

        <flux:subheading size="lg">Criar Sub Modulo</flux:subheading>
    </div>
    <flux:separator variant="subtle"/>
    <flux:card>
        <form wire:submit="submit" class="grid grid-cols-12 gap-4">
            <div class="col-span-6">
                <flux:select wire:model="modulo" label="Modulo">
                    @forelse(\App\Models\Module::all() as $module)
                        <flux:select.option value="{{ $module->id }}">{{ $module->modulo }}</flux:select.option>
                    @empty
                        <option value="null">Nenhum Valor Disponivel</option>
                    @endforelse
                </flux:select>
            </div>

            <div class="col-span-6">
                <flux:input label="Nome do Sub Modulo" wire:model="submodulo"/>
            </div>

            <div class="col-span-12">
                <flux:button variant="primary" type="submit">Salvar</flux:button>
            </div>
        </form>
    </flux:card>
</div>
