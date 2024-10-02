<?php
if (file_exists('../vendor/autoload.php')) {
  require '../vendor/autoload.php';
} else if (file_exists('../../vendor/autoload.php')) {
  require '../../vendor/autoload.php';
} else if (file_exists('../../../vendor/autoload.php')) {
  require '../../../vendor/autoload.php';
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$conexao = mysqli_connect("{$_ENV['DB_HOST']}", "{$_ENV['DB_USER']}", "{$_ENV['DB_PASSWORD']}", "{$_ENV['DB_DATABASE']}");