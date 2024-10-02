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

if ($_POST["JQueryFunction"] == 'coletarDadosDav') {
  $array = array();

  $id = $_POST['id'];
  $empresa = $_POST['empresa'];

  $resultado = $conexao->query("SELECT
      A.ID,
      A.VALOR,
      A.VENCIMENTO,
      A.OBSERVACAO,
      B.EMAIL,
      B.NOME AS CLIENTE,
      C.DESCRICAO AS FORMA_PAGAMENTO,
      D.NOME AS MUNICIPIO,
      E.UF,
      B.BAIRRO,
      B.LOGRADOURO,
      B.CEP
  FROM COM_DAVS A
      INNER JOIN BD_PESSOAS B ON B.ID = A.CLIENTE
      INNER JOIN FIN_FORMAS_DE_PAGAMENTO C ON C.ID = A.FORMA_PAGAMENTO
      INNER JOIN BD_MUNICIPIOS D ON D.ID = B.MUNICIPIO
      INNER JOIN BD_ESTADOS E ON E.ID = D.UF
  WHERE A.EMPRESA_ID = '$empresa' AND A.ID = '$id' AND A.ATIVO = 'S'");

  if (!$resultado) {
    die(json_encode(array("error" => "Erro na consulta: " . $conexao->error)));
  }

  $dadosArray = array();
  while ($dadosDavs = $resultado->fetch_assoc()) {
    $dadosArray[] = $dadosDavs;
  }

  echo json_encode($dadosArray);
}
