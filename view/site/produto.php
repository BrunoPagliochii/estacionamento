<?php
$dados = array(
    'NomePagina' => 'ALL DRIP - Produto',
);
?>

<?php include('layouts/body.php') ?>

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-12 col-md-6">
                <div class="row">

                    <div class="col-12 align-items-center">
                        <img id="imagemPrincipal" alt="image" src="<?= URL_BASE_HOST ?>/public/assets/img/teste.jpg" class="img-thumbnail img-fluid w-100" />
                    </div>

                    <div class="col-12">
                        <div class="d-flex">
                            <a href="javascript:void(0)" class="mx-1 mt-2">
                                <img src="<?= URL_BASE_HOST ?>/public/assets/img/teste.jpg" class="img-thumbnail img-fluid" width="100" height="100" onclick="changeImage('<?= URL_BASE_HOST ?>/public/assets/img/teste.jpg')" />
                            </a>
                            <a href="javascript:void(0)" class="mx-1 mt-2">
                                <img src="<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg" class="img-thumbnail img-fluid" width="100" height="100" onclick="changeImage('<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg')" />
                            </a>
                            <a href="javascript:void(0)" class="mx-1 mt-2">
                                <img src="<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg" class="img-thumbnail img-fluid" width="100" height="100" onclick="changeImage('<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg')" />
                            </a>
                            <a href="javascript:void(0)" class="mx-1 mt-2">
                                <img src="<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg" class="img-thumbnail img-fluid" width="100" height="100" onclick="changeImage('<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg')" />
                            </a>
                            <a href="javascript:void(0)" class="mx-1 mt-2">
                                <img src="<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg" class="img-thumbnail img-fluid" width="100" height="100" onclick="changeImage('<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg')" />
                            </a>
                            <a href="javascript:void(0)" class="mx-1 mt-2">
                                <img src="<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg" class="img-thumbnail img-fluid" width="100" height="100" onclick="changeImage('<?= URL_BASE_HOST ?>/public/assets/img/teste2.jpg')" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center font-bold text-black">Work Jacket Hammer Grey</h1>
                        <h3 class="text-center">
                            <del class="text-secondary">R$ 739,00</del>&nbsp;
                            <span class="font-bold text-black">R$ 739,00</span>
                        </h3>
                    </div>
                    <div class="col-12 mt-2 mt-md-3">
                        <div class="form-group">
                            <label class="form-label mb-0">Selecione o tamanho</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="radio1" value="1" class="selectgroup-input-radio" checked="">
                                    <span class="selectgroup-button">S</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="radio1" value="2" class="selectgroup-input-radio">
                                    <span class="selectgroup-button">M</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="radio1" value="3" class="selectgroup-input-radio">
                                    <span class="selectgroup-button">L</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="radio1" value="4" class="selectgroup-input-radio">
                                    <span class="selectgroup-button">XL</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6>Detalhes do produto:</h6>
                        <ul>
                            <li>100% algodão</li>
                            <li>240 g/m²</li>
                            <li>Ribana 2x1</li>
                            <li>Gráficos Doodles estampados em DTF</li>
                            <li>Modelagem oversized</li>
                        </ul>
                    </div>

                    <div class="col-12 mt-2">
                        <a href="javascript:void(0)" class="btn btn-icon icon-left btn-dark">
                            <i class="fas fa-cart-plus"></i>&nbsp;Adicionar ao carrinho
                        </a>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="form-group mb-2">
                            <label for="cep">Calcular prazo de entrega</label>
                            <input type="text" id="cep" name="cep" onkeyup="consultarFrete()" placeholder="00000-000" class="form-control">
                        </div>

                        <div class="accordion border">
                            <div class="accordion-header bg-black d-flex justify-content-between align-items-center" role="button" data-bs-toggle="collapse" data-bs-target="#painel-Frete" aria-expanded="false">
                                <h4>Prazo de entrega</h4>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="accordion-body collapse" id="painel-Frete" data-parent="#accordion">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Envio</th>
                                            <th>Prazo</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PAC</td>
                                            <td>10 Dias úteis.</td>
                                            <td>R$ 10,00</td>
                                        </tr>
                                        <tr>
                                            <td>SEDEX</td>
                                            <td>7 Dias úteis.</td>
                                            <td>R$ 10,00</td>
                                        </tr>
                                        <tr>
                                            <td>Transportadora</td>
                                            <td>3 Dias úteis.</td>
                                            <td>R$ 10,00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 mt-2 mt-md-3">
                        <div class="accordion border">
                            <div class="accordion-header bg-black d-flex justify-content-between align-items-center" role="button" data-bs-toggle="collapse" data-bs-target="#painelPagamento" aria-expanded="false">
                                <h4>Formas de pagamento</h4>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="accordion-body collapse" id="painelPagamento" data-parent="#accordion">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Método</th>
                                            <th>Parcelas</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Pix</td>
                                            <td>1x</td>
                                            <td>R$ 10,00</td>
                                        </tr>
                                        <tr>
                                            <td>Boleto bancário</td>
                                            <td>1x</td>
                                            <td>R$ 10,00</td>
                                        </tr>
                                        <tr>
                                            <td>Crédito</td>
                                            <td>1x</td>
                                            <td>R$ 10,00</td>
                                        </tr>
                                        <tr>
                                            <td>Crédito</td>
                                            <td>2x</td>
                                            <td>R$ 5,99</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 align-items-center">
                        <img alt="image" src="https://cdn.highcompanybr.com/wp-content/uploads/2024/10/D7_Tee_Dondi_SizeChart.jpg" class="img-thumbnail img-fluid" width="600" height="600" />
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include('layouts/footer.php') ?>

<script>
    function changeImage(imageUrl) {
        $('#imagemPrincipal').attr('src', imageUrl);
    }

    $('#cep').mask('00000-000');

    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', function() {
            let icon = this.querySelector('i');
            let isCollapsed = this.getAttribute('aria-expanded') === 'true';

            if (isCollapsed) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });
</script>