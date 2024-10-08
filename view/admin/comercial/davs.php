<?php
$dados = array(
    'NomePagina' => 'Pedidos / Orçamentos',
    'MenuModulo' => 'Comercial'
);
?>

<?php include('../layouts/body.php') ?>

<div class="col-12">
    <div class="card card-dark">

        <div class="card-header">
            <h4><?= $dados['NomePagina'] ?></h4>
            <div class="card-header-action">
                <a href="<?= URL_BASE_HOST ?>/view/admin/comercial/cadastrarDavs.php">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeDavs" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Validade</th>
                            <th>Frete</th>
                            <th>Outras Despesas</th>
                            <th>Observações</th>
                            <th>Status</th>
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
    preencherTabelaDeDavs();

    function preencherTabelaDeDavs() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDeDavs');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/comercial/JQ_Davs_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDeDavs tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                Object.values(item.davs).forEach(function(response) {

                    if (response.id_dav) {
                        let rowData = [
                            response.id_dav,
                            response.nome + `&nbsp;(${response.documento})`,
                            formatDate(response.validade),
                            moedaFormat(response.frete),
                            moedaFormat(response.outras_despesas),
                            response.observacoes,
                            `
                                <span class="badge badge-${response.classe}">${response.status}</span>
                            `,
                            `
                                <a title="Editar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/comercial/editarDavs.php?i=${response.id_dav}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button ${(response.permite_alterar == 'N') ? 'disabled' : ''} class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarDav(${response.id_dav}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            `
                        ];

                        updateRowInTable('#tabelaDeDavs', 'rowDav_', +response.id_dav, rowData);
                    }

                });
            }
        });
    }

    async function inativarDav(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar esse dav?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarDav');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/comercial/JQ_Davs_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.statusHttp == 'success') {

                        let rowData = [

                            response.id_dav,
                            response.nome + `&nbsp;(${response.documento})`,
                            formatDate(response.validade),
                            moedaFormat(response.frete),
                            moedaFormat(response.outras_despesas),
                            response.observacoes,
                            `
                                <span class="badge badge-${response.classe}">${response.status}</span>
                            `,
                            `
                                <a title="Editar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/comercial/editarDavs.php?i=${response.id_dav}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button ${(response.permite_alterar == 'N') ? 'disabled' : ''} class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarDav(${response.id_dav}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            `
                        ];

                        updateRowInTable('#tabelaDeDavs', 'rowDav_', +response.id_dav, rowData);
                        exibirToastr(response.msg, response.statusHttp);

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        }
    }
</script>