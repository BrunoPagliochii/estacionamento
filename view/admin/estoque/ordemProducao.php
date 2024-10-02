<?php
$dados = array(
    'NomePagina' => 'Ordem de composição: #' . $_GET['i'],
    'MenuModulo' => 'Estoque'
);
?>


<?php include('../layouts/body.php') ?>

<?php
$produtosArray = array();
$resultado = $conexao->query("SELECT 
    A.ID, 
    A.DESCRICAO,
    B.SIGLA AS UNIDADE_MEDIDA
FROM EST_PRODUTOS A 
    INNER JOIN EST_UNIDADES_MEDIDA B ON B.ID = A.UNIDADE_MEDIDA 
WHERE A.ATIVO = 'S' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $produtosArray[] = $row;
}

$ordemComposicaoAtual = array();
$resultado = $conexao->query("SELECT
    A.ID, 
    A.QUANTIDADE, 
    A.INCLUIDOEM, 
    B.NOME 
FROM EST_ORDEM_COMPOSICAO_HISTORICO A 
    INNER JOIN BD_USUARIOS B ON B.ID = A.INCLUIDOPOR
WHERE A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $ordemComposicaoAtual[] = $row;
}

$produtosUsadosArray = array();
$resultado = $conexao->query("SELECT
    A.ID, 
    A.QUANTIDADE,
    B.DESCRICAO,
    A.PRODUTO AS PRODUTO_ID,
    (SELECT SUM(QUANTIDADE) FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.PRODUTO) AS QUANTIDADE_ESTOQUE,
    C.SIGLA AS UNIDADE_MEDIDA
FROM EST_ORDEM_COMPOSICAO_PRODUTOS A 
    INNER JOIN EST_PRODUTOS B ON B.ID = A.PRODUTO
    INNER JOIN EST_UNIDADES_MEDIDA C ON C.ID = B.UNIDADE_MEDIDA
WHERE B.ATIVO = 'S' AND A.TIPO = 2 AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND A.ORDEM = " . $_GET['i']);
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $produtosUsadosArray[] = $row;
}

$produtosGeradosArray = array();
$resultado = $conexao->query("SELECT
    A.ID,   
    A.QUANTIDADE,
    B.DESCRICAO,
    A.PRODUTO AS PRODUTO_ID,
    (SELECT SUM(QUANTIDADE) FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.PRODUTO) AS QUANTIDADE_ESTOQUE,
    C.SIGLA AS UNIDADE_MEDIDA
FROM EST_ORDEM_COMPOSICAO_PRODUTOS A 
    INNER JOIN EST_PRODUTOS B ON B.ID = A.PRODUTO
    INNER JOIN EST_UNIDADES_MEDIDA C ON C.ID = B.UNIDADE_MEDIDA
WHERE B.ATIVO = 'S' AND A.TIPO = 1 AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND A.ORDEM = " . $_GET['i']);
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $produtosGeradosArray[] = $row;
}
?>
<section class="section">

    <div class="col-12">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL_BASE_HOST ?>/view/admin/estoque/composicao.php">Composição</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= 'Ordem de composição: #' . $_GET['i'] ?></li>
            </ol>
        </nav>

        <div class="row">

            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4>Processos realizados</h4>
                        <div class="card-header-action">
                            <button data-bs-toggle="modal" data-bs-target="#modal-processarComposicao" class="btn btn-success">Processar&nbsp;&nbsp;<i class="fas fa-power-off"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaOrdensDeComposicao" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Quantidade</th>
                                        <th>Quando</th>
                                        <th>Quem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ordemComposicaoAtual as $ordem) { ?>

                                        <tr>

                                            <td><?= $ordem['ID'] ?></td>
                                            <td><?= number_format($ordem['QUANTIDADE'], 2, ',', '.') ?></td>
                                            <td><?= $ordem['INCLUIDOEM'] ?></td>
                                            <td><?= $ordem['NOME'] ?></td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4>Produtos usados</h4>
                        <div class="card-header-action">
                            <a href="javascript:void(0)">
                                <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-adicionarProdutoUsado"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaProdutosUsados" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descrição</th>
                                        <th>Quantidade em estoque</th>
                                        <th>Quantidade Usada</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtosUsadosArray as $produtoUsado) { ?>

                                        <tr>

                                            <td><?= $produtoUsado['ID'] ?></td>
                                            <td><?= $produtoUsado['DESCRICAO'] ?></td>
                                            <td><?= number_format($produtoUsado['QUANTIDADE_ESTOQUE'], 2, ',', '.') ?></td>
                                            <td><?= number_format($produtoUsado['QUANTIDADE'], 2, ',', '.') ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-action mr-1" data-bs-toggle="modal" data-bs-target="#modal-editarProdutoUsado"

                                                    data-id="<?= $produtoUsado['ID'] ?>"
                                                    data-produto="<?= $produtoUsado['PRODUTO_ID'] ?>"
                                                    data-quantidade="<?= $produtoUsado['QUANTIDADE'] ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a title="remover" onclick="deletarProdutoUsado(<?= $produtoUsado['ID'] ?>)" class="btn btn-danger btn-action">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4>Produtos gerados</h4>
                        <div class="card-header-action">
                            <a href="javascript:void(0)">
                                <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-adicionarProdutoGerado"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaProdutosGerados" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descrição</th>
                                        <th>Quantidade em estoque</th>
                                        <th>Quantidade Gerada</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtosGeradosArray as $produtoGerado) { ?>

                                        <tr>

                                            <td><?= $produtoGerado['ID'] ?></td>
                                            <td><?= $produtoGerado['DESCRICAO'] ?></td>
                                            <td><?= number_format($produtoGerado['QUANTIDADE_ESTOQUE'], 2, ',', '.') ?></td>
                                            <td><?= number_format($produtoGerado['QUANTIDADE'], 2, ',', '.') ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-action mr-1" data-bs-toggle="modal" data-bs-target="#modal-editarProdutoGerado"

                                                    data-id="<?= $produtoGerado['ID'] ?>"
                                                    data-produto="<?= $produtoGerado['PRODUTO_ID'] ?>"
                                                    data-quantidade="<?= $produtoGerado['QUANTIDADE'] ?>">

                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a title="remover" onclick="deletarProdutoGerado(<?= $produtoGerado['ID'] ?>)" class="btn btn-danger btn-action">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>

<!-- Modal Adcionar produto a ser usado -->
<div class="modal fade" id="modal-adicionarProdutoUsado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12">
                        <label for="produtoUsado" class="mb-1">Produto: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="produtoUsado" id="produtoUsado">
                            <option value="" selected>Selecione...</option>
                            <?php foreach ($produtosArray as $produto) { ?>

                                <option value="<?= $produto['ID'] ?>"><?= $produto['DESCRICAO'] ?>&nbsp;(<?= $produto['UNIDADE_MEDIDA'] ?>)</option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="quantidadeUsado">Quantidade que será perdida: *</label>
                        <input type="text" id="quantidadeUsado" name="quantidadeUsado" value="0,00" class="form-control quantidade">
                    </div>

                </div>
                <button onclick="adicionarProdutoUsado()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adcionar produto a ser gerado -->
<div class="modal fade" id="modal-adicionarProdutoGerado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12">
                        <label for="produtoGerado" class="mb-1">Produto: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="produtoGerado" id="produtoGerado">
                            <option value="" selected>Selecione...</option>
                            <?php foreach ($produtosArray as $produto) { ?>

                                <option value="<?= $produto['ID'] ?>"><?= $produto['DESCRICAO'] ?>&nbsp;(<?= $produto['UNIDADE_MEDIDA'] ?>)</option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="quantidadeGerado">Quantidade que será gerada: *</label>
                        <input type="text" id="quantidadeGerado" name="quantidadeGerado" value="0,00" class="form-control quantidade">
                    </div>

                </div>
                <button onclick="adicionarProdutoGerado()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar produto a ser usado -->
<div class="modal fade" id="modal-editarProdutoUsado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" id="idProdutoUsadoEdt" name="idProdutoUsadoEdt">

                    <div class="col-12">
                        <label for="produtoUsadoEdt" class="mb-1">Produto: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="produtoUsadoEdt" id="produtoUsadoEdt">
                            <option value="" selected>Selecione...</option>
                            <?php foreach ($produtosArray as $produto) { ?>

                                <option value="<?= $produto['ID'] ?>"><?= $produto['DESCRICAO'] ?>&nbsp;(<?= $produto['UNIDADE_MEDIDA'] ?>)</option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="quantidadeUsadoEdt">Quantidade que será perdida: *</label>
                        <input type="text" id="quantidadeUsadoEdt" name="quantidadeUsadoEdt" class="form-control quantidade">
                    </div>

                </div>
                <button onclick="editarProdutoUsado()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar produto a ser gerado -->
<div class="modal fade" id="modal-editarProdutoGerado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" id="idProdutoGeradoEdt" name="idProdutoGeradoEdt">

                    <div class="col-12">
                        <label for="produtoGeradoEdt" class="mb-1">Produto: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="produtoGeradoEdt" id="produtoGeradoEdt">
                            <option value="" selected>Selecione...</option>
                            <?php foreach ($produtosArray as $produto) { ?>

                                <option value="<?= $produto['ID'] ?>"><?= $produto['DESCRICAO'] ?>&nbsp;(<?= $produto['UNIDADE_MEDIDA'] ?>)</option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="quantidadeGeradoEdt">Quantidade que será perdida: *</label>
                        <input type="text" id="quantidadeGeradoEdt" name="quantidadeGeradoEdt" class="form-control quantidade">
                    </div>

                </div>
                <button onclick="editarProdutoGerado()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar produto a ser gerado -->
<div class="modal fade" id="modal-processarComposicao" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12 mt-2">
                        <label for="quantidadeProcessar">Processar quantas vezes? *</label>
                        <input type="number" id="quantidadeProcessar" name="quantidadeProcessar" min="1" value="1" class="form-control">
                    </div>

                </div>
                <button onclick="processarComposicao()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        initDataTable('tabelaOrdensDeComposicao');
        initDataTable('tabelaProdutosUsados');
        initDataTable('tabelaProdutosGerados');

        // Verifica se os parâmetros estão presentes e não estão vazios
        if (!(new URLSearchParams(window.location.search).get('i'))) {
            alert('Dados inválidos');
            window.location = '<?= URL_BASE_HOST ?>/view/admin/estoque/composicao.php';
        }
    });

    function adicionarProdutoUsado() {

        const fieldsToValidate = [
            'produtoUsado', 'quantidadeUsado',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'adicionarProdutoUsado');
        form_data.append('produto', $('#produtoUsado').val());
        form_data.append('quantidade', $('#quantidadeUsado').val());
        form_data.append('ordem', new URLSearchParams(window.location.search).get('i'));

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function adicionarProdutoGerado() {

        const fieldsToValidate = [
            'produtoGerado', 'quantidadeGerado',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'adicionarProdutoGerado');
        form_data.append('produto', $('#produtoGerado').val());
        form_data.append('quantidade', $('#quantidadeGerado').val());
        form_data.append('ordem', new URLSearchParams(window.location.search).get('i'));
        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarProdutoUsado() {

        const fieldsToValidate = [
            'produtoUsadoEdt', 'quantidadeUsadoEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'editarProdutoUsado');
        form_data.append('produto', $('#produtoUsadoEdt').val());
        form_data.append('quantidade', $('#quantidadeUsadoEdt').val());
        form_data.append('id', $('#idProdutoUsadoEdt').val());
        form_data.append('ordem', new URLSearchParams(window.location.search).get('i'));

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarProdutoGerado() {

        const fieldsToValidate = [
            'produtoGeradoEdt', 'quantidadeGeradoEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'editarProdutoGerado');
        form_data.append('produto', $('#produtoGeradoEdt').val());
        form_data.append('quantidade', $('#quantidadeGeradoEdt').val());
        form_data.append('id', $('#idProdutoGeradoEdt').val());
        form_data.append('ordem', new URLSearchParams(window.location.search).get('i'));

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function deletarProdutoUsado(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover esse produto?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarProdutoUsado');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    async function deletarProdutoGerado(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover esse produto?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarProdutoGerado');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    async function processarComposicao() {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja processar essa ordem de composicao?');

        if (confirmed) {

            const fieldsToValidate = [
                'quantidadeProcessar'
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            const form_data = new FormData();
            form_data.append('JQueryFunction', 'processarComposicao');
            form_data.append('quantidade', $('#quantidadeProcessar').val());
            form_data.append('id', new URLSearchParams(window.location.search).get('i'));

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    $('.quantidade').maskMoney({
        thousands: '.',
        decimal: ',',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });

    $('#modal-editarProdutoUsado').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idProdutoUsadoEdt').val(button.data('id'));
        $('#produtoUsadoEdt').val(button.data('produto')).trigger("change");
        $('#quantidadeUsadoEdt').val(button.data('quantidade'));
    });
    $('#modal-editarProdutoGerado').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idProdutoGeradoEdt').val(button.data('id'));
        $('#produtoGeradoEdt').val(button.data('produto')).trigger("change");
        $('#quantidadeGeradoEdt').val(button.data('quantidade'));
    });
</script>