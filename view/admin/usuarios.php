<?php
$dados = array(
    'NomePagina' => 'Cadastro de Usuários',
    'MenuModulo' => 'Geral'
);
?>

<?php include('layouts/body.php') ?>


<div class="col-12">
    <div class="card card-dark">

        <div class="card-header">
            <h4><?= $dados['NomePagina'] ?></h4>
            <div class="card-header-action">

                <a href="javascript:void(0)">
                    <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroUsuarios"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeUsuarios" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('layouts/footer.php') ?>

<div class="modal fade" id="modal-cadastroUsuarios" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="email">E-mail *</label>
                        <input maxlength="45" type="email" id="email" name="email" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="senha">Senha *</label>
                        <input maxlength="45" type="password" id="senha" name="senha" class="form-control">
                    </div>

                </div>
                <button id="btnCadastrarUsuario" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-editarUsuarios" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden" id="idUsuario" name="idUsuario">

                    <div class="col-12">
                        <label for="nomeEdt">Nome *</label>
                        <input type="text" id="nomeEdt" name="nomeEdt" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="emailEdt">E-mail *</label>
                        <input maxlength="45" type="email" id="emailEdt" name="emailEdt" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="senhaEdt">SenhaEdt *</label>
                        <input maxlength="45" type="password" id="senhaEdt" name="senhaEdt" class="form-control">
                    </div>


                </div>
                <button id="btnEditarUsuario" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        preencherTabelaDeUsuarios();

        function preencherTabelaDeUsuarios() {

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'preencherTabelaDeUsuarios');

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/model/admin/JQ_Usuarios_model.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(item) {

                    // Limpando o tbody antes de preencher
                    $('#tabelaDeUsuarios tbody').empty();

                    // Iterando sobre a resposta e adicionando linhas à tabela
                    item.forEach(function(response) {

                        let rowData = [
                            response.id,
                            response.nome,
                            response.email,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarUsuarios" data-id="${response.id}" data-nome="${response.nome}" data-email="${response.nome}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarUsuario(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeUsuarios', 'rowUsuarios_', +response.id, rowData);

                    });
                }
            });
        }

        // Executa assim que o botão de salvar for clicado
        $('#btnCadastrarUsuario').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'nome',
                'email',
                'senha',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'novoUsuario');
            form_data.append('nome', $('#nome').val());
            form_data.append('email', $('#email').val());
            form_data.append('senha', $('#senha').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Usuarios_controller.php',
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
                            response.nome,
                            response.email,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarUsuarios" data-id="${response.id}" data-nome="${response.nome}" data-email="${response.nome}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarUsuario(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeUsuarios', 'rowUsuarios_', +response.id, rowData);
                        $('#modal-cadastroUsuarios').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });

        // Executa assim que o botão de salvar for clicado
        $('#btnEditarUsuario').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'idUsuario',
                'nomeEdt',
                'emailEdt',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'editarUsuario');
            form_data.append('idUsuario', $('#idUsuario').val());
            form_data.append('nome', $('#nomeEdt').val());
            form_data.append('email', $('#emailEdt').val());
            form_data.append('senha', $('#senhaEdt').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Usuarios_controller.php',
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
                            response.nome,
                            response.email,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarUsuarios" data-id="${response.id}" data-nome="${response.nome}" data-email="${response.nome}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarUsuario(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeUsuarios', 'rowUsuarios_', +response.id, rowData);
                        $('#modal-editarUsuarios').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });
    });

    $('#modal-editarUsuarios').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idUsuario').val(button.data('id'));
        $('#nomeEdt').val(button.data('nome'));
        $('#emailEdt').val(button.data('email'));
    });

    async function inativarUsuario(ID, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa usuário?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarUsuario');
            form_data.append('ID', ID);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Usuarios_controller.php',
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
                            response.nome,
                            response.email,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-editarUsuarios" data-id="${response.id}" data-nome="${response.nome}" data-email="${response.email}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarUsuario(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeUsuarios', 'rowUsuarios_', +response.id, rowData);

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        }
    }
</script>