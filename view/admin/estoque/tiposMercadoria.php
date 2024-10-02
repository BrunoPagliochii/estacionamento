<?php
$dados = array(
    'NomePagina' => 'Cadastro de Tipos de Mercadoria',
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
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroTiposDeMercadoria"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeTiposDeMercadoria" class="table table-bordered">
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

<!-- Modal Cadastro de Tipos de Mercadoria -->
<div class="modal fade" id="modal-cadastroTiposDeMercadoria" tabindex="-1" role="dialog" aria-hidden="true">
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
                <button onclick="cadastrarTiposDeMercadoria()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Tipos de Mercadoria -->
<div class="modal fade" id="modal-edicaoTiposDeMercadoria" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idTiposDeMercadoria" name="idTiposDeMercadoria">

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>
                </div>
                <button onclick="editarTiposDeMercadoria()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    preencherTabelaDeTiposDeMercadoria();

    function preencherTabelaDeTiposDeMercadoria() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeTiposDeMercadoria');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_TiposMercadoria_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeTiposDeMercadoria tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        `
                        <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-edicaoTiposDeMercadoria" data-id="${response.id}" data-descricao="${response.descricao}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTipoDeMercadoria(${response.id}, '${response.ativo}')">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTipoDeMercadoria(${response.id})">
                            <i class="fas fa-trash"></i>
                        </a>
                    `
                    ];

                    updateRowInTable('#tabelaDeTiposDeMercadoria', 'rowTipoDeMercadoria_', +response.id, rowData);

                });
            }
        });
    }

    function cadastrarTiposDeMercadoria() {

        const fieldsToValidate = [
            'descricao',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarTiposDeMercadoria');
        form_data.append('descricao', $('#descricao').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_TiposMercadoria_controller.php',
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
                        <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-edicaoTiposDeMercadoria" data-id="${response.id}" data-descricao="${response.descricao}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTipoDeMercadoria(${response.id}, '${response.ativo}')">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTipoDeMercadoria(${response.id})">
                            <i class="fas fa-trash"></i>
                        </a>
                    `
                    ];

                    updateRowInTable('#tabelaDeTiposDeMercadoria', 'rowTipoDeMercadoria_', +response.id, rowData);
                    $('#modal-cadastroTiposDeMercadoria').modal('hide');

                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarTiposDeMercadoria() {

        const fieldsToValidate = [
            'descricaoEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarTiposDeMercadoria');
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('id', $('#idTiposDeMercadoria').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_TiposMercadoria_controller.php',
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
                        <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-edicaoTiposDeMercadoria" data-id="${response.id}" data-descricao="${response.descricao}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTipoDeMercadoria(${response.id}, '${response.ativo}')">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTipoDeMercadoria(${response.id})">
                            <i class="fas fa-trash"></i>
                        </a>
                    `
                    ];

                    updateRowInTable('#tabelaDeTiposDeMercadoria', 'rowTipoDeMercadoria_', +response.id, rowData);
                    $('#modal-edicaoTiposDeMercadoria').modal('hide');

                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function inativarTipoDeMercadoria(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar o Tipo de Mercadoria?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarTipoDeMercadoria');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_TiposMercadoria_controller.php',
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
                                <a class="btn btn-primary btn-action mr-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-edicaoTiposDeMercadoria" data-id="${response.id}" data-descricao="${response.descricao}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTipoDeMercadoria(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTipoDeMercadoria(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeTiposDeMercadoria', 'rowTipoDeMercadoria_', +response.id, rowData);

                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    async function deletarTipoDeMercadoria(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover o Tipo de Mercadoria?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarTipoDeMercadoria');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_TiposMercadoria_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        removeRowFromTable('#tabelaDeTiposDeMercadoria', 'rowTipoDeMercadoria_', +response.id);

                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    $('#modal-edicaoTiposDeMercadoria').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idTiposDeMercadoria').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
    });
</script>