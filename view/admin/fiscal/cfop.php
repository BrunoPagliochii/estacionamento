<?php
$dados = array(
    'NomePagina' => 'CFOP',
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
                    <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroCfop"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeCfop" class="table table-bordered">
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

<!-- Modal Cadastro de CFOP -->
<div class="modal fade" id="modal-cadastroCfop" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12 col-xl-6">
                        <label for="codigo">Código *</label>
                        <input maxlength="4" type="text" id="codigo" name="codigo" class="form-control">
                    </div>

                    <div class="col-12 col-xl-6">
                        <label for="descricao">Descrição *</label>
                        <input maxlength="300" type="text" id="descricao" name="descricao" class="form-control">
                    </div>

                    <div class="col-12 col-xl-4 mt-2">
                        <label for="movimentaEstoque" class="mb-1">Movimenta estoque: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="movimentaEstoque" id="movimentaEstoque">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>

                    <div class="col-12 col-xl-4 mt-2">
                        <label for="calculaIpi" class="mb-1">Calcular IPI: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="calculaIpi" id="calculaIpi">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>

                    <div class="col-12 col-xl-4 mt-2">
                        <label for="retencao" class="mb-1">PIS / COFINS / CSLL: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="retencao" id="retencao">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>

                </div>
                <button id="btnCadastrarCfop" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar de CFOP -->
<div class="modal fade" id="modal-editarCfop" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" id="idCfop" name="idCfop">

                    <div class="col-12 mb-3">
                        <label for="codigoEdt">Código *</label>
                        <input maxlength="4" type="text" id="codigoEdt" name="codigoEdt" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="300" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>

                    <div class="col-12 col-xl-4 mt-2">
                        <label for="movimentaEstoqueEdt" class="mb-1">Movimenta estoque: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="movimentaEstoqueEdt" id="movimentaEstoqueEdt">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>

                    <div class="col-12 col-xl-4 mt-2">
                        <label for="calculaIpiEdt" class="mb-1">Calcular IPI: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="calculaIpiEdt" id="calculaIpiEdt">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>

                    <div class="col-12 col-xl-4 mt-2">
                        <label for="retencaoEdt" class="mb-1">PIS / COFINS / CSLL: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="retencaoEdt" id="retencaoEdt">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="S">Sim</option>
                            <option value="N">Não</option>
                        </select>
                    </div>

                </div>
                <button id="btnEditarCfop" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        preencherTabelaDeCfop();

        function preencherTabelaDeCfop() {

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'preencherTabelaDeCfop');

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/model/admin/fiscal/JQ_Cfop_model.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(item) {

                    // Limpando o tbody antes de preencher
                    $('#tabelaDeCfop tbody').empty();

                    // Iterando sobre a resposta e adicionando linhas à tabela
                    item.forEach(function(response) {

                        let rowData = [
                            response.id,
                            response.codigo,
                            response.descricao,
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar" 
                                    data-id="${response.id}"
                                    data-codigo="${response.codigo}"
                                    data-descricao="${response.descricao}"
                                    data-movimenta_estoque="${response.movimenta_estoque}"
                                    data-calcula_ipi="${response.calcula_ipi}"
                                    data-retencao="${response.retencao}"

                                data-bs-toggle="modal" data-bs-target="#modal-editarCfop">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCfop(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCfop(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCfop', 'rowCfop_', +response.id, rowData);

                    });
                }
            });
        }

        // Executa assim que o botão de salvar for clicado
        $('#btnCadastrarCfop').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'codigo',
                'descricao',
                'movimentaEstoque',
                'calculaIpi',
                'retencao',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'novoCfop');
            form_data.append('codigo', $('#codigo').val());
            form_data.append('descricao', $('#descricao').val());
            form_data.append('movimentaEstoque', $('#movimentaEstoque').val());
            form_data.append('calculaIpi', $('#calculaIpi').val());
            form_data.append('retencao', $('#retencao').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cfop_controller.php',
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
                                <a class="btn btn-primary btn-action mr-1" title="Editar" 
                                    data-id="${response.id}"
                                    data-codigo="${response.codigo}"
                                    data-descricao="${response.descricao}"
                                    data-movimenta_estoque="${response.movimenta_estoque}"
                                    data-calcula_ipi="${response.calcula_ipi}"
                                    data-retencao="${response.retencao}"

                                data-bs-toggle="modal" data-bs-target="#modal-editarCfop">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCfop(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCfop(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCfop', 'rowCfop_', +response.id, rowData);
                        $('#modal-cadastroCfop').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });

        // Executa assim que o botão de salvar for clicado
        $('#btnEditarCfop').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'idCfop',
                'codigoEdt',
                'descricaoEdt',
                'movimentaEstoqueEdt',
                'calculaIpiEdt',
                'retencaoEdt',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'editarCfop');
            form_data.append('id', $('#idCfop').val());
            form_data.append('codigo', $('#codigoEdt').val());
            form_data.append('descricao', $('#descricaoEdt').val());
            form_data.append('movimentaEstoque', $('#movimentaEstoqueEdt').val());
            form_data.append('calculaIpi', $('#calculaIpiEdt').val());
            form_data.append('retencao', $('#retencaoEdt').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cfop_controller.php',
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
                                <a class="btn btn-primary btn-action mr-1" title="Editar" 
                                    data-id="${response.id}"
                                    data-codigo="${response.codigo}"
                                    data-descricao="${response.descricao}"
                                    data-movimenta_estoque="${response.movimenta_estoque}"
                                    data-calcula_ipi="${response.calcula_ipi}"
                                    data-retencao="${response.retencao}"

                                data-bs-toggle="modal" data-bs-target="#modal-editarCfop">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCfop(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCfop(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCfop', 'rowCfop_', +response.id, rowData);
                        $('#modal-editarCfop').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });
    });

    $('#modal-editarCfop').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idCfop').val(button.data('id'));
        $('#codigoEdt').val(button.data('codigo'));
        $('#descricaoEdt').val(button.data('descricao'));
        $('#movimentaEstoqueEdt').val(button.data('movimenta_estoque')).trigger('change');
        $('#calculaIpiEdt').val(button.data('calcula_ipi')).trigger('change');
        $('#retencaoEdt').val(button.data('retencao')).trigger('change');
    });

    async function inativarCfop(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa tributação?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarCfop');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cfop_controller.php',
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
                                <a class="btn btn-primary btn-action mr-1" title="Editar" 
                                    data-id="${response.id}"
                                    data-codigo="${response.codigo}"
                                    data-descricao="${response.descricao}"
                                    data-movimenta_estoque="${response.movimenta_estoque}"
                                    data-calcula_ipi="${response.calcula_ipi}"
                                    data-retencao="${response.retencao}"

                                data-bs-toggle="modal" data-bs-target="#modal-editarCfop">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarCfop(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarCfop(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeCfop', 'rowCfop_', +response.id, rowData);

                    }
                }
            });
        }
    }

    async function deletarCfop(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover essa tributação?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarCfop');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Cfop_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    exibirToastr(response.msg, response.msg);
                    if (response.status == 'success') {
                        removeRowFromTable('#tabelaDeCfop', 'rowCfop_', +response.id);
                    }
                }
            });
        }
    }
</script>