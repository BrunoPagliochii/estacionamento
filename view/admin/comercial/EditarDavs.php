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

$davStatusArray = array();
$resultado = $conexao->query("SELECT ID, DESCRICAO FROM DAVS_STATUS WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $davStatusArray[] = $row;
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
            <li class="breadcrumb-item active" aria-current="page">Editar Dav: #<?= $_GET['i'] ?></li>
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

                        <div class="col-12 col-xl-4">
                            <label for="cliente" class="mb-1">Cliente: *</label>
                            <select class="form-control form-control-sm select2 w-100" onchange="preencherDados()" name="cliente" id="cliente">
                                <option value="" selected disabled>Selecione...</option>
                                <?php foreach ($clientesArray as $cliente) { ?>

                                    <option value="<?= $cliente['ID'] ?>"><?= $cliente['NOME'] ?> (<?= $cliente['DOCUMENTO'] ?>)</option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-xl-4">
                            <label for="status" class="mb-1">Status: *</label>
                            <select class="form-control form-control-sm select2 w-100" onchange="preencherDados()" name="status" id="status">
                                <option value="" selected disabled>Selecione...</option>
                                <?php foreach ($davStatusArray as $status) { ?>

                                    <option value="<?= $status['ID'] ?>"><?= $status['DESCRICAO'] ?></option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 col-xl-4">
                            <label for="validade" class="mb-1">Validade: *</label>
                            <input type="date" id="validade" name="validade" onchange="preencherDados()" class="form-control">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12 col-xl-6 mt-2">
                            <label for="outrasDespesas" class="mb-1">R$ Outras Despesas: *</label>
                            <input type="text" id="outrasDespesas" name="outrasDespesas" value="0,00" onblur="preencherDados()" class="form-control moeda">
                        </div>

                        <div class="col-12 col-xl-6 mt-2">
                            <label for="frete" class="mb-1">R$ Frete: *</label>
                            <input type="text" id="frete" name="frete" value="0,00" onblur="preencherDados()" class="form-control moeda">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <label for="observacoes">Observações</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" onblur="preencherDados()"></textarea>
                        </div>
                    </div>

                    <button id="btnEditarDav" onclick="editarDav()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>

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
                            <label for="quantidade" class="mb-1">Quantidade: *</label>
                            <input type="number" id="quantidade" name="quantidade" onkeyup="atualizarValorProduto()" class="form-control" min="1" disabled>
                        </div>

                        <div class="col-12 col-xl-2">
                            <label for="valor" class="mb-1">R$ Valor Unitário: *</label>
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

                                    <option value="<?= $formaPagamento['ID'] ?>" data-descricao="<?= $formaPagamento['DESCRICAO'] ?>"><?= $formaPagamento['DESCRICAO'] ?></option>

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

                    <hr>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="table-responsive">
                                <table id="tabelaDePagamentos" class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">Ordem</th>
                                            <th width="35%">Forma de pagamento</th>
                                            <th width="25%">Valor</th>
                                            <th width="25%">Vencimento</th>
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

    </div>

    <?php include('../layouts/footer.php') ?>

</section>


<script>
    $(function() {

        // Verifica se os parâmetros estão presentes e não estão vazios
        if (!(new URLSearchParams(window.location.search).get('i'))) {
            alert('Dados inválidos');
            window.location = '<?= URL_BASE_HOST ?>/view/admin/comercial/davs.php';
        } else {
            buscarDav(new URLSearchParams(window.location.search).get('i'));
        }
    });

    let contadorProduto = 0;
    let contadorPagamento = 0;

    let dav = {
        cliente: '',
        status: '',
        validade: '',
        frete: 0,
        outrasDespesas: 0,
        observacoes: null,
        total: 0,
        produtos: [],
        pagamentos: [],
    };

    function buscarDav(id) {
        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'buscarDav');
        form_data.append('id', id);

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/comercial/JQ_Davs_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.statusHttp == 'success') {
                    // Preenche os campos principais
                    $('#cliente').val(response.davs.id_cliente).trigger("change");
                    $('#validade').val(response.davs.validade.split(' ')[0]).trigger("change");
                    $('#outrasDespesas').val(moedaFormat(response.davs.outras_despesas));
                    $('#frete').val(moedaFormat(response.davs.frete));
                    $('#observacoes').val(response.davs.observacoes);
                    $('#status').val(response.davs.status_id).trigger("change");

                    if(response.davs.permite_alterar == 'N'){
                        $('#btnEditarDav').remove();
                    }
                    preencherDados();

                    // Inicializa o array de produtos se ainda não estiver definido
                    if (!Array.isArray(dav.produtos)) {
                        dav.produtos = [];
                    }

                    // Verifica se 'produtos' existe e se é um array antes de iterar
                    if (Array.isArray(response.davs.produtos)) {
                        response.davs.produtos.forEach(function(prod) {
                            let produto = {
                                ordem: parseFloat(dav.produtos.length + 1),
                                id: parseFloat(prod.produto_id),
                                descricao: prod.produto,
                                quantidade: parseFloat(prod.quantidade),
                                valor: parseFloat(prod.valor_unitario),
                                desconto: parseFloat(prod.desconto),
                                valorTotal: parseFloat(prod.valor_total)
                            };

                            dav.produtos.push(produto);

                            let rowData = [
                                produto.ordem,
                                produto.descricao,
                                moedaFormat(produto.quantidade),
                                moedaFormat(produto.valor),
                                moedaFormat(produto.desconto),
                                moedaFormat(produto.valorTotal),
                                `
                                    <a class="btn btn-danger btn-action" title="Deletar" onclick="removerProduto(${produto.ordem - 1})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                `
                            ];

                            updateRowInTable('#tabelaDeProdutos', 'rowProduto_', produto.ordem - 1, rowData);
                            updateTfootFromTable('#tabelaDeProdutos', [2, 3, 4, 5]);
                            recalcularIndices('#tabelaDeProdutos', 'rowProduto_', 'removerProduto');
                            atualizarTotal();
                        });
                    }

                    // Inicializa o array de pagamentos se ainda não estiver definido
                    if (!Array.isArray(dav.pagamentos)) {
                        dav.pagamentos = [];
                    }

                    // Verifica se 'pagamentos' existe e se é um array antes de iterar
                    if (Array.isArray(response.davs.pagamentos)) {
                        response.davs.pagamentos.forEach(function(pag) {


                            let pagamento = {
                                ordem: dav.pagamentos.length + 1,
                                id: parseFloat(pag.forma_pagamento_id),
                                descricao: pag.forma_pagamento,
                                vencimento: pag.vencimento.split(' ')[0],
                                valor: parseFloat(pag.valor),

                            };

                            dav.pagamentos.push(pagamento);

                            let rowData = [
                                parseFloat(pagamento.ordem),
                                pagamento.descricao,
                                moedaFormat(pagamento.valor),
                                pagamento.vencimento,
                                `
                                    <a class="btn btn-danger btn-action" title="Deletar" onclick="removerPagamento(${pagamento.ordem - 1})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                `
                            ];


                            updateRowInTable('#tabelaDePagamentos', 'rowPagamento_', produto.ordem - 1, rowData);
                            updateTfootFromTable('#tabelaDePagamentos', [2]);
                            recalcularIndices('#tabelaDePagamentos', 'rowPagamento_', 'removerPagamento');
                            atualizarTotal();
                        });
                    }
                } else {
                    window.location = '<?= URL_BASE_HOST ?>/view/admin/comercial/davs.php';
                }
            }
        });
    }

    function preencherDados() {
        dav.cliente = numberFormat($('#cliente').val());
        dav.validade = $('#validade').val();
        dav.frete = numberFormat($('#frete').val() || 0);
        dav.outrasDespesas = numberFormat($('#outrasDespesas').val() || 0);
        dav.observacoes = $('#observacoes').val();
        dav.status = $('#status').val();

        atualizarTotal();
    }

    function adicionarProduto() {
        const fieldsToValidate = [
            'produto', 'quantidade', 'valor', 'valorTotal',
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

        if (numberFormat(quantidade) <= 0) {

            $('#quantidade').addClass('is-invalid');
            $('#quantidade-error').remove();
            $(`<span id="quantidade-error" class="error invalid-feedback">O quantidade deve ser maior que 0</span>`).insertAfter($('#quantidade'));
            return;
        } else {

            $('#quantidade').removeClass('is-invalid');
            $('#quantidade-error').remove();
        }

        if (numberFormat(valor) <= 0) {

            $('#valor').addClass('is-invalid');
            $('#valor-error').remove();
            $(`<span id="valor-error" class="error invalid-feedback">O valor deve ser maior que 0,00</span>`).insertAfter($('#valor'));
            return;
        } else {

            $('#valor').removeClass('is-invalid');
            $('#valor-error').remove();
        }

        let produto = {
            ordem: dav.produtos.length + 1,
            id: numberFormat(produtoId),
            descricao: descricao,
            quantidade: numberFormat(quantidade),
            valor: numberFormat(valor),
            desconto: numberFormat(desconto),
            valorTotal: numberFormat(valorTotal)
        };

        dav.produtos.push(produto);

        let rowData = [
            produto.ordem,
            descricao,
            quantidade,
            valor,
            desconto,
            valorTotal,
            `
                <a class="btn btn-danger btn-action" title="Deletar" onclick="removerProduto(${produto.ordem - 1})">
                    <i class="fas fa-trash"></i>
                </a>
            `
        ];

        updateRowInTable('#tabelaDeProdutos', 'rowProduto_', produto.ordem - 1, rowData);
        updateTfootFromTable('#tabelaDeProdutos', [2, 3, 4, 5]);
        atualizarTotal();

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

    function removerProduto(index) {
        dav.produtos.splice(index, 1);

        dav.produtos.forEach((produto, i) => {
            produto.ordem = i + 1;
        });

        removeRowFromTable('#tabelaDeProdutos', 'rowProduto_', index);
        updateTfootFromTable('#tabelaDeProdutos', [2, 3, 4, 5]);
        atualizarTotal();
        recalcularIndices('#tabelaDeProdutos', 'rowProduto_', 'removerProduto');
    }

    function atualizarTotal() {
        let totalProdutos = dav.produtos.reduce((acc, produto) => {
            return acc + numberFormat(produto.valorTotal);
        }, 0);

        let frete = numberFormat(dav.frete) || 0;
        let outrasDespesas = numberFormat(dav.outrasDespesas) || 0;

        dav.total = (totalProdutos + frete + outrasDespesas);

        let totalPagamentos = dav.pagamentos.reduce((acc, pagamento) => {
            return acc + numberFormat(pagamento.valor);
        }, 0);

        let valorRestante = dav.total - totalPagamentos;
        $('#valorRestante').val(moedaFormat(valorRestante));
    }

    function adicionarPagamento() {
        const fieldsToValidate = [
            'formaPagamento', 'valorPagamento', 'vencimentoPagamento',
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        let pagamentoID = $('#formaPagamento').val();
        let descricao = $('#formaPagamento').find(':selected').data('descricao');
        let valor = $('#valorPagamento').val();
        let vencimento = $('#vencimentoPagamento').val();
        let valorRestante = $('#valorRestante').val() || 0;

        if (numberFormat(valor) <= 0) {

            $('#valorPagamento').addClass('is-invalid');
            $('#valorPagamento-error').remove();
            $(`<span id="valorPagamento-error" class="error invalid-feedback">O valorPagamento deve ser maior que 0</span>`).insertAfter($('#valorPagamento'));
            return;
        } else {

            $('#valorPagamento').removeClass('is-invalid');
            $('#valorPagamento-error').remove();
        }


        if ((numberFormat(valorRestante) - numberFormat(valor)) < 0) {
            $('#valorPagamento').addClass('is-invalid');
            $('#valorPagamento-error').remove();
            $(`<span id="valorPagamento-error" class="error invalid-feedback">O valor de pagamento não pode exceder o valor restante </span>`).insertAfter($('#valorPagamento'));
            return;
        } else {

            $('#desconto').removeClass('is-invalid');
            $('#desconto-error').remove();
        }

        let pagamento = {
            ordem: dav.pagamentos.length + 1,
            id: numberFormat(pagamentoID),
            descricao: descricao,
            vencimento: vencimento,
            valor: numberFormat(valor),
        };

        dav.pagamentos.push(pagamento);

        let rowData = [
            pagamento.ordem,
            descricao,
            moedaFormat(valor),
            vencimento,
            `
                <a class="btn btn-danger btn-action" title="Deletar" onclick="removerPagamento(${pagamento.ordem - 1})">
                    <i class="fas fa-trash"></i>
                </a>
            `
        ];

        updateRowInTable('#tabelaDePagamentos', 'rowPagamento_', produto.ordem - 1, rowData);
        updateTfootFromTable('#tabelaDePagamentos', [2]);
        atualizarTotal();

        $('#formaPagamento').val(null).trigger('change');
        $('#valorPagamento').val(null);
        $('#vencimentoPagamento').val(null);

        recalcularIndices('#tabelaDePagamentos', 'rowPagamento_', 'removerPagamento');
    }

    function removerPagamento(index) {
        dav.pagamentos.splice(index, 1);

        dav.pagamentos.forEach((pagamento, i) => {
            pagamento.ordem = i + 1;
        });

        removeRowFromTable('#tabelaDePagamentos', 'rowPagamento_', index);
        updateTfootFromTable('#tabelaDePagamentos', [2]);
        atualizarTotal();
        recalcularIndices('#tabelaDePagamentos', 'rowPagamento_', 'removerPagamento');
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

    function editarDav() {
        const fieldsToValidate = [
            'cliente', 'status', 'validade', 'outrasDespesas', 'frete'
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        if (dav.produtos.length === 0) {
            $('#produto').addClass('is-invalid');
            $('#produto-error').remove();
            let select2Container = $('#produto').next(".select2");
            select2Container.find(".select2-selection").addClass("is-invalid");
            select2Container.append(`<span id="produto-error" class="error invalid-feedback">Selecione pelo menos um poroduto.</span>`);
            return;

        } else {
            $('#produto').removeClass('is-invalid');
            $('#produto-error').remove();
            let select2Container = $('#produto').next(".select2");
            select2Container.find(".select2-selection").removeClass("is-invalid");
        }

        if (dav.pagamentos.length === 0) {
            $('#formaPagamento').addClass('is-invalid');
            $('#formaPagamento-error').remove();
            let select2Container = $('#formaPagamento').next(".select2");
            select2Container.find(".select2-selection").addClass("is-invalid");
            select2Container.append(`<span id="formaPagamento-error" class="error invalid-feedback">Selecione pelo menos uma forma de pagamento.</span>`);
            return;

        } else {
            $('#formaPagamento').removeClass('is-invalid');
            $('#formaPagamento-error').remove();
            let select2Container = $('#formaPagamento').next(".select2");
            select2Container.find(".select2-selection").removeClass("is-invalid");
        }

        if (numberFormat($('#valorRestante').val()) != 0 || $('#valorRestante').val() == '') {
            $('#formaPagamento').addClass('is-invalid');
            $('#formaPagamento-error').remove();
            let select2Container = $('#formaPagamento').next(".select2");
            select2Container.find(".select2-selection").addClass("is-invalid");
            select2Container.append(`<span id="formaPagamento-error" class="error invalid-feedback">Ainda possui valor restante de pagamento.</span>`);
            return;

        } else {
            $('#formaPagamento').removeClass('is-invalid');
            $('#formaPagamento-error').remove();
            let select2Container = $('#formaPagamento').next(".select2");
            select2Container.find(".select2-selection").removeClass("is-invalid");
        }

        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarDav');
        form_data.append('dav', JSON.stringify(dav));
        form_data.append('id', new URLSearchParams(window.location.search).get('i'));

        // Método post do jQuery
        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/comercial/JQ_Davs_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {
                    window.location = '<?= URL_BASE_HOST ?>/view/admin/comercial/davs.php';
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function recalcularIndices(tableId, prefix, funcao) {
        let table = $(tableId).DataTable();

        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
            let node = this.node();
            $(node).attr("id", prefix + rowIdx);
            $(node).find('.btn-action').attr("onclick", `${funcao}(${rowIdx})`);

            $(node).children('td:first').text(rowIdx + 1);
        });
        contadorProduto = table.rows().count();
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