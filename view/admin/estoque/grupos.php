<?php
$dados = array(
    'NomePagina' => 'Cadastro de Grupos',
    'MenuModulo' => 'Estoque'
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
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroGrupos"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeGrupos" class="table table-bordered">
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

<!-- Modal Cadastro de Grupos -->
<div class="modal fade" id="modal-cadastroGrupos" tabindex="-1" role="dialog" aria-hidden="true">
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
                <button onclick="cadastrarGrupo()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Grupos -->
<div class="modal fade" id="modal-edicaoGrupos" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idGrupo" name="idGrupo">

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>
                </div>
                <button onclick="editarGrupo()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    preencherTabelaDeGrupos();

    function preencherTabelaDeGrupos() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeGrupos');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_Grupos_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeGrupos tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        `
                        <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-edicaoGrupos">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarGrupo(${response.id}, '${response.ativo}')">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarGrupo(${response.id})">
                            <i class="fas fa-trash"></i>
                        </a>
                    `
                    ];

                    updateRowInTable('#tabelaDeGrupos', 'rowGrupo_', +response.id, rowData);

                });
            }
        });
    }

    function cadastrarGrupo() {

        const fieldsToValidate = [
            'descricao',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'cadastrarGrupo');
        form_data.append('descricao', $('#descricao').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Grupos_controller.php',
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
                        response.descricao,
                        `
                        <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-edicaoGrupos">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarGrupo(${response.id}, '${response.ativo}')">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarGrupo(${response.id})">
                            <i class="fas fa-trash"></i>
                        </a>
                    `
                    ];

                    updateRowInTable('#tabelaDeGrupos', 'rowGrupo_', +response.id, rowData);
                    $('#modal-cadastroGrupos').modal('hide');

                } else {
                    exibirToastr(response.msg, 'danger');
                }
            }
        });
    }

    function editarGrupo() {

        const fieldsToValidate = [
            'descricaoEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarGrupo');
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('id', $('#idGrupo').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Grupos_controller.php',
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
                        response.descricao,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-edicaoGrupos">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarGrupo(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarGrupo(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeGrupos', 'rowGrupo_', +response.id, rowData);
                    $('#modal-edicaoGrupos').modal('hide');

                } else {
                    exibirToastr(response.msg, 'danger');
                }
            }
        });
    }

    async function inativarGrupo(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar esse grupo?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarGrupo');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Grupos_controller.php',
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
                            response.descricao,
                            `
                                <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-bs-toggle="modal" data-bs-target="#modal-edicaoGrupos">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarGrupo(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarGrupo(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeGrupos', 'rowGrupo_', +response.id, rowData);

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        }
    }

    async function deletarGrupo(id) {
        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover esse grupo?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarGrupo');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Grupos_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        removeRowFromTable('#tabelaDeGrupos', 'rowGrupo_', +response.id);

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        }

    }

    $('#modal-edicaoGrupos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idGrupo').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
    });
</script>