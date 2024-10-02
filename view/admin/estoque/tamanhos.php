<?php
$dados = array(
    'NomePagina' => 'Cadastro de Tamanhos',
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
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroDeTamanhos"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeTamanhos" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Sigla</th>
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

<!-- Modal Cadastro Tamanhos -->
<div class="modal fade" id="modal-cadastroDeTamanhos" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <div class="col-12 mt-2">
                        <label for="sigla">Sigla *</label>
                        <input maxlength="5" type="text" id="sigla" name="sigla" class="form-control">
                    </div>

                </div>
                <button onclick="cadastrarTamanhos()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Tamanhos -->
<div class="modal fade" id="modal-edicaoTamanhos" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idTamanho" name="idTamanho">

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="siglaEdt">Sigla *</label>
                        <input maxlength="5" type="text" id="siglaEdt" name="siglaEdt" class="form-control">
                    </div>
                </div>
                <button onclick="editarTamanho()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    preencherTabelaDeTamanhos();

    function preencherTabelaDeTamanhos() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeTamanhos');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_Tamanhos_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeTamanhos tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        response.sigla,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-sigla="${response.sigla}" data-bs-toggle="modal" data-bs-target="#modal-edicaoTamanhos">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTamanhos(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTamanhos(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeTamanhos', 'rowTamanho_', +response.id, rowData);

                });
            }
        });
    }

    function cadastrarTamanhos() {

        const fieldsToValidate = [
            'descricao', 'sigla',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarTamanhos');
        form_data.append('descricao', $('#descricao').val());
        form_data.append('sigla', $('#sigla').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Tamanhos_controller.php',
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
                        response.sigla,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-sigla="${response.sigla}" data-bs-toggle="modal" data-bs-target="#modal-edicaoTamanhos">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTamanhos(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTamanhos(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeTamanhos', 'rowTamanho_', +response.id, rowData);
                    $('#modal-cadastroDeTamanhos').modal('hide');

                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarTamanho() {

        const fieldsToValidate = [
            'descricaoEdt', 'siglaEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarTamanho');
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('id', $('#idTamanho').val());
        form_data.append('sigla', $('#siglaEdt').val());


        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Tamanhos_controller.php',
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
                        response.sigla,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-sigla="${response.sigla}" data-bs-toggle="modal" data-bs-target="#modal-edicaoTamanhos">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTamanhos(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTamanhos(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaDeTamanhos', 'rowTamanho_', +response.id, rowData);
                    $('#modal-edicaoTamanhos').modal('hide');

                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function inativarTamanhos(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar o Tamanho?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarTamanhos');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Tamanhos_controller.php',
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
                            response.sigla,
                            `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" data-id="${response.id}" data-descricao="${response.descricao}" data-sigla="${response.sigla}" data-bs-toggle="modal" data-bs-target="#modal-edicaoTamanhos">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarTamanhos(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                            <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarTamanhos(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `
                        ];

                        updateRowInTable('#tabelaDeTamanhos', 'rowTamanho_', +response.id, rowData);

                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    async function deletarTamanhos(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover esse Tamanho?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarTamanhos');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Tamanhos_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        removeRowFromTable('#tabelaDeTamanhos', 'rowTamanho_', +response.id);
                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }


    $('#modal-edicaoTamanhos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idTamanho').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
        $('#siglaEdt').val(button.data('sigla'));
    });
</script>