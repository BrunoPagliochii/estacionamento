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
$query = "SELECT A.NOME, A.EMAIL, A.ID FROM BD_USUARIOS A WHERE A.ATIVO = 'S' AND A.EMAIL = ?";

$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $emailUsuario_token);
$stmt->execute();
$result = $stmt->get_result();

if ($dadossiderbar = $result->fetch_assoc()) {
    $IDUSUARIOMODEL = $dadossiderbar['ID'];
    $NOMEUSUARIOMODEL = $dadossiderbar['NOME'];
    $EMAILUSUARIOMODEL = $dadossiderbar['EMAIL'];
}
// --------------------------------------------------------------------
if ($_POST["JQueryFunction"] == 'novoUsuario') {
    $response = array();

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    $query = "INSERT INTO BD_USUARIOS (NOME, EMAIL, SENHA, TIPO, INCLUIDOPOR, INCLUIDOEM)
    VALUES ('$nome', '$email', '$senha', 3, '$IDUSUARIOMODEL', NOW())";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'nome' => $nome,
            'email' => $email,
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


if ($_POST["JQueryFunction"] == 'editarUsuario') {
    $response = array();

    $idUsuario = $_POST['idUsuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    if(isset($_POST['senha']) && $_POST['senha'] != null && $_POST['senha'] != ''){
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        // Query de update
        $query = "UPDATE BD_USUARIOS SET
            NOME = '$nome',
            EMAIL = '$email', 
            SENHA = '$senha', 
            ALTERADOPOR = '$IDUSUARIOMODEL',
            ALTERADOEM = NOW()
        WHERE idUsuario = '$idCor'";
    } else {
        $query = "UPDATE BD_USUARIOS SET
        NOME = '$nome',
        EMAIL = '$email',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE idUsuario = '$idCor'";
    }

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT NOME, EMAIL, ATIVO FROM BD_USUARIOS WHERE ID = '$idUsuario'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $idUsuario,
                'nome' => $dados['NOME'],
                'email' => $dados['EMAIL'],
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


if ($_POST["JQueryFunction"] == 'inativarUsuario') {
    $response = array();

    $ID = $_POST['ID'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE BD_USUARIOS SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$ID'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT NOME, EMAIL, ATIVO FROM BD_USUARIOS WHERE ID = '$ID'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $ID,
                'nome' => $dados['NOME'],
                'email' => $dados['EMAIL'],
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
