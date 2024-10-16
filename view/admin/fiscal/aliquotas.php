<?php
$dados = array(
    'NomePagina' => 'Alíquotas ICMS',
    'MenuModulo' => 'Fiscal'
);
?>

<?php include('../layouts/body.php') ?>


<div class="col-12">
    <div class="card card-dark">

        <div class="card-header">
            <h4><?= $dados['NomePagina'] ?></h4>
            <div class="card-header-action">

                <a href="javascript:void(0)">
                    <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroAliquota"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeAliquotas" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descricao</th>
                            <th>Base ICMS</th>
                            <th>Base ICMS F.E</th>
                            <th>Base ICMS ST</th>
                            <th>AC</th>
                            <th>AL</th>
                            <th>AM</th>
                            <th>AP</th>
                            <th>BA</th>
                            <th>CE</th>
                            <th>DF</th>
                            <th>ES</th>
                            <th>GO</th>
                            <th>MA</th>
                            <th>MG</th>
                            <th>MS</th>
                            <th>MT</th>
                            <th>PA</th>
                            <th>PB</th>
                            <th>PE</th>
                            <th>PI</th>
                            <th>PR</th>
                            <th>RJ</th>
                            <th>RN</th>
                            <th>RO</th>
                            <th>RR</th>
                            <th>RS</th>
                            <th>SC</th>
                            <th>SE</th>
                            <th>SP</th>
                            <th>TO</th>
                            <th>Diferimento</th>
                            <th>Funções</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../layouts/footer.php') ?>

<!-- Modal Cadastro de Aliquotas ICMS -->
<div class="modal fade" id="modal-cadastroAliquota" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="descricao">Descrição *</label>
                        <input maxlength="300" type="text" id="descricao" name="descricao" class="form-control">
                    </div>

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="baseIcms">Base ICMS *</label>
                        <input maxlength="8" type="text" id="baseIcms" value="100,00" name="baseIcms" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="baseIcmsFe">Base ICMS FE *</label>
                        <input maxlength="8" type="text" id="baseIcmsFe" value="100,00" name="baseIcmsFe" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="baseIcmsSt">Base ICMS ST *</label>
                        <input maxlength="8" type="text" id="baseIcmsSt" name="baseIcmsSt" value="100,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="AC">AC *</label>
                        <input maxlength="8" type="text" id="AC" name="AC" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="AL">AL *</label>
                        <input maxlength="8" type="text" id="AL" name="AL" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="AM">AM *</label>
                        <input maxlength="8" type="text" id="AM" name="AM" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="AP">AP *</label>
                        <input maxlength="8" type="text" id="AP" name="AP" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="BA">BA *</label>
                        <input maxlength="8" type="text" id="BA" name="BA" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="CE">CE *</label>
                        <input maxlength="8" type="text" id="CE" name="CE" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="DF">DF *</label>
                        <input maxlength="8" type="text" id="DF" name="DF" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="ES">ES *</label>
                        <input maxlength="8" type="text" id="ES" name="ES" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="GO">GO *</label>
                        <input maxlength="8" type="text" id="GO" name="GO" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MA">MA *</label>
                        <input maxlength="8" type="text" id="MA" name="MA" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MG">MG *</label>
                        <input maxlength="8" type="text" id="MG" name="MG" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MS">MS *</label>
                        <input maxlength="8" type="text" id="MS" name="MS" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MT">MT *</label>
                        <input maxlength="8" type="text" id="MT" name="MT" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PA">PA *</label>
                        <input maxlength="8" type="text" id="PA" name="PA" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PB">PB *</label>
                        <input maxlength="8" type="text" id="PB" name="PB" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PE">PE *</label>
                        <input maxlength="8" type="text" id="PE" name="PE" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PI">PI *</label>
                        <input maxlength="8" type="text" id="PI" name="PI" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PR">PR *</label>
                        <input maxlength="8" type="text" id="PR" name="PR" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RJ">RJ *</label>
                        <input maxlength="8" type="text" id="RJ" name="RJ" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RN">RN *</label>
                        <input maxlength="8" type="text" id="RN" name="RN" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RO">RO *</label>
                        <input maxlength="8" type="text" id="RO" name="RO" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RR">RR *</label>
                        <input maxlength="8" type="text" id="RR" name="RR" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RS">RS *</label>
                        <input maxlength="8" type="text" id="RS" name="RS" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="SC">SC *</label>
                        <input maxlength="8" type="text" id="SC" name="SC" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="SE">SE *</label>
                        <input maxlength="8" type="text" id="SE" name="SE" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="SP">SP *</label>
                        <input maxlength="8" type="text" id="SP" name="SP" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="TO">TO *</label>
                        <input maxlength="8" type="text" id="TO" name="TO" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-6 mb-2">
                        <label for="diferimento">Diferimento *</label>
                        <input maxlength="8" type="text" id="diferimento" name="diferimento" value="0,00" class="form-control moeda">
                    </div>

                </div>
                <button id="btnCadastrarAliquotas" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar de Aliquotas ICMS -->
<div class="modal fade" id="modal-editarAliquota" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" id="idAliquota" name="idAliquota">

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="300" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="baseIcmsEdt">Base ICMS *</label>
                        <input maxlength="8" type="text" id="baseIcmsEdt" value="100,00" name="baseIcmsEdt" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="baseIcmsFeEdt">Base ICMS FE *</label>
                        <input maxlength="8" type="text" id="baseIcmsFeEdt" value="100,00" name="baseIcmsFeEdt" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-3 mb-2">
                        <label for="baseIcmsStEdt">Base ICMS ST *</label>
                        <input maxlength="8" type="text" id="baseIcmsStEdt" name="baseIcmsStEdt" value="100,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="ACEdt">AC *</label>
                        <input maxlength="8" type="text" id="ACEdt" name="ACEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="ALEdt">AL *</label>
                        <input maxlength="8" type="text" id="ALEdt" name="ALEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="AMEdt">AM *</label>
                        <input maxlength="8" type="text" id="AMEdt" name="AMEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="APEdt">AP *</label>
                        <input maxlength="8" type="text" id="APEdt" name="APEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="BAEdt">BA *</label>
                        <input maxlength="8" type="text" id="BAEdt" name="BAEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="CEEdt">CE *</label>
                        <input maxlength="8" type="text" id="CEEdt" name="CEEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="DFEdt">DF *</label>
                        <input maxlength="8" type="text" id="DFEdt" name="DFEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="ESEdt">ES *</label>
                        <input maxlength="8" type="text" id="ESEdt" name="ESEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="GOEdt">GO *</label>
                        <input maxlength="8" type="text" id="GOEdt" name="GOEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MAEdt">MA *</label>
                        <input maxlength="8" type="text" id="MAEdt" name="MAEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MGEdt">MG *</label>
                        <input maxlength="8" type="text" id="MGEdt" name="MGEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MSEdt">MS *</label>
                        <input maxlength="8" type="text" id="MSEdt" name="MSEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="MTEdt">MT *</label>
                        <input maxlength="8" type="text" id="MTEdt" name="MTEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PAEdt">PA *</label>
                        <input maxlength="8" type="text" id="PAEdt" name="PAEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PBEdt">PB *</label>
                        <input maxlength="8" type="text" id="PBEdt" name="PBEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PEEdt">PE *</label>
                        <input maxlength="8" type="text" id="PEEdt" name="PEEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PIEdt">PI *</label>
                        <input maxlength="8" type="text" id="PIEdt" name="PIEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="PREdt">PR *</label>
                        <input maxlength="8" type="text" id="PREdt" name="PREdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RJEdt">RJ *</label>
                        <input maxlength="8" type="text" id="RJEdt" name="RJEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RNEdt">RN *</label>
                        <input maxlength="8" type="text" id="RNEdt" name="RNEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="ROEdt">RO *</label>
                        <input maxlength="8" type="text" id="ROEdt" name="ROEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RREdt">RR *</label>
                        <input maxlength="8" type="text" id="RREdt" name="RREdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="RSEdt">RS *</label>
                        <input maxlength="8" type="text" id="RSEdt" name="RSEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="SCEdt">SC *</label>
                        <input maxlength="8" type="text" id="SCEdt" name="SCEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="SEEdt">SE *</label>
                        <input maxlength="8" type="text" id="SEEdt" name="SEEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="SPEdt">SP *</label>
                        <input maxlength="8" type="text" id="SPEdt" name="SPEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-2 mb-2">
                        <label for="TOEdt">TO *</label>
                        <input maxlength="8" type="text" id="TOEdt" name="TOEdt" value="0,00" class="form-control moeda">
                    </div>

                    <div class="col-12 col-xl-6 mb-2">
                        <label for="diferimentoEdt">Diferimento *</label>
                        <input maxlength="8" type="text" id="diferimentoEdt" name="diferimentoEdt" value="0,00" class="form-control moeda">
                    </div>

                </div>
                <button id="btnEditarAliquota" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        preencherTabelaDeAliquotasIcms();

        function preencherTabelaDeAliquotasIcms() {

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'preencherTabelaDeAliquotasIcms');

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/model/admin/fiscal/JQ_Aliquotas_model.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(item) {

                    // Limpando o tbody antes de preencher
                    $('#tabelaDeAliquotas tbody').empty();

                    // Iterando sobre a resposta e adicionando linhas à tabela
                    item.forEach(function(response) {

                        let rowData = [
                            response.id,
                            response.descricao,
                            moedaFormat(response.base_icms),
                            moedaFormat(response.base_icms_fe),
                            moedaFormat(response.base_icms_st),
                            moedaFormat(response.ac),
                            moedaFormat(response.al),
                            moedaFormat(response.am),
                            moedaFormat(response.ap),
                            moedaFormat(response.ba),
                            moedaFormat(response.ce),
                            moedaFormat(response.df),
                            moedaFormat(response.es),
                            moedaFormat(response.go),
                            moedaFormat(response.ma),
                            moedaFormat(response.mg),
                            moedaFormat(response.ms),
                            moedaFormat(response.mt),
                            moedaFormat(response.pa),
                            moedaFormat(response.pb),
                            moedaFormat(response.pe),
                            moedaFormat(response.pi),
                            moedaFormat(response.pr),
                            moedaFormat(response.rj),
                            moedaFormat(response.rn),
                            moedaFormat(response.ro),
                            moedaFormat(response.rr),
                            moedaFormat(response.rs),
                            moedaFormat(response.sc),
                            moedaFormat(response.se),
                            moedaFormat(response.sp),
                            moedaFormat(response.to),
                            moedaFormat(response.diferimento),
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar"
                                    data-id="${response.id}"
                                    data-descricao="${response.descricao}"
                                    data-base_icms="${moedaFormat(response.base_icms)}"
                                    data-base_icms_fe="${moedaFormat(response.base_icms_fe)}"
                                    data-base_icms_st="${moedaFormat(response.base_icms_st)}"
                                    data-ac="${moedaFormat(response.ac)}"
                                    data-al="${moedaFormat(response.al)}"
                                    data-am="${moedaFormat(response.am)}"
                                    data-ap="${moedaFormat(response.ap)}"
                                    data-ba="${moedaFormat(response.ba)}"
                                    data-ce="${moedaFormat(response.ce)}"
                                    data-df="${moedaFormat(response.df)}"
                                    data-es="${moedaFormat(response.es)}"
                                    data-go="${moedaFormat(response.go)}"
                                    data-ma="${moedaFormat(response.ma)}"
                                    data-mg="${moedaFormat(response.mg)}"
                                    data-ms="${moedaFormat(response.ms)}"
                                    data-mt="${moedaFormat(response.mt)}"
                                    data-pa="${moedaFormat(response.pa)}"
                                    data-pb="${moedaFormat(response.pb)}"
                                    data-pe="${moedaFormat(response.pe)}"
                                    data-pi="${moedaFormat(response.pi)}"
                                    data-pr="${moedaFormat(response.pr)}"
                                    data-rj="${moedaFormat(response.rj)}"
                                    data-rn="${moedaFormat(response.rn)}"
                                    data-ro="${moedaFormat(response.ro)}"
                                    data-rr="${moedaFormat(response.rr)}"
                                    data-rs="${moedaFormat(response.rs)}"
                                    data-sc="${moedaFormat(response.sc)}"
                                    data-se="${moedaFormat(response.se)}"
                                    data-sp="${moedaFormat(response.sp)}"
                                    data-to="${moedaFormat(response.to)}"
                                    data-diferimento="${moedaFormat(response.diferimento)}"
                                
                                data-bs-toggle="modal" data-bs-target="#modal-editarAliquota" >
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarAliquota(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarAliquota(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeAliquotas', 'rowAliquota_', +response.id, rowData);

                    });
                }
            });
        }

        // Executa assim que o botão de salvar for clicado
        $('#btnCadastrarAliquotas').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'descricao',
                'baseIcms',
                'baseIcmsFe',
                'baseIcmsSt',
                'AC',
                'AL',
                'AM',
                'AP',
                'BA',
                'CE',
                'DF',
                'ES',
                'GO',
                'MA',
                'MG',
                'MS',
                'MT',
                'PA',
                'PB',
                'PE',
                'PI',
                'PR',
                'RJ',
                'RN',
                'RO',
                'RR',
                'RS',
                'SC',
                'SE',
                'SP',
                'TO',
                'diferimento',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'novaAliquota');
            form_data.append('descricao', $('#descricao').val());
            form_data.append('baseIcms', $('#baseIcms').val());
            form_data.append('baseIcmsFe', $('#baseIcmsFe').val());
            form_data.append('baseIcmsSt', $('#baseIcmsSt').val());
            form_data.append('AC', $('#AC').val());
            form_data.append('AL', $('#AL').val());
            form_data.append('AM', $('#AM').val());
            form_data.append('AP', $('#AP').val());
            form_data.append('BA', $('#BA').val());
            form_data.append('CE', $('#CE').val());
            form_data.append('DF', $('#DF').val());
            form_data.append('ES', $('#ES').val());
            form_data.append('GO', $('#GO').val());
            form_data.append('MA', $('#MA').val());
            form_data.append('MG', $('#MG').val());
            form_data.append('MS', $('#MS').val());
            form_data.append('MT', $('#MT').val());
            form_data.append('PA', $('#PA').val());
            form_data.append('PB', $('#PB').val());
            form_data.append('PE', $('#PE').val());
            form_data.append('PI', $('#PI').val());
            form_data.append('PR', $('#PR').val());
            form_data.append('RJ', $('#RJ').val());
            form_data.append('RN', $('#RN').val());
            form_data.append('RO', $('#RO').val());
            form_data.append('RR', $('#RR').val());
            form_data.append('RS', $('#RS').val());
            form_data.append('SC', $('#SC').val());
            form_data.append('SE', $('#SE').val());
            form_data.append('SP', $('#SP').val());
            form_data.append('TO', $('#TO').val());
            form_data.append('diferimento', $('#diferimento').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Aliquotas_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.descricao,
                            moedaFormat(response.base_icms),
                            moedaFormat(response.base_icms_fe),
                            moedaFormat(response.base_icms_st),
                            moedaFormat(response.ac),
                            moedaFormat(response.al),
                            moedaFormat(response.am),
                            moedaFormat(response.ap),
                            moedaFormat(response.ba),
                            moedaFormat(response.ce),
                            moedaFormat(response.df),
                            moedaFormat(response.es),
                            moedaFormat(response.go),
                            moedaFormat(response.ma),
                            moedaFormat(response.mg),
                            moedaFormat(response.ms),
                            moedaFormat(response.mt),
                            moedaFormat(response.pa),
                            moedaFormat(response.pb),
                            moedaFormat(response.pe),
                            moedaFormat(response.pi),
                            moedaFormat(response.pr),
                            moedaFormat(response.rj),
                            moedaFormat(response.rn),
                            moedaFormat(response.ro),
                            moedaFormat(response.rr),
                            moedaFormat(response.rs),
                            moedaFormat(response.sc),
                            moedaFormat(response.se),
                            moedaFormat(response.sp),
                            moedaFormat(response.to),
                            moedaFormat(response.diferimento),
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar"
                                    data-id="${response.id}"
                                    data-descricao="${response.descricao}"
                                    data-base_icms="${moedaFormat(response.base_icms)}"
                                    data-base_icms_fe="${moedaFormat(response.base_icms_fe)}"
                                    data-base_icms_st="${moedaFormat(response.base_icms_st)}"
                                    data-ac="${moedaFormat(response.ac)}"
                                    data-al="${moedaFormat(response.al)}"
                                    data-am="${moedaFormat(response.am)}"
                                    data-ap="${moedaFormat(response.ap)}"
                                    data-ba="${moedaFormat(response.ba)}"
                                    data-ce="${moedaFormat(response.ce)}"
                                    data-df="${moedaFormat(response.df)}"
                                    data-es="${moedaFormat(response.es)}"
                                    data-go="${moedaFormat(response.go)}"
                                    data-ma="${moedaFormat(response.ma)}"
                                    data-mg="${moedaFormat(response.mg)}"
                                    data-ms="${moedaFormat(response.ms)}"
                                    data-mt="${moedaFormat(response.mt)}"
                                    data-pa="${moedaFormat(response.pa)}"
                                    data-pb="${moedaFormat(response.pb)}"
                                    data-pe="${moedaFormat(response.pe)}"
                                    data-pi="${moedaFormat(response.pi)}"
                                    data-pr="${moedaFormat(response.pr)}"
                                    data-rj="${moedaFormat(response.rj)}"
                                    data-rn="${moedaFormat(response.rn)}"
                                    data-ro="${moedaFormat(response.ro)}"
                                    data-rr="${moedaFormat(response.rr)}"
                                    data-rs="${moedaFormat(response.rs)}"
                                    data-sc="${moedaFormat(response.sc)}"
                                    data-se="${moedaFormat(response.se)}"
                                    data-sp="${moedaFormat(response.sp)}"
                                    data-to="${moedaFormat(response.to)}"
                                    data-diferimento="${moedaFormat(response.diferimento)}"
                                
                                data-bs-toggle="modal" data-bs-target="#modal-editarAliquota" >
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarAliquota(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarAliquota(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeAliquotas', 'rowAliquota_', +response.id, rowData);
                        $('#modal-cadastroAliquota').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });

        // Executa assim que o botão de salvar for clicado
        $('#btnEditarAliquota').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'idAliquota',
                'descricaoEdt',
                'baseIcmsEdt',
                'baseIcmsFeEdt',
                'baseIcmsStEdt',
                'ACEdt',
                'ALEdt',
                'AMEdt',
                'APEdt',
                'BAEdt',
                'CEEdt',
                'DFEdt',
                'ESEdt',
                'GOEdt',
                'MAEdt',
                'MGEdt',
                'MSEdt',
                'MTEdt',
                'PAEdt',
                'PBEdt',
                'PEEdt',
                'PIEdt',
                'PREdt',
                'RJEdt',
                'RNEdt',
                'ROEdt',
                'RREdt',
                'RSEdt',
                'SCEdt',
                'SEEdt',
                'SPEdt',
                'TOEdt',
                'diferimentoEdt',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'editarAliquota');
            form_data.append('id', $('#idAliquota').val());
            form_data.append('descricao', $('#descricaoEdt').val());
            form_data.append('baseIcms', $('#baseIcmsEdt').val());
            form_data.append('baseIcmsFe', $('#baseIcmsFeEdt').val());
            form_data.append('baseIcmsSt', $('#baseIcmsSeEdt').val());
            form_data.append('AC', $('#ACEdt').val());
            form_data.append('AL', $('#ALEdt').val());
            form_data.append('AM', $('#AMEdt').val());
            form_data.append('AP', $('#APEdt').val());
            form_data.append('BA', $('#BAEdt').val());
            form_data.append('CE', $('#CEEdt').val());
            form_data.append('DF', $('#DFEdt').val());
            form_data.append('ES', $('#ESEdt').val());
            form_data.append('GO', $('#GOEdt').val());
            form_data.append('MA', $('#MAEdt').val());
            form_data.append('MG', $('#MGEdt').val());
            form_data.append('MS', $('#MSEdt').val());
            form_data.append('MT', $('#MTEdt').val());
            form_data.append('PA', $('#PAEdt').val());
            form_data.append('PB', $('#PBEdt').val());
            form_data.append('PE', $('#PEEdt').val());
            form_data.append('PI', $('#PIEdt').val());
            form_data.append('PR', $('#PREdt').val());
            form_data.append('RJ', $('#RJEdt').val());
            form_data.append('RN', $('#RNEdt').val());
            form_data.append('RO', $('#ROEdt').val());
            form_data.append('RR', $('#RREdt').val());
            form_data.append('RS', $('#RSEdt').val());
            form_data.append('SC', $('#SCEdt').val());
            form_data.append('SE', $('#SEEdt').val());
            form_data.append('SP', $('#SPEdt').val());
            form_data.append('TO', $('#TOEdt').val());
            form_data.append('diferimento', $('#diferimentoEdt').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Aliquotas_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.descricao,
                            moedaFormat(response.base_icms),
                            moedaFormat(response.base_icms_fe),
                            moedaFormat(response.base_icms_st),
                            moedaFormat(response.ac),
                            moedaFormat(response.al),
                            moedaFormat(response.am),
                            moedaFormat(response.ap),
                            moedaFormat(response.ba),
                            moedaFormat(response.ce),
                            moedaFormat(response.df),
                            moedaFormat(response.es),
                            moedaFormat(response.go),
                            moedaFormat(response.ma),
                            moedaFormat(response.mg),
                            moedaFormat(response.ms),
                            moedaFormat(response.mt),
                            moedaFormat(response.pa),
                            moedaFormat(response.pb),
                            moedaFormat(response.pe),
                            moedaFormat(response.pi),
                            moedaFormat(response.pr),
                            moedaFormat(response.rj),
                            moedaFormat(response.rn),
                            moedaFormat(response.ro),
                            moedaFormat(response.rr),
                            moedaFormat(response.rs),
                            moedaFormat(response.sc),
                            moedaFormat(response.se),
                            moedaFormat(response.sp),
                            moedaFormat(response.to),
                            moedaFormat(response.diferimento),
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar"
                                    data-id="${response.id}"
                                    data-descricao="${response.descricao}"
                                    data-base_icms="${moedaFormat(response.base_icms)}"
                                    data-base_icms_fe="${moedaFormat(response.base_icms_fe)}"
                                    data-base_icms_st="${moedaFormat(response.base_icms_st)}"
                                    data-ac="${moedaFormat(response.ac)}"
                                    data-al="${moedaFormat(response.al)}"
                                    data-am="${moedaFormat(response.am)}"
                                    data-ap="${moedaFormat(response.ap)}"
                                    data-ba="${moedaFormat(response.ba)}"
                                    data-ce="${moedaFormat(response.ce)}"
                                    data-df="${moedaFormat(response.df)}"
                                    data-es="${moedaFormat(response.es)}"
                                    data-go="${moedaFormat(response.go)}"
                                    data-ma="${moedaFormat(response.ma)}"
                                    data-mg="${moedaFormat(response.mg)}"
                                    data-ms="${moedaFormat(response.ms)}"
                                    data-mt="${moedaFormat(response.mt)}"
                                    data-pa="${moedaFormat(response.pa)}"
                                    data-pb="${moedaFormat(response.pb)}"
                                    data-pe="${moedaFormat(response.pe)}"
                                    data-pi="${moedaFormat(response.pi)}"
                                    data-pr="${moedaFormat(response.pr)}"
                                    data-rj="${moedaFormat(response.rj)}"
                                    data-rn="${moedaFormat(response.rn)}"
                                    data-ro="${moedaFormat(response.ro)}"
                                    data-rr="${moedaFormat(response.rr)}"
                                    data-rs="${moedaFormat(response.rs)}"
                                    data-sc="${moedaFormat(response.sc)}"
                                    data-se="${moedaFormat(response.se)}"
                                    data-sp="${moedaFormat(response.sp)}"
                                    data-to="${moedaFormat(response.to)}"
                                    data-diferimento="${moedaFormat(response.diferimento)}"
                                
                                data-bs-toggle="modal" data-bs-target="#modal-editarAliquota" >
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarAliquota(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarAliquota(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeAliquotas', 'rowAliquota_', +response.id, rowData);
                        $('#modal-editarAliquota').modal('hide');

                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });
    });

    $('#modal-editarAliquota').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idAliquota').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
        $('#baseIcmsEdt').val(button.data('base_icms'));
        $('#baseIcmsFeEdt').val(button.data('base_icms_fe'));
        $('#baseIcmsStEdt').val(button.data('base_icms_st'));
        $('#ACEdt').val(button.data('ac'));
        $('#ALEdt').val(button.data('al'));
        $('#AMEdt').val(button.data('am'));
        $('#APEdt').val(button.data('ap'));
        $('#BAEdt').val(button.data('ba'));
        $('#CEEdt').val(button.data('ce'));
        $('#DFEdt').val(button.data('df'));
        $('#ESEdt').val(button.data('es'));
        $('#GOEdt').val(button.data('go'));
        $('#MAEdt').val(button.data('ma'));
        $('#MGEdt').val(button.data('mg'));
        $('#MSEdt').val(button.data('ms'));
        $('#MTEdt').val(button.data('mt'));
        $('#PAEdt').val(button.data('pa'));
        $('#PBEdt').val(button.data('pb'));
        $('#PEEdt').val(button.data('pe'));
        $('#PIEdt').val(button.data('pi'));
        $('#PREdt').val(button.data('pr'));
        $('#RJEdt').val(button.data('rj'));
        $('#RNEdt').val(button.data('rn'));
        $('#ROEdt').val(button.data('ro'));
        $('#RREdt').val(button.data('rr'));
        $('#RSEdt').val(button.data('rs'));
        $('#SCEdt').val(button.data('sc'));
        $('#SEEdt').val(button.data('se'));
        $('#SPEdt').val(button.data('sp'));
        $('#TOEdt').val(button.data('to'));
        $('#diferimentoEdt').val(button.data('diferimento'));
    });

    async function inativarAliquota(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja alterar essa tributação?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'inativarAliquota');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Aliquotas_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    exibirToastr(response.msg, response.msg);
                    if (response.status == 'success') {

                        // Iterando sobre a resposta e adicionando linhas à tabela
                        let rowData = [
                            response.id,
                            response.descricao,
                            moedaFormat(response.base_icms),
                            moedaFormat(response.base_icms_fe),
                            moedaFormat(response.base_icms_st),
                            moedaFormat(response.ac),
                            moedaFormat(response.al),
                            moedaFormat(response.am),
                            moedaFormat(response.ap),
                            moedaFormat(response.ba),
                            moedaFormat(response.ce),
                            moedaFormat(response.df),
                            moedaFormat(response.es),
                            moedaFormat(response.go),
                            moedaFormat(response.ma),
                            moedaFormat(response.mg),
                            moedaFormat(response.ms),
                            moedaFormat(response.mt),
                            moedaFormat(response.pa),
                            moedaFormat(response.pb),
                            moedaFormat(response.pe),
                            moedaFormat(response.pi),
                            moedaFormat(response.pr),
                            moedaFormat(response.rj),
                            moedaFormat(response.rn),
                            moedaFormat(response.ro),
                            moedaFormat(response.rr),
                            moedaFormat(response.rs),
                            moedaFormat(response.sc),
                            moedaFormat(response.se),
                            moedaFormat(response.sp),
                            moedaFormat(response.to),
                            moedaFormat(response.diferimento),
                            `
                                <a class="btn btn-primary btn-action mr-1" title="Editar"
                                    data-id="${response.id}"
                                    data-descricao="${response.descricao}"
                                    data-base_icms="${moedaFormat(response.base_icms)}"
                                    data-base_icms_fe="${moedaFormat(response.base_icms_fe)}"
                                    data-base_icms_st="${moedaFormat(response.base_icms_st)}"
                                    data-ac="${moedaFormat(response.ac)}"
                                    data-al="${moedaFormat(response.al)}"
                                    data-am="${moedaFormat(response.am)}"
                                    data-ap="${moedaFormat(response.ap)}"
                                    data-ba="${moedaFormat(response.ba)}"
                                    data-ce="${moedaFormat(response.ce)}"
                                    data-df="${moedaFormat(response.df)}"
                                    data-es="${moedaFormat(response.es)}"
                                    data-go="${moedaFormat(response.go)}"
                                    data-ma="${moedaFormat(response.ma)}"
                                    data-mg="${moedaFormat(response.mg)}"
                                    data-ms="${moedaFormat(response.ms)}"
                                    data-mt="${moedaFormat(response.mt)}"
                                    data-pa="${moedaFormat(response.pa)}"
                                    data-pb="${moedaFormat(response.pb)}"
                                    data-pe="${moedaFormat(response.pe)}"
                                    data-pi="${moedaFormat(response.pi)}"
                                    data-pr="${moedaFormat(response.pr)}"
                                    data-rj="${moedaFormat(response.rj)}"
                                    data-rn="${moedaFormat(response.rn)}"
                                    data-ro="${moedaFormat(response.ro)}"
                                    data-rr="${moedaFormat(response.rr)}"
                                    data-rs="${moedaFormat(response.rs)}"
                                    data-sc="${moedaFormat(response.sc)}"
                                    data-se="${moedaFormat(response.se)}"
                                    data-sp="${moedaFormat(response.sp)}"
                                    data-to="${moedaFormat(response.to)}"
                                    data-diferimento="${moedaFormat(response.diferimento)}"
                                
                                data-bs-toggle="modal" data-bs-target="#modal-editarAliquota" >
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="btn btn-${response.ativo == 'S' ? 'warning' : 'success'} btn-action" title="${response.ativo == 'S' ? 'Inativar' : 'Ativar'}" onclick="inativarAliquota(${response.id}, '${response.ativo}')">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a class="btn btn-danger btn-action" title="Deletar" onclick="deletarAliquota(${response.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `
                        ];

                        updateRowInTable('#tabelaDeAliquotas', 'rowAliquota_', +response.id, rowData);

                    }
                }
            });
        }
    }

    async function deletarAliquota(id, status) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja remover essa tributação?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarAliquota');
            form_data.append('id', id);
            form_data.append('status', status);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/admin/fiscal/JQ_Aliquotas_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    exibirToastr(response.msg, response.msg);
                    if (response.status == 'success') {
                        removeRowFromTable('#tabelaDeAliquotas', 'rowAliquota_', +response.id);
                    }
                }
            });
        }
    }

    $('.moeda').maskMoney({
        thousands: '.',
        decimal: ',',
        prefix: '% ',
        allowZero: true,
        precision: 2,
        affixesStay: false
    });
</script>