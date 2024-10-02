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

if ($_POST["JQueryFunction"] == 'cadastrarDav') {
  $array = array();

  $cliente = $_POST['cliente'];
  $formaPagamento = $_POST['formaPagamento'];
  $vencimento = $_POST['vencimento'];
  $valor = str_replace(",", ".", (str_replace(".", "", $_POST["valor"])));
  $observacao = (!isset($_POST["observacao"]) || $_POST["observacao"] == '') ? 'NULL' : "'" . $_POST["observacao"] . "'";

  $query = "INSERT INTO COM_DAVS (CLIENTE, FORMA_PAGAMENTO, VENCIMENTO, VALOR, OBSERVACAO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
  VALUES ('$cliente', '$formaPagamento', '$vencimento', '$valor', $observacao, '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

  if (mysqli_query($conexao, $query)) {
    $array = array(
      'status' =>  'success',
      'msg' =>  'Cadastro Realizado com Sucesso!.',
    );
  } else {

    $array = array(
      'msg' =>  mysqli_error($conexao),
    );
  }

  echo json_encode($array);
}

if ($_POST["JQueryFunction"] == 'editarDav') {
  $array = array();

  $cliente = $_POST['cliente'];
  $formaPagamento = $_POST['formaPagamento'];
  $vencimento = $_POST['vencimento'];
  $valor = str_replace(",", ".", (str_replace(".", "", $_POST["valor"])));
  $observacao     = (!isset($_POST["observacao"]) || $_POST["observacao"] == '') ? 'NULL' : "'" . $_POST["observacao"] . "'";

  $id = $_POST['id'];

  $query = "UPDATE COM_DAVS 
  SET CLIENTE = '$cliente', 
      FORMA_PAGAMENTO = '$formaPagamento', 
      VENCIMENTO = '$vencimento', 
      VALOR = '$valor', 
      OBSERVACAO = $observacao, 
      ALTERADOPOR = '$IDUSUARIOMODEL', 
      ALTERADOEM = NOW() 
  WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

  if (mysqli_query($conexao, $query)) {
    $array = array(
      'status' =>  'success',
      'msg' =>  'Cadastro Alterado com Sucesso!.',
    );
  } else {

    $array = array(
      'msg' =>  mysqli_error($conexao),
    );
  }

  echo json_encode($array);
}


if ($_POST["JQueryFunction"] == 'deletarDav') {
  $array = array();

  $id = $_POST['id'];

  $query = "UPDATE COM_DAVS SET
  ATIVO = 'N',
  ALTERADOPOR = '$IDUSUARIOMODEL',
  ALTERADOEM = NOW()
  WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


  if (mysqli_query($conexao, $query)) {
    $array = array(
      'status' =>  'success',
      'msg' =>  'Cadastro foi Alterado com Sucesso!.',
    );
  } else {

    $array = array(
      'status' =>  'error',
      'msg' =>  mysqli_error($conexao),
    );
  }

  echo json_encode($array);
}
