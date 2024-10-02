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
                <li class="breadcrumb-item active" aria-current="page">Editar Pessoa: #<?= $_GET['i']?></li>
            </ol>
        </nav>
        
    </div>

    <div class="col-12">
        <div class="card card-dark">
            <div class="card-header">

                <ul class="nav nav-pills" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-dadosGeraisEdt" role="tab" aria-selected="true">Geral</a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-enderecoEdt" role="tab" aria-selected="false" tabindex="-1">Endereço</a>
                    </li>

                </ul>

            </div>
            <div class="card-body">

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-dadosGeraisEdt" role="tabpanel">

                        <div class="row">
                            <div class="col-12">
                                <label for="tipoEdt">Tipo de cadastro *</label>
                                <select id="tipoEdt" name="tipoEdt" class="form-control form-control-sm select2">
                                    <option selected disabled>Selecione...</option>
                                    <option value="PF">Pessoa Física</option>
                                    <option value="PJ">Pessoa Jurídica</option>
                                </select>
                            </div>

                            <div class="col-12 mt-2">
                                <label for="nomeEdt">Nome completo *</label>
                                <input maxlength="45" type="text" id="nomeEdt" name="nomeEdt" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="documentoEdt">CPF *</label>
                                <input type="text" id="documentoEdt" name="documentoEdt" class="form-control" disabled>
                            </div>

                            <div class="col-12 mt-2">
                                <label for="emailEdt">E-mail *</label>
                                <input maxlength="45" type="email" id="emailEdt" name="emailEdt" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="celularEdt">Celular *</label>
                                <input type="text" id="celularEdt" name="celularEdt" class="form-control">
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="tab-enderecoEdt" role="tabpanel">

                        <div class="row">

                            <div class="col-12">
                                <label for="logradouroEdt">Logradouro *</label>
                                <input maxlength="45" type="text" id="logradouroEdt" name="logradouroEdt" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="bairroEdt">Bairro *</label>
                                <input type="text" maxlength="45" id="bairroEdt" name="bairroEdt" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="cepEdt">CEP *</label>
                                <input maxlength="9" type="text" id="cepEdt" name="cepEdt" class="form-control">
                            </div>

                            <div class="col-12 mt-2">
                                <label for="municipioEdt" class="mb-1">Municipio: *</label>
                                <select class="form-control form-control-sm select2 w-100" name="municipioEdt" id="municipioEdt">
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

                <button onclick="editarPessoa()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>

            </div>
        </div>
    </div>

</section>

<?php include('../layouts/footer.php') ?>

<script>
    $(function() {

        // Verifica se os parâmetros estão presentes e não estão vazios
        if (!(new URLSearchParams(window.location.search).get('i'))) {
            alert('Dados inválidos');
            window.location = '<?= URL_BASE_HOST ?>/view/admin/pessoas/pessoas.php';
        } else {
            buscarPessoa(new URLSearchParams(window.location.search).get('i'));
        }
    });

    function buscarPessoa(id) {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'buscarPessoa');
        form_data.append('id', id);

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/admin/pessoas/JQ_Pessoas_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {

                if (response.status == 'success') {
                    $('#tipoEdt').val(response.tipo).trigger('change');
                    $('#nomeEdt').val(response.nome);
                    $('#documentoEdt').val(response.documento);
                    $('#emailEdt').val(response.email);
                    $('#celularEdt').val(response.celular);

                    $('#logradouroEdt').val(response.logradouro);
                    $('#bairroEdt').val(response.bairro);
                    $('#cepEdt').val(response.cep);
                    $('#municipioEdt').val(response.municipio_id).trigger('change');
                } else {
                    window.location = '<?= URL_BASE_HOST ?>/view/admin/pessoas/pessoas.php';
                }
            }
        });
    }

    function editarPessoa() {
        let hasError = false;
        let camposUpload = [
            'tipoEdt',
            'nomeEdt',
            'documentoEdt',
            'emailEdt',
            'celularEdt',
        ];

        hasError = !validateForm(...camposUpload);

        if (hasError) {
            $('a[href="#tab-dadosGeraisEdt"]').tab('show');
            return false;
        }

        camposUpload = [
            'logradouroEdt',
            'bairroEdt',
            'cepEdt',
            'municipioEdt',
        ];

        hasError = !validateForm(...camposUpload);

        if (hasError) {
            $('a[href="#tab-enderecoEdt"]').tab('show');
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'editarPessoa');
        form_data.append('tipo', $('#tipoEdt').val());
        form_data.append('nome', $('#nomeEdt').val());
        form_data.append('documento', $('#documentoEdt').val());
        form_data.append('email', $('#emailEdt').val());
        form_data.append('celular', $('#celularEdt').val());

        form_data.append('logradouro', $('#logradouroEdt').val());
        form_data.append('bairro', $('#bairroEdt').val());
        form_data.append('cep', $('#cepEdt').val());
        form_data.append('municipio', $('#municipioEdt').val());

        form_data.append('id', new URLSearchParams(window.location.search).get('i'));

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

    $('#cepEdt').mask('00000-000');
    $('#tipoEdt').change(function() {
        if ($(this).val() == 'PF') {
            $('#documentoEdt').prop('disabled', false);
            $('label[for="documentoEdt"]').text('CPF *');
            $('#documentoEdt').mask('000.000.000-00', {
                reverse: true
            });

        }

        if ($(this).val() == 'PJ') {
            $('#documentoEdt').prop('disabled', false);
            $('label[for="documentoEdt"]').text('CNPJ *');
            $('#documentoEdt').mask('00.000.000/0000-00', {
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

    $('#celularEdt').mask(SPMaskBehavior, spOptions);
</script>