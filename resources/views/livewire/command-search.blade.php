<div>
    <flux:modal.trigger name="search" shortcut="alt.k">
        <flux:input as="button" variant="filled" placeholder="Pesquisar..." icon="magnifying-glass" kbd="Alt + K"/>
    </flux:modal.trigger>

    <flux:modal name="search" variant="bare" class="w-full max-w-[30rem] my-[12vh] max-h-screen overflow-y-hidden">
        <flux:command class="border-none shadow-lg inline-flex flex-col max-h-[76vh]">
            <flux:command.input placeholder="Pesquisar..." closable/>
            <flux:command.items>
                @forelse(\App\Models\Query::all() as $query)
                    <flux:command.item wire:click="relatorio({{ $query->id }})" icon="user-plus">{{ $query->titulo }}</flux:command.item>
                @empty
                @endforelse
            </flux:command.items>
        </flux:command>
    </flux:modal>
</div>