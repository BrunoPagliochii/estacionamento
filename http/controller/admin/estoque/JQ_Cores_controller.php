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
if ($_POST["JQueryFunction"] == 'novaCor') {
    $response = array();

    $hexadecimal = $_POST['hexadecimal'];
    $descricao = $_POST['descricao'];

    $query = "INSERT INTO EST_CORES (DESCRICAO, HEXADECIMAL, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$descricao', '$hexadecimal', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'descricao' => $descricao,
            'hexadecimal' => $hexadecimal,
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


if ($_POST["JQueryFunction"] == 'editarCor') {
    $response = array();

    $idCor = $_POST['idCor'];
    $hexadecimal = $_POST['hexadecimal'];
    $descricao = $_POST['descricao'];

    // Query de update
    $query = "UPDATE EST_CORES SET
        DESCRICAO = '$descricao',
        HEXADECIMAL = '$hexadecimal', 
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$idCor' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT DESCRICAO, HEXADECIMAL, ATIVO FROM EST_CORES 
        WHERE ID = '$idCor' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $idCor,
                'descricao' => $dados['DESCRICAO'],
                'hexadecimal' => $dados['HEXADECIMAL'],
                'ativo' => $dados['ATIVO']
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar dados atualizados.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'msg' => mysqli_error($conexao)
        );
    }

    echo json_encode($response);
}


if ($_POST["JQueryFunction"] == 'inativarCor') {
    $response = array();

    $ID = $_POST['ID'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE EST_CORES SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT DESCRICAO, HEXADECIMAL, ATIVO FROM EST_CORES 
        WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $ID,
                'descricao' => $dados['DESCRICAO'],
                'hexadecimal' => $dados['HEXADECIMAL'],
                'ativo' => $dados['ATIVO']
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar dados atualizados.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'msg' => mysqli_error($conexao)
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'deletarCor') {
    $response = array();

    $ID = $_POST['ID'];

    $query = "DELETE FROM EST_CORES WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' => 'success',
            'id' => $ID,
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
