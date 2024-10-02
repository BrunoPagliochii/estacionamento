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

if ($_POST["JQueryFunction"] == 'cadastrarGrupo') {
    $response = array();

    $descricao = $_POST['descricao'];

    $query = "INSERT INTO EST_GRUPOS (DESCRICAO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$descricao', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'descricao' => $descricao,
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

if ($_POST["JQueryFunction"] == 'editarGrupo') {
    $response = array();

    $id = $_POST['id'];
    $descricao = $_POST['descricao'];

    $query = "UPDATE EST_GRUPOS SET
        DESCRICAO = '$descricao',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT DESCRICAO, ATIVO FROM EST_GRUPOS 
        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $id,
                'descricao' => $dados['DESCRICAO'],
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

if ($_POST["JQueryFunction"] == 'inativarGrupo') {
    $response = array();

    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE EST_GRUPOS SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT DESCRICAO, ATIVO FROM EST_GRUPOS 
        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $id,
                'descricao' => $dados['DESCRICAO'],
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

if ($_POST["JQueryFunction"] == 'deletarGrupo') {
    $response = array();

    $id = $_POST['id'];

    $query = "DELETE FROM EST_GRUPOS WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

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
