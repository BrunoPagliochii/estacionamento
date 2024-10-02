<?php
if (file_exists('../vendor/autoload.php')) {
  require '../vendor/autoload.php';
} else {
  require '../../vendor/autoload.php';
}
require 'PHP_conecta.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if (!(getenv('WEBSITE_SITE_NAME'))) {
  $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
  $dotenv->load();
}

if (isset($_ENV['JWT_NAME'])) {
  $jwt_token = 'session_' . $_ENV['JWT_NAME'];
} else {
  $jwt_token = 'session_app';
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$query = "SELECT A.ID, A.NOME, A.EMAIL, A.SENHA AS PASSWORD 
          FROM BD_USUARIOS A
          INNER JOIN BD_EMPRESAS B ON B.ID = A.EMPRESA_ID
          WHERE A.ATIVO = 'S'
          AND B.ATIVO = 'S'
          AND A.EMAIL = ?";

$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$userFound = $result->fetch_assoc();

if (!$userFound || !password_verify($password, $userFound["PASSWORD"])) {
  http_response_code(401);
  setcookie("$jwt_token", '', 0, "/");
  echo json_encode(["message" => "Credenciais inválidas"]);
  exit;
}

if (isset($_ENV['JWT_TEMPO_AUTH'])) {
  $tempo_auth = $_ENV['JWT_TEMPO_AUTH'];
} else {
  $tempo_auth = ((60 * 60) * 4);
}

if (isset($_POST['rememberme']) && $_POST['rememberme'] === 'on') {
  $expiry = (time() + (86400 * 30));
} else {
  $expiry = (time() + $tempo_auth);
}

$payload = [
  "iat" => time(),
  "exp" => $expiry,
  "email" => $email
];

$encode = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');
$token = json_encode($encode);
$tokencookie = urlencode($encode);

$domain = $_ENV['DOMINIO_APP'];
$secure = isset($_ENV['SECURE_COOKIE']) && $_ENV['SECURE_COOKIE'] === 'true';

if (str_contains($jwt_token, 'local')) {
  $cookieSet = setcookie($jwt_token, $tokencookie, $expiry, "/", $domain, false, true);
} else {
  $cookieSet = setcookie($jwt_token, $tokencookie, $expiry, "/", $domain, $secure, true);
}

if (!$cookieSet) {
  http_response_code(500);
  echo json_encode(["message" => "Não foi possível definir o cookie. Verifique as configurações de domínio e caminho."]);
  exit;
}

echo $token;
?>
