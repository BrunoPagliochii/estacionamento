<?php
if (file_exists('../lib/PHP_conecta.php')) {
    require_once '../lib/jwt_auth_functions.php';
    require_once '../lib/PHP_conecta.php';
    require_once '../functions/helpers.php';
} else if (file_exists('../../lib/PHP_conecta.php')) {
    require_once '../../lib/jwt_auth_functions.php';
    require_once '../../lib/PHP_conecta.php';
    require_once '../../functions/helpers.php';
} else if (file_exists('../../../lib/PHP_conecta.php')) {
    require_once '../../../lib/jwt_auth_functions.php';
    require_once '../../../lib/PHP_conecta.php';
    require_once '../../../functions/helpers.php';
} else if (file_exists('../../../../lib/PHP_conecta.php')) {
    require_once '../../../../lib/jwt_auth_functions.php';
    require_once '../../../../lib/PHP_conecta.php';
    require_once '../../../../functions/helpers.php';
}

// --------------------------------------------------------------------
$emailUsuario_token = validateJWTToken();
// --------------------------------------------------------------------
$query = "SELECT A.NOME, A.EMAIL, A.ID, B.RAZAO_SOCIAL, A.EMPRESA_ID FROM BD_USUARIOS A 
          INNER JOIN BD_EMPRESAS B ON B.ID = A.EMPRESA_ID 
          WHERE A.ATIVO = 'S' AND B.ATIVO = 'S' AND A.EMAIL = ?";

$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $emailUsuario_token);
$stmt->execute();
$result = $stmt->get_result();

if ($dadossiderbar = $result->fetch_assoc()) {
    $IDUSUARIOMODEL = $dadossiderbar['ID'];
    $NOMEUSUARIOMODEL = $dadossiderbar['NOME'];
    $EMAILUSUARIOMODEL = $dadossiderbar['EMAIL'];
    $EMPRESAUSUARIOMODEL = $dadossiderbar['RAZAO_SOCIAL'];
    $IDEMPRESAUSUARIOMODEL = $dadossiderbar['EMPRESA_ID'];
}
// --------------------------------------------------------------------

if ($_POST["JQueryFunction"] == 'preencherTabelaDeAliquotasIcms') {
    $response = array();

    $resultado = $conexao->query("SELECT ID, ATIVO, DESCRICAO, BASE_ICMS, BASE_ICMS_FE, BASE_ICMS_ST, AC, AL, AM, AP, BA, CE, DF, ES, `GO`, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RO, RR, RS, SC, SE, SP, `TO`, DIFERIMENTO FROM FIS_TAXAS_ICMS");
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dados = $resultado->fetch_assoc()) {
        $response[] = array(
            'id' => $dados['ID'],
            'ativo' => $dados['ATIVO'],
            'descricao' => $dados['DESCRICAO'],
            'base_icms' => $dados['BASE_ICMS'],
            'base_icms_fe' => $dados['BASE_ICMS_FE'],
            'base_icms_st' => $dados['BASE_ICMS_ST'],
            'ac' => $dados['AC'],
            'al' => $dados['AL'],
            'am' => $dados['AM'],
            'ap' => $dados['AP'],
            'ba' => $dados['BA'],
            'ce' => $dados['CE'],
            'df' => $dados['DF'],
            'es' => $dados['ES'],
            'go' => $dados['GO'],
            'ma' => $dados['MA'],
            'mg' => $dados['MG'],
            'ms' => $dados['MS'],
            'mt' => $dados['MT'],
            'pa' => $dados['PA'],
            'pb' => $dados['PB'],
            'pe' => $dados['PE'],
            'pi' => $dados['PI'],
            'pr' => $dados['PR'],
            'rj' => $dados['RJ'],
            'rn' => $dados['RN'],
            'ro' => $dados['RO'],
            'ro' => $dados['RO'],
            'rr' => $dados['RR'],
            'rs' => $dados['RS'],
            'sc' => $dados['SC'],
            'se' => $dados['SE'],
            'sp' => $dados['SP'],
            'to' => $dados['TO'],
            'diferimento' => $dados['DIFERIMENTO'],
        );
    }

    echo json_encode($response);
}
