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

if ($_POST["JQueryFunction"] == 'novaAliquota') {
    $response = array();

    $descricao = $_POST['descricao'];
    $baseIcms = $_POST['baseIcms'];
    $baseIcmsFe = $_POST['baseIcmsFe'];
    $baseIcmsSt = $_POST['baseIcmsSt'];
    $AC = $_POST['AC'];
    $AL = $_POST['AL'];
    $AM = $_POST['AM'];
    $AP = $_POST['AP'];
    $BA = $_POST['BA'];
    $CE = $_POST['CE'];
    $DF = $_POST['DF'];
    $ES = $_POST['ES'];
    $GO = $_POST['GO'];
    $MA = $_POST['MA'];
    $MG = $_POST['MG'];
    $MS = $_POST['MS'];
    $MT = $_POST['MT'];
    $PA = $_POST['PA'];
    $PB = $_POST['PB'];
    $PE = $_POST['PE'];
    $PI = $_POST['PI'];
    $PR = $_POST['PR'];
    $RJ = $_POST['RJ'];
    $RN = $_POST['RN'];
    $RO = $_POST['RO'];
    $RR = $_POST['RR'];
    $RS = $_POST['RS'];
    $SC = $_POST['SC'];
    $SE = $_POST['SE'];
    $SP = $_POST['SP'];
    $TO = $_POST['TO'];
    $diferimento = $_POST['diferimento'];
    
    $query = "INSERT INTO FIS_TAXAS_ICMS (DESCRICAO, BASE_ICMS, BASE_ICMS_FE, BASE_ICMS_ST, AC, AL, AM, AP, BA, CE, DF, ES, `GO`, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, `TO`, DIFERIMENTO, EMPRESA_ID, INCLUIDOPOR, INCLUIDOEM)
    VALUES ('$descricao', '$baseIcms', '$baseIcmsFe', '$baseIcmsSt', '$AC', '$AL', '$AM', '$AP', '$BA', '$CE', '$DF', '$ES', '$GO', '$MA', '$MG', '$MS', '$MT', '$PA', '$PB', '$PE', '$PI', '$PR', '$RJ', '$RN', '$RO', '$RR', '$RS', '$SC', '$SE', '$SP', '$TO', '$diferimento', '$IDEMPRESAUSUARIOMODEL', '$IDUSUARIOMODEL', NOW())";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'ativo' => 'S',
            'descricao' => $descricao,
            'base_icms' => $baseIcms,
            'base_icms_fe' => $baseIcmsFe,
            'base_icms_st' => $baseIcmsSt,
            'ac' => $AC,
            'al' => $AL,
            'am' => $AM,
            'ap' => $AP,
            'ba' => $BA,
            'ce' => $CE,
            'df' => $DF,
            'es' => $ES,
            'go' => $GO,
            'ma' => $MA,
            'mg' => $MG,
            'ms' => $MS,
            'mt' => $MT,
            'pa' => $PA,
            'pb' => $PB,
            'pe' => $PE,
            'pi' => $PI,
            'pr' => $PR,
            'rj' => $RJ,
            'rn' => $RN,
            'ro' => $RO,
            'ro' => $RO,
            'rr' => $RR,
            'rs' => $RS,
            'sc' => $SC,
            'se' => $SE,
            'sp' => $SP,
            'to' => $TO,
            'diferimento' => $diferimento
        );
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarAliquota') {
    $response = array();
    
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $baseIcms = $_POST['baseIcms'];
    $baseIcmsFe = $_POST['baseIcmsFe'];
    $baseIcmsSt = $_POST['baseIcmsSt'];
    $AC = $_POST['AC'];
    $AL = $_POST['AL'];
    $AM = $_POST['AM'];
    $AP = $_POST['AP'];
    $BA = $_POST['BA'];
    $CE = $_POST['CE'];
    $DF = $_POST['DF'];
    $ES = $_POST['ES'];
    $GO = $_POST['GO'];
    $MA = $_POST['MA'];
    $MG = $_POST['MG'];
    $MS = $_POST['MS'];
    $MT = $_POST['MT'];
    $PA = $_POST['PA'];
    $PB = $_POST['PB'];
    $PE = $_POST['PE'];
    $PI = $_POST['PI'];
    $PR = $_POST['PR'];
    $RJ = $_POST['RJ'];
    $RN = $_POST['RN'];
    $RO = $_POST['RO'];
    $RR = $_POST['RR'];
    $RS = $_POST['RS'];
    $SC = $_POST['SC'];
    $SE = $_POST['SE'];
    $SP = $_POST['SP'];
    $TO = $_POST['TO'];
    $diferimento = $_POST['diferimento'];
    
    // Atualizar os dados
    $query = "UPDATE FIS_TAXAS_ICMS 
              SET DESCRICAO = '$descricao', BASE_ICMS = '$baseIcms', BASE_ICMS_FE = '$baseIcmsFe', BASE_ICMS_ST = '$baseIcmsSt', AC = '$AC', AL = '$AL', AM = '$AM', AP = '$AP', BA = '$BA', CE = '$CE', DF = '$DF', ES = '$ES', `GO` = '$GO', MA = '$MA', MG = '$MG', MS = '$MS', MT = '$MT', PA = '$PA', PB = '$PB', PE = '$PE', PI = '$PI', PR = '$PR', RJ = '$RJ', RN = '$RN', RO = '$RO', RR = '$RR', RS = '$RS', SC = '$SC', SE = '$SE', SP = '$SP', `TO` = '$TO', DIFERIMENTO = '$diferimento', ALTERADOPOR = '$IDUSUARIOMODEL', ALTERADOEM = NOW()
              WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Seleciona os dados atualizados
        $selectQuery = "SELECT ID, DESCRICAO, BASE_ICMS, BASE_ICMS_FE, BASE_ICMS_ST, AC, AL, AM, AP, BA, CE, DF, ES, `GO`, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, `TO`, DIFERIMENTO, ATIVO 
                        FROM FIS_TAXAS_ICMS 
                        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $result = mysqli_query($conexao, $selectQuery);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $response = array(
                'status' =>  'success',
                'msg' =>  'Atualização realizada com sucesso!',
                'id' => $row['ID'],
                'ativo' => $row['ATIVO'],
                'descricao' => $row['DESCRICAO'],
                'base_icms' => $row['BASE_ICMS'],
                'base_icms_fe' => $row['BASE_ICMS_FE'],
                'ac' => $row['AC'],
                'al' => $row['AL'],
                'am' => $row['AM'],
                'ap' => $row['AP'],
                'ba' => $row['BA'],
                'ce' => $row['CE'],
                'df' => $row['DF'],
                'es' => $row['ES'],
                'go' => $row['GO'],
                'ma' => $row['MA'],
                'mg' => $row['MG'],
                'ms' => $row['MS'],
                'mt' => $row['MT'],
                'pa' => $row['PA'],
                'pb' => $row['PB'],
                'pe' => $row['PE'],
                'pi' => $row['PI'],
                'pr' => $row['PR'],
                'rj' => $row['RJ'],
                'rn' => $row['RN'],
                'ro' => $row['RO'],
                'rr' => $row['RR'],
                'rs' => $row['RS'],
                'sc' => $row['SC'],
                'se' => $row['SE'],
                'sp' => $row['SP'],
                'to' => $row['TO'],
                'diferimento' => $row['DIFERIMENTO']
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar os dados atualizados.'
            );
        }
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'inativarAliquota') {
    $response = array();
    
    $id = $_POST['id'];
    
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    // Atualizar os dados
    $query = "UPDATE FIS_TAXAS_ICMS SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Seleciona os dados atualizados
        $selectQuery = "SELECT ID, DESCRICAO, BASE_ICMS, BASE_ICMS_FE, BASE_ICMS_ST, AC, AL, AM, AP, BA, CE, DF, ES, `GO`, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, `TO`, DIFERIMENTO, ATIVO 
                        FROM FIS_TAXAS_ICMS 
                        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $result = mysqli_query($conexao, $selectQuery);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $response = array(
                'status' =>  'success',
                'msg' =>  'Atualização realizada com sucesso!',
                'id' => $row['ID'],
                'ativo' => $row['ATIVO'],
                'descricao' => $row['DESCRICAO'],
                'base_icms' => $row['BASE_ICMS'],
                'base_icms_fe' => $row['BASE_ICMS_FE'],
                'base_icms_st' => $row['BASE_ICMS_ST'],
                'ac' => $row['AC'],
                'al' => $row['AL'],
                'am' => $row['AM'],
                'ap' => $row['AP'],
                'ba' => $row['BA'],
                'ce' => $row['CE'],
                'df' => $row['DF'],
                'es' => $row['ES'],
                'go' => $row['GO'],
                'ma' => $row['MA'],
                'mg' => $row['MG'],
                'ms' => $row['MS'],
                'mt' => $row['MT'],
                'pa' => $row['PA'],
                'pb' => $row['PB'],
                'pe' => $row['PE'],
                'pi' => $row['PI'],
                'pr' => $row['PR'],
                'rj' => $row['RJ'],
                'rn' => $row['RN'],
                'ro' => $row['RO'],
                'rr' => $row['RR'],
                'rs' => $row['RS'],
                'sc' => $row['SC'],
                'se' => $row['SE'],
                'sp' => $row['SP'],
                'to' => $row['TO'],
                'diferimento' => $row['DIFERIMENTO']
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar os dados atualizados.'
            );
        }
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'deletarAliquota') {
    $response = array();

    $id = $_POST['id'];

    $query = "DELETE FROM FIS_TAXAS_ICMS WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' => 'success',
            'id' => $id,
            'msg' => 'Cadastro foi excluído!',
        );
    } else {
        $response = array(
            'status' => 'error',
            'msg' => mysqli_error($conexao)
        );
    }

    echo json_encode($response);
}