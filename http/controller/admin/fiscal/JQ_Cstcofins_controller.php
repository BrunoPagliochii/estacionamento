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

if ($_POST["JQueryFunction"] == 'novoCstCofins') {
    $response = array();

    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];

    $query = "INSERT INTO FIS_CST_COFINS (CODIGO, DESCRICAO, INCLUIDOPOR, INCLUIDOEM)
    VALUES ('$codigo', '$descricao', '$IDUSUARIOMODEL', NOW())";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'descricao' => $descricao,
            'codigo' => $codigo,
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

if ($_POST["JQueryFunction"] == 'editarCstCofins') {
    $response = array();

    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $codigo = $_POST['codigo'];

    $query = "UPDATE FIS_CST_COFINS  SET
        DESCRICAO = '$descricao',
        CODIGO = '$codigo',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT DESCRICAO, CODIGO, ATIVO FROM FIS_CST_COFINS WHERE ID = '$id'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $id,
                'descricao' => $dados['DESCRICAO'],
                'codigo' => $dados['CODIGO'],
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

if ($_POST["JQueryFunction"] == 'inativarCstCofins') {
    $response = array();

    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE FIS_CST_COFINS  SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT CODIGO, DESCRICAO, ATIVO FROM FIS_CST_COFINS  WHERE ID = '$id'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $id,
                'descricao' => $dados['DESCRICAO'],
                'codigo' => $dados['CODIGO'],
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

if ($_POST["JQueryFunction"] == 'deletarCstCofins') {
    $response = array();

    $id = $_POST['id'];

    $query = "DELETE FROM FIS_CST_COFINS WHERE ID = '$id'";

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
