<div class="space-y-6">
    <flux:heading size="xl" level="1">Sentinela</flux:heading>

    <flux:subheading size="lg" class="mb-6">Monitore, audite e organize os processos da sua empresa com eficiência.</flux:subheading>

    <flux:separator variant="subtle"/>
    <div class="grid grid-cols-12 gap-4">
        <flux:card class="col-span-5">
            <flux:heading class="pb-4">{{ $editar ? 'Editar Usuário' : 'Cadastrar Usuário' }}</flux:heading>
            <form wire:submit="submit" class="space-y-4">
                <div class="col-span-5">
                    <flux:input label="Nome" type="text" placeholder="Nome" wire:model.blur="name"/>
                </div>
                <div class="col-span-5">
                    <flux:input label="Email" placeholder="email@email.com" wire:model.blur="email"/>
                </div>
                <div class="col-span-2">
                    <flux:select wire:model.blur="is_admin" label="Admin">
                        <flux:select.option value="0" selected>Não</flux:select.option>
                        <flux:select.option value="1">Sim</flux:select.option>
                    </flux:select>
                </div>
                <div class="col-span-12">
                    <flux:select wire:model="modulo" multiple indicator="checkbox" variant="listbox" label="Modulos Disponiveis">
                        @forelse(\App\Models\Module::all() as $module)
                            <flux:select.option value="{{ $module->id }}">{{ $module->modulo }}</flux:select.option>
                        @empty
                            <flux:select.option value="null">Nenhum Valor Disponivel</flux:select.option>
                        @endforelse
                    </flux:select>
                </div>
                <div class="flex gap-2 pt-4">
                    <flux:spacer/>

                    <flux:button type="button" wire:click="resetar()" variant="ghost">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">{{ $editar ? 'Salvar' : 'Criar' }}</flux:button>
                </div>
            </form>
        </flux:card>
        <flux:card class="col-span-7 space-y-4">
            <flux:heading>Usuários</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nome</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Admin</flux:table.column>
                    <flux:table.column>Operações</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($usuarios as $user)
                        <flux:table.row>
                            <flux:table.cell>{{ $user->name }}</flux:table.cell>
                            <flux:table.cell>{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>{{ $user->is_admin ? 'Sim' : 'Não'}}</flux:table.cell>
                            <flux:table.cell class="text-center">
                                <flux:button square wire:click="delete({{ $user->id }})" wire:loading.attr="disabled">D</flux:button>
                                <flux:button square wire:click="edit({{ $user->id }})" wire:loading.attr="disabled">E</flux:button>
                                {{--<a href="mailto:{{ $user->email }}?subject=Bem-vindo ao Sistema Sentinela&body=Olá {{ $user->name }},%0A%0ASeja bem-vindo ao Sistema Sentinela.%0A%0APara acessar o sistema, utilize o seguinte link:%0Ahttp://172.22.22.172:8001%0A%0ASuas credenciais de acesso são:%0AEmail: {{ $user->email }}%0ASenha: padrao" class="btn btn-primary"></a>--}}
                                <flux:button square wire:click="resetPassword({{ $user->id }})" wire:loading.attr="disabled">R</flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <tr>
                            <td>Sem Usuários!</td>
                        </tr>
                    @endforelse
                </flux:table.rows>
            </flux:table>
            {{ $usuarios->links(data: ['scrollTo' => false]) }}
        </flux:card>
    </div>
</div>