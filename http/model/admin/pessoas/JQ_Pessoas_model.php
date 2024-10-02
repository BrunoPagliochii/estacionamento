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

if ($_POST["JQueryFunction"] == 'preencherTabelaDePessoas') {
    $response = array();

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
    WHERE A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dados = $resultado->fetch_assoc()) {
        $response[] = array(
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

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'buscarPessoa') {
    $response = array();
    $id = $_POST['id'];

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
    WHERE A.ID = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dados = $resultado->fetch_assoc()) {
        $response = array(
            'status' => 'success',
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

    echo json_encode($response);
}
