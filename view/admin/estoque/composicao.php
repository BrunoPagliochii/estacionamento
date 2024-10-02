<?php
$dados = array(
    'NomePagina' => 'Composição',
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
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroOrdemComposicao"></i>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaOrdensDeComposicao" class="table table-bordered">
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

<!-- Modal Cadastro de Ordens de composição -->
<div class="modal fade" id="modal-cadastroOrdemComposicao" tabindex="-1" role="dialog" aria-hidden="true">
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
                <button onclick="cadastrarOrdemDeComposicao()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    preencherTabelaDeComposicao();

    function preencherTabelaDeComposicao() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeComposicao');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_OrdemComposicao_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaOrdensDeComposicao tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        `
                            <a title="Acessar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/estoque/ordemProducao.php?i=${response.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarOrdemDeComposicao(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaOrdensDeComposicao', 'rowOrdemComposicao_', +response.id, rowData);

                });
            }
        });
    }

    function cadastrarOrdemDeComposicao() {

        const fieldsToValidate = [
            'descricao',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarOrdemDeComposicao');
        form_data.append('descricao', $('#descricao').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
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
                            <a title="Acessar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/estoque/ordemProducao.php?i=${response.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarOrdemDeComposicao(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                        `
                    ];

                    updateRowInTable('#tabelaOrdensDeComposicao', 'rowOrdemComposicao_', +response.id, rowData);
                    $('#modal-cadastroOrdemComposicao').modal('hide');
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function inativarOrdemDeComposicao(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa ordem de produção?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarOrdemDeComposicao');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_OrdemComposicao_controller.php',
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
                            <a title="Acessar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/estoque/ordemProducao.php?i=${response.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarOrdemDeComposicao(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a>
                        `
                        ];

                        updateRowInTable('#tabelaOrdensDeComposicao', 'rowOrdemComposicao_', +response.id, rowData);
                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }
</script>