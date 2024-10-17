<?php
$dados = array(
    'NomePagina' => 'Estacionamentos',
    'MenuModulo' => 'Geral'
);
?>

<?php include('layouts/body.php') ?>

<div class="card card-dark">

    <div class="card-header">
        <h4>Novo</h4>
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-12 col-lg-3">
                <label for="placa">Placa </label>
                <input type="text" id="placa" name="placa" class="form-control">
            </div>

            <div class="col-12 col-lg-3">
                <label for="modelo">Modelo </label>
                <input type="text" id="modelo" name="modelo" class="form-control">
            </div>

            <div class="col-12 col-lg-3">
                <label for="cor">Cor </label>
                <input type="text" id="cor" name="cor" class="form-control">
            </div>

            <div class="col-12 col-lg-3">
                <label for="horaEntrada">Hora de entrada *</label>
                <input type="datetime-local" id="horaEntrada" name="horaEntrada" class="form-control">
            </div>

        </div>
        <button id="btnCadastrarEstacionamento" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>

    </div>
</div>


<div class="card card-dark">

    <div class="card-header">
        <h4>Estacionados</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelaDeEstacionados" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Cor</th>
                        <th>Entrada</th>
                        <th>Valor pago</th>
                        <th>Forma de pagamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php include('layouts/footer.php') ?>

<div class="modal fade" id="modal-pagamento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informar pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="idEstacionamentoPag" name="idEstacionamentoPag">

                    <div class="col-12 mb-2">
                        <label for="valorFinal">Valor a pagar *</label>
                        <input type="text" id="valorFinal" name="valorFinal" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12">
                        <label for="formaPagamento" class="mb-1">Forma de pagamento: *</label>
                        <select class="form-control form-control-sm select2 w-100" name="formaPagamento" id="formaPagamento">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="Pix">Pix</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Débito">Débito</option>
                            <option value="Crédito">Crédito</option>

                        </select>
                    </div>

                </div>
                <button id="btnPagar" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-finalizar" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden" id="idEstacionamento" name="idEstacionamento">

                    <div class="col-12 col-lg-6 mb-2">
                        <label for="horaEntradaFim">Hora de entrada *</label>
                        <input type="datetime-local" id="horaEntradaFim" name="horaEntradaFim" class="form-control" disabled>
                    </div>

                    <div class="col-12 col-lg-6 mb-2">
                        <label for="horaSaida">Hora de saída *</label>
                        <input type="datetime-local" id="horaSaida" name="horaSaida" class="form-control">
                    </div>

                </div>
                <button id="btnFinalizar" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {

        preencherTabelaDeEstacionamentos();

        function preencherTabelaDeEstacionamentos() {

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'preencherTabelaDeEstacionamentos');

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/model/admin/JQ_Estacionamentos_model.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(item) {

                    // Limpando o tbody antes de preencher
                    $('#tabelaDeEstacionados tbody').empty();

                    // Iterando sobre a resposta e adicionando linhas à tabela
                    item.forEach(function(response) {

                        let funcoes = '';
                        funcoes += `
                            <a class="btn btn-success btn-action"
                                data-bs-toggle="modal" data-bs-target="#modal-pagamento"
                                data-id="${response.id}"
                                data-pagamento="${(response.valor ? moedaFormat(response.valor) : null)}"
                                data-forma_pagamento="${(response.forma_pagamento ? response.forma_pagamento : null)}"

                            title="Pagamento">
                                <i class="fas fa-coins"></i>
                            </a>
                        `;
                        if (response.valor != '' && response.valor != null) {
                            funcoes += `
                                <a class="btn btn-info btn-action"
                                    data-bs-toggle="modal" data-bs-target="#modal-finalizar"
                                    data-id="${response.id}"
                                    data-hora_entrada="${response.hora_entrada}"

                                title="finalizar">
                                    <i class="fas fa-play"></i>
                                </a>
                            `;
                        }
                        funcoes += `
                            <a class="btn btn-danger btn-action" title="excluir" onclick="excluirEstacionamento(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `;

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.placa,
                            response.modelo,
                            response.cor,
                            formatDate(response.hora_entrada),
                            (response.valor ? moedaFormat(response.valor) : ''),
                            (response.forma_pagamento ? response.forma_pagamento : ''),
                            funcoes

                        ];

                        updateRowInTable('#tabelaDeEstacionados', 'rowEstacionado_', +response.id, rowData);

                    });
                }
            });
        }

        // Executa assim que o botão de salvar for clicado
        $('#btnCadastrarEstacionamento').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'horaEntrada',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'novoEstacionamento');
            form_data.append('placa', $('#placa').val());
            form_data.append('modelo', $('#modelo').val());
            form_data.append('cor', $('#cor').val());
            form_data.append('horaEntrada', $('#horaEntrada').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Estacionamentos_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        let funcoes = '';
                        funcoes += `
                            <a class="btn btn-success btn-action"
                                data-bs-toggle="modal" data-bs-target="#modal-pagamento"
                                data-id="${response.id}"
                                data-pagamento="${(response.valor ? moedaFormat(response.valor) : null)}"
                                data-forma_pagamento="${(response.forma_pagamento ? response.forma_pagamento : null)}"

                            title="Pagamento">
                                <i class="fas fa-coins"></i>
                            </a>
                        `;
                        if (response.valor != '' && response.valor != null) {
                            funcoes += `
                                <a class="btn btn-info btn-action"
                                    data-bs-toggle="modal" data-bs-target="#modal-finalizar"
                                    data-id="${response.id}"
                                    data-hora_entrada="${response.hora_entrada}"

                                title="finalizar">
                                    <i class="fas fa-play"></i>
                                </a>
                            `;
                        }
                        funcoes += `
                            <a class="btn btn-danger btn-action" title="excluir" onclick="excluirEstacionamento(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `;

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.placa,
                            response.modelo,
                            response.cor,
                            formatDate(response.hora_entrada),
                            (response.valor ? moedaFormat(response.valor) : ''),
                            (response.forma_pagamento ? response.forma_pagamento : ''),
                            funcoes

                        ];

                        updateRowInTable('#tabelaDeEstacionados', 'rowEstacionado_', +response.id, rowData);

                        $('#placa').val(null);
                        $('#modelo').val(null);
                        $('#cor').val(null);
                        $('#horaEntrada').val(null);
                    }
                    exibirToastr(response.msg, response.status);
                }
            });
        });
    });

    async function excluirEstacionamento(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover esse estacionamento?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'excluirEstacionamento');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Estacionamentos_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    exibirToastr(response.msg, response.status);
                    if (response.status == 'success') {
                        removeRowFromTable('#tabelaDeEstacionados', 'rowEstacionado_', +id);
                    }
                }
            });
        }
    }

    $('#modal-finalizar').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idEstacionamento').val(button.data('id'));
        $('#horaEntradaFim').val(button.data('hora_entrada'));
    });

    $('#modal-pagamento').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idEstacionamentoPag').val(button.data('id'));
        $('#valorFinal').val(button.data('pagamento'));
        $('#formaPagamento').val(button.data('forma_pagamento')).trigger('change');
    });

    $('.moeda').maskMoney({
        thousands: '.',
        decimal: ',',
        prefix: 'R$ ',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });

    $('#btnPagar').click(function(e) {
        // Cancela o envio do formulário
        e.preventDefault();

        const fieldsToValidate = [
            'idEstacionamentoPag',
            'valorFinal',
            'formaPagamento',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'pagarEstacionamento');
        form_data.append('id', $('#idEstacionamentoPag').val());
        form_data.append('valorFinal', $('#valorFinal').val());
        form_data.append('formaPagamento', $('#formaPagamento').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Estacionamentos_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                exibirToastr(response.msg, response.status);

                let funcoes = '';
                if (response.status == 'success') {

                    funcoes += `
                            <a class="btn btn-success btn-action"
                                data-bs-toggle="modal" data-bs-target="#modal-pagamento"
                                data-id="${response.id}"
                                data-pagamento="${(response.valor ? moedaFormat(response.valor) : null)}"
                                data-forma_pagamento="${(response.forma_pagamento ? response.forma_pagamento : null)}"

                            title="Pagamento">
                                <i class="fas fa-coins"></i>
                            </a>
                        `;
                    if (response.valor != '' && response.valor != null) {
                        funcoes += `
                                <a class="btn btn-info btn-action"
                                    data-bs-toggle="modal" data-bs-target="#modal-finalizar"
                                    data-id="${response.id}"
                                    data-hora_entrada="${response.hora_entrada}"

                                title="finalizar">
                                    <i class="fas fa-play"></i>
                                </a>
                            `;
                    }
                    funcoes += `
                            <a class="btn btn-danger btn-action" title="excluir" onclick="excluirEstacionamento(${response.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        `;

                    // Iterando sobre a resposta e adicionando linhas à tabela
                    let rowData = [
                        response.id,
                        response.placa,
                        response.modelo,
                        response.cor,
                        formatDate(response.hora_entrada),
                        (response.valor ? moedaFormat(response.valor) : ''),
                        (response.forma_pagamento ? response.forma_pagamento : ''),
                        funcoes

                    ];

                    updateRowInTable('#tabelaDeEstacionados', 'rowEstacionado_', +response.id, rowData);

                    $('#idEstacionamentoPag').val(null);
                    $('#valorFinal').val(null);
                    $('#formaPagamento').val(null);
                    $('#modal-pagamento').modal('hide');
                }
            }
        });
    });

    $('#btnFinalizar').click(function(e) {
        // Cancela o envio do formulário
        e.preventDefault();

        const fieldsToValidate = [
            'idEstacionamento',
            'horaSaida',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'finalizarEstacionamento');
        form_data.append('id', $('#idEstacionamento').val());
        form_data.append('horaSaida', $('#horaSaida').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/JQ_Estacionamentos_controller.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                exibirToastr(response.msg, response.status);
                if (response.status == 'success') {
                    removeRowFromTable('#tabelaDeEstacionados', 'rowEstacionado_', +$('#idEstacionamento').val());
                    $('#idEstacionamento').val(null);
                    $('#horaEntradaFim').val(null);
                    $('#horaSaida').val(null);
                    $('#valorHora').val(null);
                    $('#valorFinal').val(null);
                    $('#formaPagamento').val(null);
                    $('#modal-finalizar').modal('hide');
                }
            }
        });
    });
</script>