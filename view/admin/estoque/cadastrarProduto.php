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
                <li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
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

                </ul>

            </div>
            <div class="card-body">

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-dadosGerais" role="tabpanel">

                        <div class="row">

                            <div class="col-12 col-xl-4">
                                <label for="descricao">Descrição *</label>
                                <input type="text" id="descricao" placeholder="Digite o Nome do Produto" name="descricao" maxlength="60" class="form-control">
                            </div>

                            <div class="col-12 col-xl-3">
                                <label for="quantidade">Quantidade *</label>
                                <input type="text" id="quantidade" name="quantidade" value="0,00" class="form-control quilo">
                            </div>

                            <div class="col-12 col-xl-2">
                                <label for="valorVenda">Valor de Venda *</label>
                                <input type="text" id="valorVenda" name="valorVenda" value="0,00" class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-3">
                                <label for="peso">Peso</label>
                                <input type="text" id="peso" placeholder="Digite o peso" name="peso" class="form-control quilo">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="custo">Valor de Custo *</label>
                                <input type="text" id="custo" name="custo" value="0,00" class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="custoUltimaCompra">Custo da Última Compra</label>
                                <input type="text" id="custoUltimaCompra" name="custoUltimaCompra" value="0,00" disabled class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="custoMedio">Custo Médio</label>
                                <input type="text" id="custoMedio" name="custoMedio" disabled value="0,00" class="form-control moeda">
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="tipoMercadoria" class="mb-1">Tipo de Mercadoria: *</label>
                                <select class="form-control form-control-sm select2 w-100" name="tipoMercadoria" id="tipoMercadoria">
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($tiposMercadoriaArray as $tiposMercadoria) { ?>

                                        <option value="<?= $tiposMercadoria['ID'] ?>"><?= $tiposMercadoria['DESCRICAO'] ?> </option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="unidadeMedida" class="mb-1">Unidade de Medida: *</label>
                                <select class="form-control form-control-sm select2 w-100" name="unidadeMedida" id="unidadeMedida">
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($unidadeMedidaArray as $unidadeMedida) { ?>

                                        <option value="<?= $unidadeMedida['ID'] ?>"><?= $unidadeMedida['DESCRICAO'] ?> (<?= $unidadeMedida['SIGLA'] ?>)</option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-4 mt-2">
                                <label for="grupo" class="mb-1">Grupo:</label>
                                <select class="form-control form-control-sm select2 w-100" name="grupo" id="grupo">
                                    <option value="" selected>Selecione...</option>
                                    <?php foreach ($gruposArray as $grupo) { ?>

                                        <option value="<?= $grupo['ID'] ?>"><?= $grupo['DESCRICAO'] ?> </option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-6 mt-2">
                                <label for="cor" class="mb-1">Cor:</label>
                                <select class="form-control form-control-sm select2 w-100" name="cor" id="cor">
                                    <option value="" selected>Selecione...</option>
                                    <?php foreach ($coresArray as $cor) { ?>

                                        <option value="<?= $cor['ID'] ?>"><?= $cor['DESCRICAO'] ?> </option>

                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-xl-6 mt-2">
                                <label for="tamanho" class="mb-1">Tamanho:</label>
                                <select class="form-control form-control-sm select2 w-100" name="tamanho" id="tamanho">
                                    <option value="" selected>Selecione...</option>
                                    <?php foreach ($tamanhosArray as $tamanho) { ?>

                                        <option value="<?= $tamanho['ID'] ?>"><?= $tamanho['DESCRICAO'] ?> (<?= $tamanho['SIGLA'] ?>)</option>

                                    <?php } ?>
                                </select>
                            </div>

                        </div>

                    </div>

                </div>

                <button onclick="cadastrarProduto()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>

            </div>
        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>

<script>
    // Executa assim que o botão de salvar for clicado
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