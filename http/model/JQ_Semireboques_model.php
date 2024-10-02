<?php
if (file_exists('../lib/PHP_conecta.php')) {
  require_once '../lib/jwt_auth_functions.php';
  require_once '../lib/PHP_conecta.php';
  require_once '../functions/helpers.php';
} else {
  require_once '../../lib/jwt_auth_functions.php';
  require_once '../../lib/PHP_conecta.php';
  require_once '../../functions/helpers.php';
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

if ($_POST["JQueryFunction"] == '') {
    $array = array();

    $query = "INSERT INTO BD_FORMAS_PAGAMENTO(DESCRICAO, EMPRESA, INCLUIDOPOR, INCLUIDOEM) 
    VALUES ('$descricao','$IDEMPRESAUSER','$IDUSUARIO', now2())";


    if (mysqli_query($conexao, $query)) {
        $array = array(
            'success' =>  1,
            'msg' =>  'Cadastro Realizado com Sucesso!.',
        );
    } else {

        $array = array(
            'success' =>  1,
            'msg' =>  mysqli_error($conexao),
        );
    }

    // Converte o array para JSON e envia como resposta
    echo json_encode($array);
}
