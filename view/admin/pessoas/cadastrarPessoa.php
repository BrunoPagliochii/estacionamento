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

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL_BASE_HOST ?>/view/admin/pessoas/pessoas.php">Pessoas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
            </ol>
        </nav>
    </div>

    <div class="col-12">
        <div class="card card-dark">
            <div class="card-header">

                <ul class="nav nav-pills" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-dadosGerais" role="tab" aria-selected="true">Geral</a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-endereco" role="tab" aria-selected="false" tabindex="-1">Endereço</a>
                    </li>

                </ul>

            </div>
            <div class="card-body">

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-dadosGerais" role="tabpanel">


                        <div class="row">
                            <div class="col-12">
                                <label for="tipo">Tipo de cadastro *</label>
                                <select id="tipo" name="tipo" class="form-control form-control-sm select2">
                                    <option selected disabled>Selecione...</option>
                                    <option value="PF">Pessoa Física</option>
                                    <option value="PJ">Pessoa Jurídica</option>
                                </select>
                            </div>

                            <div class="col-12 mt-2">
                                <label for="nome">Nome completo *</label>
                                <input maxlength="45" type="text" id="nome" name="nome" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="documento">CPF *</label>
                                <input type="text" id="documento" name="documento" class="form-control" disabled>
                            </div>

                            <div class="col-12 mt-2">
                                <label for="email">E-mail *</label>
                                <input maxlength="45" type="email" id="email" name="email" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="celular">Celular *</label>
                                <input type="text" id="celular" name="celular" class="form-control">
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-endereco" role="tabpanel">

                        <div class="row">

                            <div class="col-12 mt-2">
                                <label for="logradouro">Logradouro *</label>
                                <input maxlength="45" type="text" id="logradouro" name="logradouro" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="bairro">Bairro *</label>
                                <input type="text" maxlength="45" id="bairro" name="bairro" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="cep">CEP *</label>
                                <input maxlength="9" type="text" id="cep" name="cep" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="municipio" class="mb-1">Municipio: *</label>
                                <select class="form-control form-control-sm select2 w-100" name="municipio" id="municipio">
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($municipiosArray as $municipio) { ?>
                                        <option value="<?= $municipio['ID'] ?>">
                                            <?= $municipio['MUNICIPIO'] ?> (<?= $municipio['SIGLA'] ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>

                    </div>

                </div>

                <button onclick="cadastrarPessoa()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>

            </div>
        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>

<script>
    function cadastrarPessoa() {

        let hasError = false;
        let camposUpload = [
            'tipo',
            'nome',
            'documento',
            'email',
            'celular',
        ];

        hasError = !validateForm(...camposUpload);

        if (hasError) {
            $('a[href="#tab-dadosGerais"]').tab('show');
            return false;
        }

        camposUpload = [
            'logradouro',
            'bairro',
            'cep',
            'municipio',
        ];
        hasError = !validateForm(...camposUpload);

        if (hasError) {
            $('a[href="#tab-endereco"]').tab('show');
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarPessoa');
        form_data.append('tipo', $('#tipo').val());
        form_data.append('nome', $('#nome').val());
        form_data.append('documento', $('#documento').val());
        form_data.append('email', $('#email').val());
        form_data.append('celular', $('#celular').val());

        form_data.append('logradouro', $('#logradouro').val());
        form_data.append('bairro', $('#bairro').val());
        form_data.append('cep', $('#cep').val());
        form_data.append('municipio', $('#municipio').val());

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
                    window.location = '<?= URL_BASE_HOST ?>/view/admin/pessoas/pessoas.php';
                } else {
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    $('#cep').mask('00000-000');
    $('#tipo').change(function() {
        if ($(this).val() == 'PF') {
            $('#documento').prop('disabled', false);
            $('label[for="documento"]').text('CPF *');
            $('#documento').mask('000.000.000-00', {
                reverse: true
            });

        }

        if ($(this).val() == 'PJ') {
            $('#documento').prop('disabled', false);
            $('label[for="documento"]').text('CNPJ *');
            $('#documento').mask('00.000.000/0000-00', {
                reverse: true
            });
        }
    });

    var SPMaskBehavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

    $('#celular').mask(SPMaskBehavior, spOptions);
</script>