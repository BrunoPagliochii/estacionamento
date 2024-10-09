<?php
if (file_exists('../lib/PHP_conecta.php')) {
  require_once '../lib/jwt_auth_functions.php';
  require_once '../lib/PHP_conecta.php';
  require_once '../functions/helpers.php';
} else if (file_exists('../../lib/PHP_conecta.php')) {
  require_once '../../lib/jwt_auth_functions.php';
  require_once '../../lib/PHP_conecta.php';
  require_once '../../functions/helpers.php';
}if (file_exists('../../../lib/PHP_conecta.php')) {
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

// Define o fuso horário, se necessário
date_default_timezone_set('America/Sao_Paulo');

// Obtendo o primeiro dia do mês corrente
$primeiroDia = new DateTime('first day of this month');
$primeiroDiaDoMes = $primeiroDia->format('Y-m-d');

// Obtendo o último dia do mês corrente
$ultimoDia = new DateTime('last day of this month');
$ultimoDiaDoMes = $ultimoDia->format('Y-m-d');

// Formatação manual da data atual em português
setlocale(LC_TIME, 'pt_BR.UTF-8');
$data_atual = strftime('%A, %d de %B de %Y', time());
?>
