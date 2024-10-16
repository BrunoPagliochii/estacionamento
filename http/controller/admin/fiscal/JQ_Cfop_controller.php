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

if ($_POST["JQueryFunction"] == 'novoCfop') {
    $response = array();

    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];
    $movimentaEstoque = $_POST['movimentaEstoque'];
    $calculaIpi = $_POST['calculaIpi'];
    $retencao = $_POST['retencao'];

    $query = "INSERT INTO FIS_CFOP (CODIGO, DESCRICAO, MOVIMENTA_ESTOQUE, CALCULA_IPI, RETENCAO, EMPRESA_ID, INCLUIDOPOR, INCLUIDOEM)
    VALUES ('$codigo', '$descricao', '$movimentaEstoque', '$calculaIpi', '$retencao', '$IDEMPRESAUSUARIOMODEL', '$IDUSUARIOMODEL', NOW())";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'descricao' => $descricao,
            'codigo' => $codigo,
            'movimenta_estoque' => $movimentaEstoque,
            'calcula_ipi' => $calculaIpi,
            'retencao' => $retencao,
            'ativo' => 'S',
        );
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarCfop') {
    $response = array();

    $id = $_POST['id']; 
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];
    $movimentaEstoque = $_POST['movimentaEstoque'];
    $calculaIpi = $_POST['calculaIpi'];
    $retencao = $_POST['retencao'];

    // Atualizar os dados
    $query = "UPDATE FIS_CFOP 
              SET CODIGO = '$codigo', DESCRICAO = '$descricao', MOVIMENTA_ESTOQUE = '$movimentaEstoque', CALCULA_IPI = '$calculaIpi', RETENCAO = '$retencao', ALTERADOPOR = '$IDUSUARIOMODEL', ALTERADOEM = NOW()
              WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Seleciona os dados atualizados
        $selectQuery = "SELECT ID, CODIGO, DESCRICAO, MOVIMENTA_ESTOQUE, CALCULA_IPI, RETENCAO, ATIVO
                        FROM FIS_CFOP 
                        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $result = mysqli_query($conexao, $selectQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $response = array(
                'status' =>  'success',
                'msg' =>  'Atualização realizada com sucesso!',
                'id' => $row['ID'],
                'codigo' => $row['CODIGO'],
                'descricao' => $row['DESCRICAO'],
                'movimenta_estoque' => $row['MOVIMENTA_ESTOQUE'],
                'calcula_ipi' => $row['CALCULA_IPI'],
                'retencao' => $row['RETENCAO'],
                'ativo' => $row['ATIVO'],
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

if ($_POST["JQueryFunction"] == 'inativarCfop') {
    $response = array();

    $id = $_POST['id']; 
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    // Atualizar os dados
    $query = "UPDATE FIS_CFOP SET
        ATIVO = '$status', ALTERADOPOR = '$IDUSUARIOMODEL', ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Seleciona os dados atualizados
        $selectQuery = "SELECT ID, CODIGO, DESCRICAO, MOVIMENTA_ESTOQUE, CALCULA_IPI, RETENCAO, ATIVO
                        FROM FIS_CFOP 
                        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $result = mysqli_query($conexao, $selectQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $response = array(
                'status' =>  'success',
                'msg' =>  'Atualização realizada com sucesso!',
                'id' => $row['ID'],
                'codigo' => $row['CODIGO'],
                'descricao' => $row['DESCRICAO'],
                'movimenta_estoque' => $row['MOVIMENTA_ESTOQUE'],
                'calcula_ipi' => $row['CALCULA_IPI'],
                'retencao' => $row['RETENCAO'],
                'ativo' => $row['ATIVO'],
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

if ($_POST["JQueryFunction"] == 'deletarCfop') {
    $response = array();

    $id = $_POST['id'];

    $query = "DELETE FROM FIS_CFOP WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

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