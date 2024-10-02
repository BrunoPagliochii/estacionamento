<?php

if (file_exists('../vendor/autoload.php')) {
  require '../vendor/autoload.php';
} else {
  require '../../vendor/autoload.php';
}

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

if (!(getenv('WEBSITE_SITE_NAME'))) {
  $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
  $dotenv->load();
}

$headers = getallheaders();
$authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;

if ($authorization === null) {
  http_response_code(401);
  echo json_encode("Authorization header missing");
  exit;
}

$token = str_replace('Bearer ', '', $authorization);

if (empty($token)) {
  http_response_code(401);
  echo json_encode("Token missing");
  exit;
}

$tempo_auth = $_ENV['JWT_TEMPO_AUTH'] ?? ((60 * 60) * 4);
$jwt_token = 'session_' . ($_ENV['JWT_NAME'] ?? 'app');

try {
  $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
  echo json_encode($decoded);

  // $cookie_options = ['expires' => time() + $tempo_auth, 'path' => "/", 'domain' => $_ENV['DOMINIO_APP'], 'secure' => !str_contains($jwt_token, 'cube.local.com'), 'httponly' => true];
  // setcookie($jwt_token, $token, $cookie_options);
} catch (ExpiredException $e) {
  http_response_code(401);
  setcookie($jwt_token, "", 0, "/", $_ENV['DOMINIO_APP'], false, true);
  echo json_encode("Token expirado");
} catch (Throwable $e) {
  http_response_code(401);
  setcookie($jwt_token, "", 0, "/", $_ENV['DOMINIO_APP'], false, true);
  echo json_encode("Token inválido");
}