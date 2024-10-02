<?php
$dados = array(
    'NomePagina' => 'Formas de Pagamento',
    'MenuModulo' => 'Financeiro'
);
?>


<?php include('../layouts/body.php') ?>

<section class="section">

    <div class="col-12">
        <div class="card card-dark">

            <div class="card-header">
                <h4><?= $dados['NomePagina'] ?></h4>
                <div class="card-header-action">
                    <a href="javascript:void(0)">
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroFormasDePagamento"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeFormasDePagamento" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>


<!-- Modal Cadastro Formas de pagamento -->
<div class="modal fade" id="modal-cadastroFormasDePagamento" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <div class="col-12">
                        <label for="descricao">Descrição *</label>
                        <input maxlength="45" type="text" id="descricao" name="descricao" class="form-control">
                    </div>

                </div>
                <button onclick="cadastrarFormasDePagamento()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Formas de pagamento -->
<div class="modal fade" id="modal-editarFormasDePagamento" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idFormaDePagamento" name="idFormaDePagamento">

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>

                </div>
                <button onclick="editarFormaDePagamento()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    preencherTabelaDeFormasDePagamento();

    function preencherTabelaDeFormasDePagamento() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeFormasDePagamento');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/financeiro/JQ_Financeiro_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeFormasDePagamento tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-editarFormasDePagamento">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarFormaPagamento(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarFormaPagamento(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeFormasDePagamento', 'rowFormaPagamento_', +response.id, rowData);

                });
            }
        });
    }

    function cadastrarFormasDePagamento() {

        const fieldsToValidate = [
            'descricao',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarFormasDePagamento');
        form_data.append('descricao', $('#descricao').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/financeiro/JQ_FormasDePagamento_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {

                    let rowData = [
                        response.id,
                        response.descricao,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-editarFormasDePagamento">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarFormaPagamento(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarFormaPagamento(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeFormasDePagamento', 'rowFormaPagamento_', +response.id, rowData);
                    $('#modal-cadastroFormasDePagamento').modal('hide');

                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarFormaDePagamento() {
        const fieldsToValidate = [
            'descricaoEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'editarFormaDePagamento');
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('id', $('#idFormaDePagamento').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/financeiro/JQ_FormasDePagamento_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                if (response.status == 'success') {

                    let rowData = [
                        response.id,
                        response.descricao,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-editarFormasDePagamento">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarFormaPagamento(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarFormaPagamento(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeFormasDePagamento', 'rowFormaPagamento_', +response.id, rowData);
                    $('#modal-editarFormasDePagamento').modal('hide');

                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function inativarFormaPagamento(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa forma de pagamento?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarFormaPagamento');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/financeiro/JQ_FormasDePagamento_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        let rowData = [
                            response.id,
                            response.descricao,
                            `
                                <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-editarFormasDePagamento">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarFormaPagamento(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarFormaPagamento(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeFormasDePagamento', 'rowFormaPagamento_', +response.id, rowData);

                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    async function deletarFormaPagamento(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover essa forma de pagamento?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarFormaPagamento');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/financeiro/JQ_FormasDePagamento_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        removeRowFromTable('#tabelaDeFormasDePagamento', 'rowFormaPagamento_', +response.id);

                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    $('#modal-editarFormasDePagamento').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idFormaDePagamento').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
    });
</script>