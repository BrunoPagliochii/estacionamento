<?php
$dados = array(
    'NomePagina' => 'Estacionamentos finalizados',
    'MenuModulo' => 'Geral'
);
?>

<?php include('layouts/body.php') ?>

<div class="card card-dark">

    <div class="card-header">
        <h4>Estacionamentos finalizados</h4>
        <div class="card-header-action">

            <a href="javascript:void(0)">
                <i class="fas fa-search" data-bs-toggle="modal" data-bs-target="#modal-filtrar"></i>
            </a>

        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelaDeEstacionados" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Cor</th>
                        <th>Entrada</th>
                        <th>Quem estacionou</th>
                        <th>Quem saiu</th>
                        <th>Quando saiu</th>
                        <th>Forma de pagamento</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
</div>

<?php include('layouts/footer.php') ?>


<div class="modal fade" id="modal-filtrar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12 col-lg-6 mb-3">
                        <label for="horaEntradaInicio">Hora de entrada (Inicio)</label>
                        <input type="datetime-local" id="horaEntradaInicio" name="horaEntradaInicio" class="form-control">
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label for="horaEntradaFim">Hora de entrada (Fim)</label>
                        <input type="datetime-local" id="horaEntradaFim" name="horaEntradaFim" class="form-control">
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label for="horaSaidaInicio">Hora de Saida (Inicio)</label>
                        <input type="datetime-local" id="horaSaidaInicio" name="horaSaidaInicio" class="form-control">
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label for="horaSaidaFim">Hora de Saida (Fim)</label>
                        <input type="datetime-local" id="horaSaidaFim" name="horaSaidaFim" class="form-control">
                    </div>


                    <div class="col-12">
                        <label for="formaPagamento" class="mb-1">Forma de pagamento: </label>
                        <select class="form-control form-control-sm select2 w-100" name="formaPagamento" id="formaPagamento">
                            <option value="" selected disabled>Selecione...</option>
                            <option value="">Todas </option>
                            <option value="Pix">Pix</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Débito">Débito</option>
                            <option value="Crédito">Crédito</option>
                        </select>
                    </div>

                </div>
                <button onclick="preencherTabelaDeEstacionamentosGeral()" type="button" data-dismiss="modal" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    preencherTabelaDeEstacionamentosGeral();

    function preencherTabelaDeEstacionamentosGeral() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeEstacionamentosGeral');
        form_data.append('horaEntradaInicio', $('#horaEntradaInicio').val());
        form_data.append('horaEntradaFim', $('#horaEntradaFim').val());
        form_data.append('horaSaidaInicio', $('#horaSaidaInicio').val());
        form_data.append('horaSaidaFim', $('#horaSaidaFim').val());
        form_data.append('formaPagamento', $('#formaPagamento').val());

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
                $('#tabelaDeEstacionados tbody tr').each(function() {
                    let rowId = $(this).attr('id');
                    let handle = rowId.replace('rowEstacionado_', '');

                    // Se o 'handle' não está presente na nova resposta, removemos a linha
                    if (!item.some(teste => teste.handle == handle)) {
                        removeRowFromTable('#tabelaDeEstacionados', 'rowEstacionado_', handle);
                    }
                });

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.placa,
                        response.modelo,
                        response.cor,
                        formatDate(response.hora_entrada),
                        response.quem_entrou,
                        response.quem_saiu,
                        formatDate(response.hora_saida),
                        response.forma_pagamento,
                        moedaFormat(response.valor),
                    ];

                    updateRowInTable('#tabelaDeEstacionados', 'rowEstacionado_', +response.id, rowData);
                });
                updateTfootFromTable('#tabelaDeEstacionados', [9]);
            }
        });
    }
</script>