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

if ($_POST["JQueryFunction"] == 'cadastrarPessoa') {
    $response = array();

    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    $documento = $_POST['documento'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];

    $logradouro = $_POST['logradouro'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $municipio = $_POST['municipio'];

    $query = "INSERT INTO BD_PESSOAS (NOME, DOCUMENTO, TIPO, EMAIL, CELULAR, LOGRADOURO, BAIRRO, CEP, MUNICIPIO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
  VALUES ('$nome', '$documento',  '$tipo', '$email', '$celular', '$logradouro', '$bairro', '$cep', '$municipio','$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!.',
        );
    } else {

        $response = array(
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarPessoa') {
    $response = array();

    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    $documento = $_POST['documento'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];

    $logradouro = $_POST['logradouro'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $municipio = $_POST['municipio'];

    $query = "UPDATE BD_PESSOAS SET 
        NOME = '$nome', 
        TIPO = '$tipo', 
        EMAIL = '$email', 
        CELULAR = '$celular', 
        LOGRADOURO = '$logradouro', 
        BAIRRO = '$bairro', 
        CEP = '$cep', 
        MUNICIPIO = '$municipio', 
        DOCUMENTO = '$documento', 
        ALTERADOPOR = '$IDUSUARIOMODEL', 
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Alterado com Sucesso!.',
        );
    } else {

        $response = array(
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}


if ($_POST["JQueryFunction"] == 'inativarPessoa') {
    $response = array();

    $ID = $_POST['id'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE BD_PESSOAS SET 
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $resultado = $conexao->query("SELECT 
            A.ID,
            A.TIPO,
            A.NOME,
            A.DOCUMENTO,
            A.EMAIL,
            A.CELULAR,
            A.LOGRADOURO,
            A.BAIRRO,
            A.CEP,
            A.MUNICIPIO AS MUNICIPIO_ID,
            B.NOME AS MUNICIPIO,
            C.UF,
            A.ATIVO
        FROM BD_PESSOAS A
            INNER JOIN BD_MUNICIPIOS B ON B.ID = A.MUNICIPIO
            INNER JOIN BD_ESTADOS C ON C.ID = B.UF
        WHERE A.ID = '$ID' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
        if (!$resultado) {
            die("Erro na consulta: " . $conexao->error);
        }

        while ($dados = $resultado->fetch_assoc()) {
            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $dados['ID'],
                'tipo' => $dados['TIPO'],
                'nome' => $dados['NOME'],
                'documento' => $dados['DOCUMENTO'],
                'email' => $dados['EMAIL'],
                'celular' => $dados['CELULAR'],
                'logradouro' => $dados['LOGRADOURO'],
                'bairro' => $dados['BAIRRO'],
                'cep' => $dados['CEP'],
                'municipio_id' => $dados['MUNICIPIO_ID'],
                'municipio' => $dados['MUNICIPIO'],
                'uf' => $dados['UF'],
                'ativo' => $dados['ATIVO'],
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
