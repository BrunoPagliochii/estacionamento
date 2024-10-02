<?php require_once '../../../lib/BaseUrl.php'; ?>
<?php require_once '../../../functions/helpers.php'; ?>

<!DOCTYPE html>
<html lang="pt-Br">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $dados['NomePagina'] ?? 'AllDrip' ?></title>

    <?php include('../../layouts/styles.php') ?>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            <div class="row justify-content-center p-5">
                <div class="col-6">
                    <div class="row">
                        <div class="col-12" id="inserirAqui">

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>

<?php include('../../layouts/scripts.php') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtém os parâmetros da URL
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');
        const empresa = urlParams.get('empresa');

        // Verifica se os parâmetros estão presentes e não estão vazios
        if (!(token && empresa)) {
            alert('Dados inválidos');
            window.location = '<?= URL_BASE_HOST ?>/view/admin/estoque/composicao.php';
        } else {
            // Chama a função com os parâmetros
            coletarDadosDav(token, empresa);
        }
    });

    function coletarDadosDav(id, empresa) {

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'coletarDadosDav');
        form_data.append('id', id);
        form_data.append('empresa', empresa);

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/model/comercial/JQ_Davs_model.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response) {
                let html = ``;

                if (Array.isArray(response) && response.length > 0) {

                    var dados = response[0];
                    html += `<div class="card card-invoice">`;
                    html += `<div class="card-header">`;
                    html += `<div class="invoice-header">`;
                    html += `<div class="invoice-logo">`;
                    html += `<img src="<?= URL_BASE_HOST ?>/public/template/assets/img/logo.png" alt="Logo">`;
                    html += `</div>`;
                    html += `<h3 class="invoice-title mt-4">Orçamento</h3>`;
                    html += `</div>`;

                    html += `</div>`;

                    html += `<div class="card-body">`;
                    html += `<div class="separator-solid"></div>`;

                    html += `<div class="row">`;

                    html += `<div class="col-md-4 info-invoice">`;
                    html += `<h5 class="sub">Vencimento</h5>`;
                    html += `<p>${new Date(dados.VENCIMENTO).toLocaleDateString('pt-BR')}</p>`;
                    html += `</div>`;

                    html += `<div class="col-md-4 info-invoice">`;
                    html += `<h5 class="sub">Número do pedido</h5>`;
                    html += `<p>#${dados.ID}</p>`;
                    html += `</div>`;

                    html += `<div class="col-md-4 info-invoice">`;
                    html += `<h5 class="sub">Destinatário</h5>`;
                    html += `<p>`;
                    html += `${dados.CLIENTE}, ${dados.LOGRADOURO}<br>${dados.BAIRRO}, ${dados.MUNICIPIO} (${dados.UF})<br>${dados.CEP}`;
                    html += `</p>`;
                    html += `</div>`;

                    html += `</div>`;

                    html += `<div class="row">`;
                    html += `<div class="col-12">`;
                    html += `<div class="invoice-detail">`;

                    html += `<div class="invoice-top">`;
                    html += `<h3 class="title"><strong>Produtos</strong></h3>`;
                    html += `</div>`;

                    html += `<div class="invoice-item">`;
                    html += `<div class="table-responsive">`;

                    html += `<table class="table table-striped">`;
                    html += `<thead>`;
                    html += `<tr>`;
                    html += `<td><strong>Item</strong></td>`;
                    html += `<td class="text-center"><strong>Price</strong></td>`;
                    html += `<td class="text-center"><strong>Quantity</strong></td>`;
                    html += `<td class="text-end"><strong>Totals</strong></td>`;
                    html += `</tr>`;
                    html += `</thead>`;
                    html += `<tbody>`;
                    html += `<tr>`;
                    html += `<td>BS-200</td>`;
                    html += `<td class="text-center">$10.99</td>`;
                    html += `<td class="text-center">1</td>`;
                    html += `<td class="text-end">$10.99</td>`;
                    html += `</tr>`;
                    html += `</tbody>`;
                    html += `</table>`;

                    html += `</div>`;
                    html += `</div>`;

                    html += `</div>`;
                    html += `<div class="separator-solid  mb-3"></div>`;
                    html += `</div>`;
                    html += `</div>`;

                    html += `</div>`;
                    html += `<div class="card-footer">`;

                    html += `<div class="row">`;

                    html += `<div class="col-sm-7 col-md-5 mb-3 mb-md-0 transfer-to">`;
                    html += `<h5 class="sub">Forma de pagamento</h5>`;
                    html += `<div class="account-transfer">`;
                    html += `<div><span>${dados.FORMA_PAGAMENTO}</span></div>`;
                    html += `</div>`;
                    html += `</div>`;

                    html += `<div class="col-sm-5 col-md-7 transfer-total">`;
                    html += `<h5 class="sub">Total</h5>`;
                    html += `<div class="price">${dados.VALOR}</div>`;
                    html += `</div>`;

                    html += `</div>`;

                    html += `<div class="separator-solid"></div>`;

                    html += `<h6 class="text-uppercase mt-4 mb-1 fw-bold">Observações</h6>`;

                    html += `<p class="text-muted mb-0">${dados.OBSERVACAO}</p>`;
                    html += `</div>`;
                    html += `</div>`;
                    // Acessa o primeiro item do array

                } else {
                    html += `<h3 class="invoice-title">`;
                    html += `Sem dados`;
                    html += `</h3>`;
                }

                $('#inserirAqui').html(html);
            },
        });
    }
</script>