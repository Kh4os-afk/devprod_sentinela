                    <div class="col-md-12">
                        <label class="control-label" for="modulo">Modulo</label>
                        <select class="form-control select2 @error('modulo') is-invalid @enderror" id="modulo" wire:model="modulo">
                            @forelse(\App\Models\Module::all() as $module)
                                <option value="{{ $module->id }}">{{ $module->modulo }}</option>
                            @empty
                                <option value="null">Nenhum Valor Disponivel</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label" for="titulo">Titulo</label>
                        <input class="form-control @error('titulo') is-invalid @enderror" type="text" id="titulo" placeholder="Titulo" wire:model="titulo">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="tabela">Tabela</label>
                        <input class="form-control @error('tabela') is-invalid @enderror" type="text" id="tabela" placeholder="Tabela" wire:model.live="tabela">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="tabela">Tabelas Existentes</label>
                        <select class="form-select" wire:model="tabela" style="background-color: #e9ecef; opacity: 1">
                            @foreach($sugestoes as $sugestao)
                                <option value="{{ $sugestao }}">{{ $sugestao }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label" for="atualizacao">Tempo de Atualização em Horas</label>
                        <input class="form-control @error('atualizacao') is-invalid @enderror" type="number" step="1" id="atualizacao" wire:model="atualizacao">
                    </div>
                    <div class="col-md-12">
                        <label class="control-label" for="consulta">Consulta</label>
                        <textarea class="form-control @error('consulta') is-invalid @enderror" type="text" id="consulta" rows="8" placeholder="Consulta" wire:model="consulta">
                            </textarea>
                    </div>
                    <div class="text-danger">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"> Editar uma consulta faz com que seus dados sejam atualizados</i>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-success" type="submit">Salvar</button>
                        <button class="btn btn-secondary" type="button" wire:click="$dispatch('closeModal')">Voltar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--
@script
<script data-navigate-once>
    $('#modulo').select2({
        placeholder: "Modulo",
        theme: 'bootstrap4',
    });
    $('#modulo').on('change', function (e) {
        @this.
        set('modulo', e.target.value);
    });
</script>

@endscript
--}}
