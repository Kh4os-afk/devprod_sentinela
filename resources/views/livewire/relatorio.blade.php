<flux:card>
    <flux:heading size="xl" class="text-center">{{ $titulo ?? 'Relatório' }}</flux:heading>
    <flux:subheading size="lg" class="text-center">{{ $data ? $data->format('d/m/Y H:i:s') : '' }}</flux:subheading>
    <table id="table" class="display compact" style="width: 100%">
        <thead>
        <tr>
            @forelse ($relatorios[0] ?? [] as $chave => $valor)
                <th>{{ ucwords(str_replace('_',' ',$chave)) }}</th>
            @empty
                <th class="text-center">Sem Dados</th>
            @endforelse
        </tr>
        </thead>
        <tbody>
        @forelse ($relatorios as $objeto)
            <tr>
                @forelse ($objeto as $valor)
                    <td class="text-truncate">{{ $valor }}</td>
                @empty
                @endforelse
            </tr>
    @empty
    @endforelse
</flux:card>

@assets
<script src="{{ asset('datatables/datatables.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

<link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet">
@endassets

@script
<script>
    $(document).ready(function () {
        const table = $('#table').DataTable({
            language: {
                url: '{!! asset('datatables/pt-BR.json') !!}'
            },
            lengthMenu: [
                [15, 25, 50, 100, -1],
                [15, 25, 50, 100, 'All']
            ],
            layout: {
                topStart: 'buttons',
                bottomStart: 'pageLength',
            },
            colReorder: true,
            scrollX: true,
            buttons: [
                'colvis',
                'searchBuilder',
                {
                    extend: 'excelHtml5',
                    title: {!! json_encode($titulo ?? 'Relatório') !!},
                    filename: {!! json_encode($titulo ?? 'Relatório') !!},
                    createEmptyCells: true,
                    customizeData: function (data) {
                        for (var i = 0; i < data.body.length; i++) {
                            for (var j = 0; j < data.body[i].length; j++) {
                                // Verifica se no DataTables o campo é maior que 16 dígitos e é um número para converter em string
                                if (!isNaN(data.body[i][j]) && data.body[i][j].length > 16) {
                                    data.body[i][j] = '\u200C' + data.body[i][j];
                                }

                                // Verifica se o valor numérico começa com '.' e adiciona o zero
                                if (typeof data.body[i][j] === 'string' && data.body[i][j].trim().startsWith('.')) {
                                    data.body[i][j] = '0' + data.body[i][j];
                                }
                            }
                        }
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        // Estilos para a primeira linha: centralizado, negrito, com bordas
                        sheet.querySelectorAll('row').forEach((row, rowIndex) => {
                            if (rowIndex === 0) { // Primeira linha
                                row.querySelectorAll('c').forEach((el) => {
                                    el.setAttribute('s', '51'); // Estilo 51: centralizado e negrito com bordas
                                });
                            } else if (rowIndex === 1) { // Segunda linha
                                row.querySelectorAll('c').forEach((el) => {
                                    el.setAttribute('s', '27'); // Estilo 27: negrito com bordas
                                });
                            } else if (rowIndex >= 2) { // Terceira linha em diante
                                row.querySelectorAll('c').forEach((el) => {
                                    el.setAttribute('s', '25'); // Estilo 25: bordas
                                });
                            }
                        });
                    },
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'print',
                    title: {!! json_encode($titulo ?? 'Relatório') !!}, // Customiza o título
                    messageTop: function () {
                        // Centraliza a data no topo do documento de impressão
                        return '<h2 style="text-align: center; margin-bottom: 30px;">' + {!! json_encode($data ? $data->format('d/m/Y H:i:s') : '') !!} + '</h2>';
                    },
                    customize: function (win) {
                        $(win.document.body).find('h1').css('text-align', 'center');
                    },
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: {!! json_encode($titulo ?? 'Relatório') !!},
                    filename: {!! json_encode($titulo ?? 'Relatório') !!},
                    orientation: 'landscape',
                    pageSize: 'A3',
                    customize: function (doc) {
                        // Encontra o título e ajusta sua margem inferior
                        doc.content[0].margin = [0, 0, 0, 5]; // Ajuste a margem inferior do título

                        // Centraliza a mensagem no topo do PDF
                        doc.content.splice(1, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'center',
                            fontSize: 12, // Aumente esse valor para uma fonte maior
                            text: {!! json_encode($data ? $data->format('d/m/Y H:i:s') : '') !!},
                        });
                    },
                    exportOptions: {
                        columns: ':visible' // Somente colunas visíveis serão exportadas
                    },
                },
                {
                    extend: 'csvHtml5',
                    title: {!! json_encode($titulo ?? 'Relatório') !!},
                    filename: {!! json_encode($titulo ?? 'Relatório') !!},
                    exportOptions: {
                        columns: ':visible',
                    }
                }
            ],
            /*Verifica se o campo da tabela possui R$ para formatalo como numero*/
            createdRow: function (row, data, dataIndex) {
                $('td', row).each(function (index) {
                    var cellData = data[index];

                    // Verifica se o valor é uma string numérica que começa com '.'
                    if (typeof cellData === 'string' && cellData.trim().startsWith('.')) {
                        // Adiciona o zero antes do ponto
                        cellData = '0' + cellData;
                    }

                    // Verifica se o valor é numérico e atualiza a célula com o valor correto
                    if (!isNaN(cellData)) {
                        $(this).text(cellData);
                    }

                    if (typeof cellData === 'string' && cellData.includes('R$')) {
                        var numericValue = parseFloat(cellData.replace(/[^\d.-]/g, ''));
                        $(this).text('R$ ' + numeral(numericValue).format('0,0.00').replace(/,/g, 'X').replace(/\./g, ',').replace(/X/g, '.'));
                    }
                });
            }
        });
    });
</script>

@endscript
