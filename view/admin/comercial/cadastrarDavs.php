<?php
$dados = array(
    'NomePagina' => 'Pedidos / Orçamentos',
    'MenuModulo' => 'Comercial'
);
?>


<?php include('../layouts/body.php') ?>

<?php
$formasDePagamento = array();
$resultado = $conexao->query("SELECT ID, DESCRICAO FROM FIN_FORMAS_DE_PAGAMENTO WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $formasDePagamento[] = $row;
}

$clientesArray = array();
$resultado = $conexao->query("SELECT ID, NOME, DOCUMENTO FROM BD_PESSOAS WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $clientesArray[] = $row;
}

$produtosArray = array();
$resultado = $conexao->query("SELECT 
    A.ID, 
    A.DESCRICAO,
    B.SIGLA AS UNIDADE_MEDIDA,
    A.VALOR_VENDA
FROM EST_PRODUTOS A 
    INNER JOIN EST_UNIDADES_MEDIDA B ON B.ID = A.UNIDADE_MEDIDA 
WHERE A.ATIVO = 'S' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $produtosArray[] = $row;
}
?>

<section class="section">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= URL_BASE_HOST ?>/view/admin/comercial/davs.php"><?= $dados['NomePagina'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
        </ol>
    </nav>

    <div class="row">

        <div class="col-12">
            <div class="card card-dark">

                <div class="card-header">
                    <h4>Dados Gerais</h4>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-12 col-xl-6">
                            <label for="cliente" class="mb-1">Cliente *</label>
                            <select class="form-control form-control-sm select2 w-100" onchange="preencherDados()" name="cliente" id="cliente">
                                <option value="" selected disabled>Selecione...</option>
                                <?php foreach ($clientesArray as $cliente) { ?>

                                    <option value="<?= $cliente['ID'] ?>"><?= $cliente['NOME'] ?> (<?= $cliente['DOCUMENTO'] ?>)</option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-xl-6">
                            <label for="validade" class="mb-1">Validade</label>
                            <input type="date" id="validade" name="validade" onchange="preencherDados()" class="form-control">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12 col-xl-6 mt-2">
                            <label for="outrasDespesas" class="mb-1">R$ Outras Despesas: *</label>
                            <input type="text" id="outrasDespesas" name="outrasDespesas" value="0,00" onkeyup="preencherDados()" class="form-control moeda">
                        </div>

                        <div class="col-12 col-xl-6 mt-2">
                            <label for="frete" class="mb-1">R$ Frete: *</label>
                            <input type="text" id="frete" name="frete" value="0,00" onkeyup="preencherDados()" class="form-control moeda">
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-12 mt-2">
                            <label for="observacoes">Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes"></textarea>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card card-dark">

                <div class="card-header">
                    <h4>Produtos</h4>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-12 col-xl-3">
                            <label for="produto" class="mb-1">Produto: *</label>
                            <select class="form-control form-control-sm select2" style="width: 100%;" name="produto" id="produto">
                                <option value="" selected disabled>Selecione...</option>
                                <?php foreach ($produtosArray as $produto) { ?>

                                    <option value="<?= $produto['ID'] ?>" data-descricao="<?= $produto['DESCRICAO'] ?>" data-valor_venda="<?= number_format($produto['VALOR_VENDA'], 2, ',', '.') ?>"><?= $produto['DESCRICAO'] ?>&nbsp;(<?= $produto['UNIDADE_MEDIDA'] ?>)</option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="quantidade" class="mb-1">Quantidade</label>
                            <input type="number" id="quantidade" name="quantidade" onkeyup="atualizarValorProduto()" class="form-control" min="1" disabled>
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="valor" class="mb-1">R$ Valor Unitário</label>
                            <input type="text" id="valor" name="valor" onkeyup="atualizarValorProduto()" class="form-control moeda" disabled>
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="desconto" class="mb-1">% Desconto</label>
                            <input type="text" id="desconto" name="desconto" onkeyup="atualizarValorProduto()" class="form-control percentual" disabled>
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="valorTotal" class="mb-1">R$ Valor total</label>
                            <input type="text" id="valorTotal" name="valorTotal" class="form-control moeda" disabled>
                        </div>

                        <div class="col-12 col-xl-1">
                            <label for="btnAdicionarProduto" class="mb-1">Adicionar</label>
                            <button id="btnAdicionarProduto" name="btnAdicionarProduto" onclick="adicionarProduto()" type="button" class="btn btn-lg btn-success w-100">
                                <i class="fas fa-plus-square"></i>
                            </button>
                        </div>

                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="table-responsive">
                                <table id="tabelaDeProdutos" class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">Ordem</th>
                                            <th width="35%">Produto</th>
                                            <th width="12.5%">Quantidade</th>
                                            <th width="12.5%">Valor unitário</th>
                                            <th width="12.5%">Desconto</th>
                                            <th width="12.5%">Valor total</th>
                                            <th width="10%">Deletar</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-dark">

                <div class="card-header">
                    <h4>Pagamento</h4>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-12 col-xl-5">
                            <label for="formaPagamento" class="mb-1">Forma de Pagamento: *</label>
                            <select class="form-control form-control-sm select2" style="width: 100%;" name="formaPagamento" id="formaPagamento">
                                <option value="" selected disabled>Selecione...</option>
                                <?php foreach ($formasDePagamento as $formaPagamento) { ?>

                                    <option value="<?= $formaPagamento['ID'] ?>"><?= $formaPagamento['DESCRICAO'] ?></option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="valorPagamento" class="mb-1">R$ Valor: *</label>
                            <input type="text" id="valorPagamento" name="valorPagamento" class="form-control moeda">
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="vencimentoPagamento" class="mb-1">Vencimento: *</label>
                            <input type="date" id="vencimentoPagamento" name="vencimentoPagamento" class="form-control">
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="valorRestante" class="mb-1">R$ Valor Restante:</label>
                            <input type="text" id="valorRestante" name="valorRestante" class="form-control" disabled>
                        </div>

                        <div class="col-12 col-xl-1">
                            <label for="btnAdicionarPagamento" class="mb-1">Adicionar</label>
                            <button id="btnAdicionarPagamento" name="btnAdicionarPagamento" onclick="adicionarPagamento()" type="button" class="btn btn-lg btn-success w-100">
                                <i class="fas fa-plus-square"></i>
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    <?php include('../layouts/footer.php') ?>

</section>


<script>
    function preencherDados() {
        let cliente = $('#cliente').va();
        let validade = $('#validade').va();
        let frete = $('#frete').va();
        let outrasDespesas = $('#outrasDespesas').va();
        let observacoes = $('#observacoes').va();
    }

    function cadastrarProduto() {
        const fieldsToValidate = [
            'descricao', 'quantidade', 'custo', 'valorVenda', 'tipoMercadoria', 'unidadeMedida',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'novoProduto');
        form_data.append('descricao', $('#descricao').val());
        form_data.append('custo', $('#custo').val());
        form_data.append('quantidade', $('#quantidade').val());
        form_data.append('valorVenda', $('#valorVenda').val());
        form_data.append('peso', $('#peso').val());
        form_data.append('tipoMercadoria', $('#tipoMercadoria').val());
        form_data.append('unidadeMedida', $('#unidadeMedida').val());
        form_data.append('grupo', $('#grupo').val());
        form_data.append('cor', $('#cor').val());
        form_data.append('tamanho', $('#tamanho').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Produtos_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {
                    window.location = '<?= URL_BASE_HOST ?>/view/admin/estoque/produtos.php';
                } else {
                    exibirToastr(response.msg, 'danger');
                }
            }
        });
    }

    let contador = 0;

    function adicionarProduto() {
        const fieldsToValidate = [
            'produto', 'quantidade', 'valor', 'valorTotal',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        let produtoId = $('#produto').val();
        let descricao = $('#produto').find(':selected').data('descricao');
        let quantidade = $('#quantidade').val();
        let valor = $('#valor').val();
        let desconto = $('#desconto').val();
        let valorTotal = $('#valorTotal').val();

        let rowData = [
            contador + 1,
            descricao,
            quantidade,
            valor,
            desconto,
            valorTotal,
            `
                <a class="btn btn-danger btn-action" title="Deletar" onclick="removerProduto(${contador})">
                    <i class="fas fa-trash"></i>
                </a>
            `
        ];

        updateRowInTable('#tabelaDeProdutos', 'rowProduto_', contador, rowData);
        updateTfootFromTable('#tabelaDeProdutos', [2, 3, 4, 5]);

        let lastRowNode = $('#tabelaDeProdutos').DataTable().row(':last').node(); // Pega o último nó adicionado
        $(lastRowNode).attr('data-id', produtoId);

        $('#produto').val(null).trigger('change');
        $('#quantidade').val(null);
        $('#valor').val(null);
        $('#desconto').val(null);
        $('#valorTotal').val(null);
        $('#quantidade').prop('disabled', true);
        $('#valor').prop('disabled', true);
        $('#desconto').prop('disabled', true);

        recalcularIndices('#tabelaDeProdutos', 'rowProduto_', 'removerProduto');
    }

    function removerProduto(contador) {
        removeRowFromTable('#tabelaDeProdutos', 'rowProduto_', contador);
        updateTfootFromTable('#tabelaDeProdutos', [2, 3, 4, 5]); // Ajusta os índices das colunas que serão somadas

        // Recalcula os índices das linhas e atualiza o contador após remover
        recalcularIndices('#tabelaDeProdutos', 'rowProduto_', 'removerProduto');
    }

    function atualizarValorProduto() {
        let quantidade = numberFormat($('#quantidade').val());
        let valor = numberFormat($('#valor').val());
        let desconto = numberFormat($('#desconto').val());
        let valorTotal = valor * quantidade;

        if (desconto > 100) {

            $('#desconto').addClass('is-invalid');
            $('#desconto-error').remove();
            $(`<span id="desconto-error" class="error invalid-feedback">O desconto não pode ser maior que 100%</span>`).insertAfter($('#desconto'));
            return;
        } else {

            $('#desconto').removeClass('is-invalid');
            $('#desconto-error').remove();
        }

        if (desconto > 0) {
            valorTotal = valorTotal * (1 - desconto / 100);
        }

        // Atualiza o campo de valor total
        $('#valorTotal').val(moedaFormat(valorTotal));
    }

    function recalcularIndices(tableId, prefix, funcao) {
        let table = $(tableId).DataTable();

        // Recalcula o índice (contador) para cada linha visível na tabela
        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
            let node = this.node();
            $(node).attr("id", prefix + rowIdx); // Atualiza o ID com o novo contador baseado no índice
            $(node).find('.btn-action').attr("onclick", `${funcao}(${rowIdx})`); // Atualiza a função de remoção com o novo índice

            // Atualiza a primeira célula da linha com o novo valor do contador
            $(node).children('td:first').text(rowIdx + 1);
        });

        // Atualiza o contador global com o total de linhas
        contador = table.rows().count();
    }

    function getTotalFromTable(tableId, columns) {
        let table = $(tableId).DataTable();
        let totals = {};

        // Inicializa os totais das colunas com 0
        columns.forEach(colIndex => {
            totals[colIndex] = 0;
        });

        // Percorre todas as linhas da tabela
        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
            let row = this.data(); // Pega os dados da linha

            // Para cada coluna especificada, soma os valores
            columns.forEach(colIndex => {
                let value = parseFloat(row[colIndex]) || 0; // Converte o valor da célula para float (ou 0 se não for numérico)
                totals[colIndex] += value;
            });
        });

        return totals; // Retorna o objeto com os totais de cada coluna
    }

    $('.moeda').maskMoney({
        thousands: '.',
        decimal: ',',
        prefix: 'R$ ',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });

    $('.percentual').mask('##0,00%', {
        reverse: true
    });

    $('#produto').on('change', function(e) {
        let valorVenda = $(this).find(':selected').data('valor_venda');
        if (valorVenda) {
            $('#quantidade').prop('disabled', false);
            $('#quantidade').val(1);
            $('#valor').prop('disabled', false);
            $('#valor').val(valorVenda);
            $('#desconto').prop('disabled', false);
            $('#valorTotal').val(valorVenda);
        }
    });
</script>