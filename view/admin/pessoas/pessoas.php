<?php
$dados = array(
    'NomePagina' => 'Gerenciamento de Pessoas',
    'MenuModulo' => 'Pessoas'
);
?>


<?php include('../layouts/body.php') ?>

<?php
$municipiosArray = array();
$resultado = $conexao->query("SELECT A.NOME AS MUNICIPIO, B.UF AS SIGLA, A.ID FROM BD_MUNICIPIOS A INNER JOIN BD_ESTADOS B ON A.UF = B.ID");
if (!$resultado) {
    die("Erro na consulta: " . $conexao->error);
}

while ($row = $resultado->fetch_assoc()) {
    $municipiosArray[] = $row;
} ?>

<section class="section">

    <div class="col-12">
        <div class="card card-dark">

            <div class="card-header">
                <h4><?= $dados['NomePagina'] ?></h4>
                <div class="card-header-action">
                    <a href="<?= URL_BASE_HOST ?>/view/admin/pessoas/cadastrarPessoa.php">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDePessoas" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Celular</th>
                                <th>Município</th>
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

<script>
    preencherTabelaDePessoas();

    function preencherTabelaDePessoas() {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'preencherTabelaDePessoas');

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/pessoas/JQ_Pessoas_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(item) {

                // Limpando o tbody antes de preencher
                $('#tabelaDePessoas tbody').empty();

                // Iterando sobre a resposta e adicionando linhas à tabela
                item.forEach(function(response) {

                    let rowData = [
                        response.id,
                        response.nome,
                        response.email,
                        response.celular,
                        response.municipio + '(' + response.uf + ')',
                        `
                            <a title="Editar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/pessoas/editarPessoa.php?i=${response.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarPessoa(${response.id}, '${response.ativo}')">
                                <i class="fas fa-power-off"></i>
                            </a> 
                        `
                    ];

                    updateRowInTable('#tabelaDePessoas', 'rowPessoaf_', +response.id, rowData);

                });
            }
        });
    }

    async function inativarPessoa(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa pessoa?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarPessoa');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/pessoas/JQ_Pessoas_controller.php',
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
                            response.nome,
                            response.email,
                            response.celular,
                            response.municipio + '(' + response.uf + ')',
                            `
                                <a title="Editar" class="btn btn-primary btn-action mr-1" href="<?= URL_BASE_HOST ?>/view/admin/pessoas/editarPessoa.php?i=${response.id}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarPessoa(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a> 
                            `
                        ];

                        updateRowInTable('#tabelaDePessoas', 'rowPessoaf_', +response.id, rowData);

                    } else {
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }
</script>