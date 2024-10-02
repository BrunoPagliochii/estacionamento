<?php
$dados = array(
    'NomePagina' => 'Gerenciar Produtos',
    'MenuModulo' => 'Estoque'
);
?>

<?php include('../layouts/body.php') ?>


<?php
$tiposMercadoriaArray = array();
$resultado = $conexao->query("SELECT ID, DESCRICAO FROM EST_TIPOS_MERCADORIA WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $tiposMercadoriaArray[] = $row;
}

$unidadeMedidaArray = array();
$resultado = $conexao->query("SELECT ID, SIGLA, DESCRICAO FROM EST_UNIDADES_MEDIDA WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $unidadeMedidaArray[] = $row;
}

$gruposArray = array();
$resultado = $conexao->query("SELECT ID, DESCRICAO FROM EST_GRUPOS WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $gruposArray[] = $row;
}

$coresArray = array();
$resultado = $conexao->query("SELECT ID, DESCRICAO FROM EST_CORES WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $coresArray[] = $row;
}

$tamanhosArray = array();
$resultado = $conexao->query("SELECT ID, SIGLA, DESCRICAO FROM EST_TAMANHOS WHERE ATIVO = 'S' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $tamanhosArray[] = $row;
}

?>

<section class="section">

    <div class="col-12">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL_BASE_HOST ?>/view/admin/estoque/produtos.php">Produtos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Produto: #<?= $_GET['i'] ?></li>
            </ol>
        </nav>
    </div>

    <div class="col-12">
        <div class="card card-dark">
            <div class="card-header">

                <ul class="nav nav-pills" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-dadosGerais" role="tab" aria-selected="true">Geral</a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-historico" role="tab" aria-selected="true">Histórico</a>
                    </li>

                </ul>

            </div>
            <div class="card-body">

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-dadosGerais" role="tabpanel">

                        <div class="row">

                            <input type="hidden" id="idProduto" name="idProduto">
                            <input type="hidden" id="quantidadeAntiga" name="quantidadeAntiga">

                            <div class="col-12 col-xl-4">
                                <label for="descricaoEdt">Descrição *</label>
                                <input type="text" id="descricaoEdt" name="descricaoEdt" placeholder="Digite o Nome do Produto" maxlength="60" class="form-control">
                            </div>

                            <div class="col-12 col-xl-3">
                                <label for="quantidadeEdt">Quantidade *</label>
                                <input type="text" id="quantidadeEdt" name="quantidadeEdt" value="0,00" class="form-control quilo">
                            </div>

                            <div class="col-12 col-xl-2">
                                <label for="valorVendaEdt">Valor de Venda *</label>
                                <input type="text" id="valorVendaEdt" name="valorVendaEdt" value="0,00" class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-3">
                                <label for="pesoEdt">Peso</label>
                                <input type="text" id="pesoEdt" name="pesoEdt" placeholder="Digite o peso" class="form-control quilo">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="custoEdt">Valor de Custo *</label>
                                <input type="text" id="custoEdt" name="custoEdt" value="0,00" class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="custoUltimaCompraEdt">Custo da Última Compra</label>
                                <input type="text" id="custoUltimaCompraEdt" name="custoUltimaCompraEdt" value="0,00" disabled class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="custoMedio">Custo Médio</label>
                                <input type="text" id="custoMedio" name="custoMedio" disabled value="0,00" class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="tipoMercadoriaEdt" class="mb-1">Tipo de Mercadoria: *</label>
                                <select class="form-control form-control-sm select2 w-100" name="tipoMercadoriaEdt" id="tipoMercadoriaEdt">
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($tiposMercadoriaArray as $tiposMercadoria) { ?>

                                        <option value="<?= $tiposMercadoria['ID'] ?>"><?= $tiposMercadoria['DESCRICAO'] ?> </option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="unidadeMedidaEdt" class="mb-1">Unidade de Medida: *</label>
                                <select class="form-control form-control-sm select2 w-100" name="unidadeMedidaEdt" id="unidadeMedidaEdt">
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($unidadeMedidaArray as $unidadeMedida) { ?>

                                        <option value="<?= $unidadeMedida['ID'] ?>"><?= $unidadeMedida['DESCRICAO'] ?> (<?= $unidadeMedida['SIGLA'] ?>)</option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="grupoEdt" class="mb-1">Grupo:</label>
                                <select class="form-control form-control-sm select2 w-100" name="grupoEdt" id="grupoEdt">
                                    <option value="" selected>Selecione...</option>
                                    <?php foreach ($gruposArray as $grupo) { ?>

                                        <option value="<?= $grupo['ID'] ?>"><?= $grupo['DESCRICAO'] ?> </option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-6 mt-2">
                                <label for="corEdt" class="mb-1">Cor:</label>
                                <select class="form-control form-control-sm select2 w-100" name="corEdt" id="corEdt">
                                    <option value="" selected>Selecione...</option>
                                    <?php foreach ($coresArray as $cor) { ?>

                                        <option value="<?= $cor['ID'] ?>"><?= $cor['DESCRICAO'] ?> </option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-6 mt-2">
                                <label for="tamanhoEdt" class="mb-1">Tamanho:</label>
                                <select class="form-control form-control-sm select2 w-100" name="tamanhoEdt" id="tamanhoEdt">
                                    <option value="" selected>Selecione...</option>
                                    <?php foreach ($tamanhosArray as $tamanho) { ?>

                                        <option value="<?= $tamanho['ID'] ?>"><?= $tamanho['DESCRICAO'] ?> (<?= $tamanho['SIGLA'] ?>)</option>

                                    <?php } ?>
                                </select>
                            </div>

                        </div>

                        <button onclick="editarProduto()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>

                    </div>


                    <div class="tab-pane fade show" id="tab-historico" role="tabpanel">
                        <div class="table-responsive">
                            <table id="tabelaDeHistorico" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descrição</th>
                                        <th>Quantidade</th>
                                        <th>Custo</th>
                                        <th>Custo unitário</th>
                                        <th>Incluidopor</th>
                                        <th>Incluidoem</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>

<script>
    $(function() {

        // Verifica se os parâmetros estão presentes e não estão vazios
        if (!(new URLSearchParams(window.location.search).get('i'))) {
            alert('Dados inválidos');
            window.location = '<?= URL_BASE_HOST ?>/view/admin/estoque/produtos.php';
        } else {
            buscarProduto(new URLSearchParams(window.location.search).get('i'));
        }
    });

    function buscarProduto(id) {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'buscarProduto');
        form_data.append('id', id);

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_Produtos_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {

                if (response.status == 'success') {

                    $('#idProduto').val(response.id);
                    $('#quantidadeAntiga').val(response.quantidade);
                    $('#descricaoEdt').val(response.descricao);
                    $('#quantidadeEdt').val(response.quantidade);
                    $('#valorVendaEdt').val(response.valor_venda);
                    $('#pesoEdt').val(response.peso);
                    $('#custoEdt').val(response.custo);
                    $('#custoMedioEdt').val(response.custo_medio);
                    $('#custoUltimaCompraEdt').val(response.custo_ultima_compra);
                    $('#tipoMercadoriaEdt').val(response.tipo_mercadoria_id).trigger("change");
                    $('#unidadeMedidaEdt').val(response.unidade_medida_id).trigger("change");
                    $('#grupoEdt').val(response.grupo_id).trigger("change");
                    $('#corEdt').val(response.cor_id).trigger("change");
                    $('#tamanhoEdt').val(response.tamanho_id).trigger("change");

                } else {
                    window.location = '<?= URL_BASE_HOST ?>/view/admin/estoque/produtos.php';
                }
            }
        });
    }

    // Executa assim que o botão de salvar for clicado
    function editarProduto() {
        const fieldsToValidate = [
            'descricaoEdt', 'quantidadeEdt', 'custoEdt', 'valorVendaEdt', 'tipoMercadoriaEdt', 'unidadeMedidaEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarProduto');
        form_data.append('idProduto', $('#idProduto').val());
        form_data.append('quantidadeAntiga', $('#quantidadeAntiga').val());
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('custo', $('#custoEdt').val());
        form_data.append('quantidade', $('#quantidadeEdt').val());
        form_data.append('valorVenda', $('#valorVendaEdt').val());
        form_data.append('peso', $('#pesoEdt').val());
        form_data.append('tipoMercadoria', $('#tipoMercadoriaEdt').val());
        form_data.append('unidadeMedida', $('#unidadeMedidaEdt').val());
        form_data.append('grupo', $('#grupoEdt').val());
        form_data.append('cor', $('#corEdt').val());
        form_data.append('tamanho', $('#tamanhoEdt').val());

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

    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"][href="#tab-historico"]', function() {
        buscarHistoricoProduto();
    });

    function buscarHistoricoProduto() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'buscarHistoricoProduto');
        form_data.append('id', new URLSearchParams(window.location.search).get('i'));

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_Produtos_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeHistorico tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        response.quantidade,
                        response.custo,
                        response.custo_unitario,
                        response.incluidopor,
                        response.incluidoem,
                    ];

                    updateRowInTable('#tabelaDeHistorico', 'historico_', +response.id, rowData);

                });
            }
        });

    }

    $('.moeda').maskMoney({
        thousands: '.',
        decimal: ',',
        prefix: 'R$ ',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });
    $('.quilo').maskMoney({
        thousands: '.',
        decimal: ',',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });
</script>