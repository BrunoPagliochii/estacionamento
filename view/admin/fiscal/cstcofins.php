<?php
$dados = array(
    'NomePagina' => 'CST COFINS',
    'MenuModulo' => 'Fiscal'
);
?>

<?php include('../layouts/body.php') ?>


<div class="col-12">
    <div class="card card-dark">

        <div class="card-header">
            <h4><?= $dados['NomePagina'] ?></h4>
            <div class="card-header-action">

                <a href="javascript:void(0)">
                    <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroCstCofins"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeCstcofins" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
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

<?php include('../layouts/footer.php') ?>

<!-- Modal Cadastro de CSTCOFINS -->
<div class="modal fade" id="modal-cadastroCstCofins" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <div class="col-12 mb-3">
                        <label for="codigo">Código *</label>
                        <input maxlength="2" type="text" id="codigo" name="codigo" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="descricao">Descrição *</label>
                        <input maxlength="300" type="text" id="descricao" name="descricao" class="form-control">
                    </div>

                </div>
                <button id="btnCadastrarCstCofins" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar de CSTCOFINS -->
<div class="modal fade" id="modal-editarCstcofins" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idCstcofins" name="idCstcofins">

                    <div class="col-12 mb-3">
                        <label for="codigoEdt">Código *</label>
                        <input maxlength="2" type="text" id="codigoEdt" name="codigoEdt" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="300" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>

                </div>
                <button id="btnEditarCstcofins" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        preencherTabelaDeCSTCOFINS();

        function preencherTabelaDeCSTCOFINS() {

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'preencherTabelaDeCSTCOFINS');

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/model/admin/fiscal/JQ_Cstcofins_model.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(item) {

                    // Limpando o tbody antes de preencher
                    $('#tabelaDeCstcofins tbody').empty();

                    // Iterando sobre a resposta e adicionando linhas à tabela
                    item.forEach(function(response) {

                        let rowData = [
                            response.id,
                            response.codigo,
                            response.descricao,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarCstcofins" data-id="${response.id}" data-codigo="${response.codigo}" data-descricao="${response.descricao}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCstCofins(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCstCofins(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCstcofins', 'rowCstCofins_', +response.id, rowData);

                    });
                }
            });
        }

        // Executa assim que o botão de salvar for clicado
        $('#btnCadastrarCstCofins').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'codigo',
                'descricao',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'novoCstCofins');
            form_data.append('codigo', $('#codigo').val());
            form_data.append('descricao', $('#descricao').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cstcofins_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.codigo,
                            response.descricao,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarCstcofins" data-id="${response.id}" data-codigo="${response.codigo}" data-descricao="${response.descricao}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCstCofins(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCstCofins(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCstcofins', 'rowCstCofins_', +response.id, rowData);
                        $('#modal-cadastroCstCofins').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });

        // Executa assim que o botão de salvar for clicado
        $('#btnEditarCstcofins').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'idCstcofins',
                'codigoEdt',
                'descricaoEdt',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'editarCstCofins');
            form_data.append('id', $('#idCstcofins').val());
            form_data.append('codigo', $('#codigoEdt').val());
            form_data.append('descricao', $('#descricaoEdt').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cstcofins_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.codigo,
                            response.descricao,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarCstcofins" data-id="${response.id}" data-codigo="${response.codigo}" data-descricao="${response.descricao}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCstCofins(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCstCofins(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCstcofins', 'rowCstCofins_', +response.id, rowData);
                        $('#modal-editarCstcofins').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });
    });

    $('#modal-editarCstcofins').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idCstcofins').val(button.data('id'));
        $('#codigoEdt').val(button.data('codigo'));
        $('#descricaoEdt').val(button.data('descricao'));
    });

    async function inativarCstCofins(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa tributação?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarCstCofins');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cstcofins_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    exibirToastr(response.msg, response.msg);
                    if (response.status == 'success') {

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.codigo,
                            response.descricao,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarCstcofins" data-id="${response.id}" data-codigo="${response.codigo}" data-descricao="${response.descricao}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCstCofins(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCstCofins(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCstcofins', 'rowCstCofins_', +response.id, rowData);

                    }
                }
            });
        }
    }

    async function deletarCstCofins(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover essa tributação?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarCstCofins');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cstcofins_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    exibirToastr(response.msg, response.msg);
                    if (response.status == 'success') {
                        removeRowFromTable('#tabelaDeCstcofins', 'rowCstCofins_', +response.id);
                    }
                }
            });
        }
    }
</script>