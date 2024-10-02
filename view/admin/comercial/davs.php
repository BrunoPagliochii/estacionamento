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

?>

<section class="section">

    <div class="col-12">
        <div class="card card-dark">

            <div class="card-header">
                <h4><?= $dados['NomePagina'] ?></h4>
                <div class="card-header-action">
                    <a href="<?= URL_BASE_HOST ?>/view/admin/comercial/cadastrarDavs.php">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeDavs" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Forma de pagamento</th>
                                <th>Observação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $resultado = $conexao->query("SELECT
                                A.ID,
                                A.CLIENTE AS CLIENTE_ID,
                                A.FORMA_PAGAMENTO AS FORMA_PAGAMENTO_ID,
                                A.VALOR,
                                A.VENCIMENTO,
                                A.OBSERVACAO,
                                B.EMAIL,
                                B.NOME AS CLIENTE,
                                C.DESCRICAO AS FORMA_PAGAMENTO
                            FROM COM_DAVS A
                                INNER JOIN BD_PESSOAS B ON B.ID = A.CLIENTE
                                INNER JOIN FIN_FORMAS_DE_PAGAMENTO C ON C.ID = A.FORMA_PAGAMENTO
                            WHERE A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND A.ATIVO = 'S'");

                            if (!$resultado) {
                                die("Erro na consulta: " . $conexao->error);
                            }

                            while ($dadosDavs = $resultado->fetch_assoc()) { ?>

                                <tr>
                                    <td><?= $dadosDavs['ID'] ?></td>
                                    <td><?= $dadosDavs['CLIENTE'] ?></td>
                                    <td><?= number_format($dadosDavs['VALOR'], 2, ',', '.') ?></td>
                                    <td><?= date('d/m/Y', strtotime($dadosDavs['VENCIMENTO'])) ?></td>
                                    <td><?= $dadosDavs['FORMA_PAGAMENTO'] ?></td>
                                    <td><?= $dadosDavs['OBSERVACAO'] ?></td>
                                    <td>
                                        <a title="acessar" href="<?= URL_BASE_HOST ?>/view/admin/comercial/orcamento.php?token=<?= $dadosDavs['ID'] ?>&empresa=<?= $IDEMPRESAUSUARIOMODEL ?>" class="btn btn-info btn-action">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a title="editar" class="btn btn-primary btn-action mr-1"

                                            data-id="<?= $dadosDavs['ID'] ?>"
                                            data-cliente_id="<?= $dadosDavs['CLIENTE_ID'] ?>"
                                            data-forma_pagamento_id="<?= $dadosDavs['FORMA_PAGAMENTO_ID'] ?>"
                                            data-vencimento="<?= $dadosDavs['VENCIMENTO'] ?>"
                                            data-valor="<?= number_format($dadosDavs['VALOR'], 2, ',', '.') ?>"
                                            data-observacao="<?= $dadosDavs['OBSERVACAO'] ?>"

                                            data-bs-toggle="modal" data-bs-target="#modal-editarDav">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <a title="remover" onclick="deletarDav(<?= $dadosDavs['ID'] ?>)" class="btn btn-danger btn-action">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>


<!-- Modal Cadastro Davs -->
<div class="modal fade" id="modal-cadastroDav" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12 mt-2">
                        <label for="cliente" class="mb-1">Cliente *</label>
                        <select class="form-control form-control-sm select2" style="width: 100%;" name="cliente" id="cliente">
                            <option value="" selected disabled>Selecione...</option>
                            <?php foreach ($clientesArray as $cliente) { ?>

                                <option value="<?= $cliente['ID'] ?>"><?= $cliente['NOME'] ?> (<?= $cliente['DOCUMENTO'] ?>)</option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="formaPagamento" class="mb-1">Forma de Pagamento: *</label>
                        <select class="form-control form-control-sm select2" style="width: 100%;" name="formaPagamento" id="formaPagamento">
                            <option value="" selected disabled>Selecione...</option>
                            <?php foreach ($formasDePagamento as $formaPagamento) { ?>

                                <option value="<?= $formaPagamento['ID'] ?>"><?= $formaPagamento['DESCRICAO'] ?></option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="vencimento">Vencimento *</label>
                        <input type="date" id="vencimento" name="vencimento" class="form-control">
                    </div>

                    <div class="col-12 mt-2">
                        <label for="valor">Valor *</label>
                        <input type="text" id="valor" value="0,00" name="valor" class="form-control">
                    </div>

                    <div class="col-12 mt-2">
                        <label for="observacao"></label>
                        <textarea id="observacao" name="observacao" class="form-control"></textarea>
                    </div>

                </div>
                <button onclick="cadastrarDav()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Davs -->
<div class="modal fade" id="modal-editarDav" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" id="idDav" name="idDav">

                    <div class="col-12 mt-2">
                        <label for="clienteEdt" class="mb-1">Cliente *</label>
                        <select class="form-control form-control-sm select2" style="width: 100%;" name="clienteEdt" id="clienteEdt">
                            <option value="" selected disabled>Selecione...</option>
                            <?php foreach ($clientesArray as $cliente) { ?>

                                <option value="<?= $cliente['ID'] ?>"><?= $cliente['NOME'] ?> (<?= $cliente['DOCUMENTO'] ?>)</option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="formaPagamentoEdt" class="mb-1">Forma de Pagamento: *</label>
                        <select class="form-control form-control-sm select2" style="width: 100%;" name="formaPagamentoEdt" id="formaPagamentoEdt">
                            <option value="" selected disabled>Selecione...</option>
                            <?php foreach ($formasDePagamento as $formaPagamento) { ?>

                                <option value="<?= $formaPagamento['ID'] ?>"><?= $formaPagamento['DESCRICAO'] ?></option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="vencimentoEdt">Vencimento *</label>
                        <input type="date" id="vencimentoEdt" name="vencimentoEdt" class="form-control">
                    </div>

                    <div class="col-12 mt-2">
                        <label for="valorEdt">Valor *</label>
                        <input type="text" id="valorEdt" value="0,00" name="valorEdt" class="form-control">
                    </div>

                    <div class="col-12 mt-2">
                        <label for="observacaoEdt">Observação *</label>
                        <textarea id="observacaoEdt" name="observacaoEdt" class="form-control"></textarea>
                    </div>
                </div>
                <button onclick="editarDav()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        initDataTable('tabelaDeDavs');
    });

    function cadastrarDav() {

        const fieldsToValidate = [
            'cliente', 'formaPagamento', 'vencimento', 'valor',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarDav');
        form_data.append('cliente', $('#cliente').val());
        form_data.append('formaPagamento', $('#formaPagamento').val());
        form_data.append('vencimento', $('#vencimento').val());
        form_data.append('valor', $('#valor').val());
        form_data.append('observacao', $('#observacao').val());

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
                    location.reload();
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarDav() {

        const fieldsToValidate = [
            'clienteEdt', 'formaPagamentoEdt', 'vencimentoEdt', 'valorEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'editarDav');
        form_data.append('cliente', $('#clienteEdt').val());
        form_data.append('formaPagamento', $('#formaPagamentoEdt').val());
        form_data.append('vencimento', $('#vencimentoEdt').val());
        form_data.append('valor', $('#valorEdt').val());
        form_data.append('observacao', $('#observacaoEdt').val());
        form_data.append('id', $('#idDav').val());

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
                    location.reload();
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function deletarDav(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja excluir esse orçamento?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarDav');
            form_data.append('id', id);

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
                        location.reload();
                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    $('#modal-editarDav').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idDav').val(button.data('id'));

        $('#clienteEdt').val(button.data('cliente_id')).trigger('change');
        $('#formaPagamentoEdt').val(button.data('forma_pagamento_id')).trigger('change');
        $('#vencimentoEdt').val(button.data('vencimento'));
        $('#valorEdt').val(button.data('valor'));
        $('#observacaoEdt').val(button.data('observacao'));
    });

    $('#valor').maskMoney({
        thousands: '.',
        decimal: ',',
        prefix: 'R$ ',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });
    $('#valorEdt').maskMoney({
        thousands: '.',
        decimal: ',',
        prefix: 'R$ ',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });
</script>