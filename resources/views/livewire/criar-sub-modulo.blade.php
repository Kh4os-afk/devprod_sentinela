<div class="space-y-6">
    <div>
        <flux:heading size="lg">Criar Sub Modulo</flux:heading>
        <flux:text class="mt-2">Preencha os campos abaixo para</flux:text>
        <flux:text>adicionar um novo submódulo ao sistema.</flux:text>
    </div>
    <form wire:submit="submit" class="space-y-6">
        <flux:select wire:model="modulo" label="Modulo" disabled>
            <flux:select.option selected value="null">Selecione o Modulo</flux:select.option>
            @forelse(\App\Models\Module::all() as $module)
                <flux:select.option value="{{ $module->id }}">{{ $module->modulo }}</flux:select.option>
            @empty
                <option value="null">Nenhum Valor Disponivel</option>
            @endforelse
        </flux:select>

        <flux:input label="Nome do Sub Modulo" wire:model="submodulo"/>

        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary">Salvar</flux:button>
        </div>
    </form>
    <flux:separator variant="subtle" text="Sub Modulos"/>
    <div>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Modulo</flux:table.column>
                <flux:table.column>Sub Modulo</flux:table.column>
                <flux:table.column>Deletar</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse(\App\Models\SubModulo::where('modulo_id',$modulo)->get() as $subModulo)
                    <flux:table.row>
                        <flux:table.cell variant="strong">{{ $subModulo->modulo_id }}</flux:table.cell>
                        <flux:table.cell>{{ $subModulo->submodulo }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button icon="trash" size="sm" variant="ghost" wire:click="delete({{ $subModulo->id }})"></flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>


