<?php
$dados = array(
    'NomePagina' => 'Gerenciar Produtos',
    'MenuModulo' => 'Estoque'
);
?>

<?php include('../layouts/body.php') ?>

<div class="col-12">
    <div class="card card-dark">

        <div class="card-header">
            <h4><?= $dados['NomePagina'] ?></h4>
            <div class="card-header-action">
                <a href="<?= URL_BASE_HOST ?>/view/admin/estoque/cadastrarProduto.php">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeProdutos" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                            <th>Valor de venda</th>
                            <th>Grupo</th>
                            <th>Cor</th>
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

<script>
    preencherTabelaDeProdutos();

    function preencherTabelaDeProdutos() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeProdutos');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/estoque/JQ_Produtos_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeProdutos tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.descricao,
                        response.quantidade,
                        response.valor_venda,
                        response.grupo,
                        `
                            <div class="card-icon" style="background-color: ${response.hexadecimal};">&nbsp;</div>
                        `,
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/estoque/editarProduto.php?i=${response.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarProduto(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                            </a> 
                        `
                    ];

                    updateRowInTable('#tabelaDeProdutos', 'rowProduto_', +response.id, rowData);

                });
            }
        });
    }

    async function inativarProduto(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar esse produto?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarProduto');
            form_data.append('ID', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/estoque/JQ_Produtos_controller.php',
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
                            response.quantidade,
                            response.valor_venda,
                            response.grupo,
                            `
                                <div class="card-icon" style="background-color: ${response.hexadecimal};">&nbsp;</div>
                            `,
                                `
                                <a title="Editar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/estoque/editarProduto.php?i=${response.id}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarProduto(${response.id}, '${response.ativo}')">
                                        <i class="fas fa-power-off"></i>
                                </a> 
                            `
                        ];

                        updateRowInTable('#tabelaDeProdutos', 'rowProduto_', +response.id, rowData);
                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        }
    }
</script>