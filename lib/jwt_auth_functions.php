<?php
if (file_exists('../vendor/autoload.php')) {
    require_once '../vendor/autoload.php';
    require_once '../lib/BaseUrl.php';
} else if (file_exists('../../vendor/autoload.php')) {
    require_once '../../vendor/autoload.php';
    require_once '../../lib/BaseUrl.php';
} else if (file_exists('../../../vendor/autoload.php')) {
    require_once '../../../vendor/autoload.php';
    require_once '../../../lib/BaseUrl.php';
} else if (file_exists('../../../../vendor/autoload.php')) {
    require_once '../../../../vendor/autoload.php';
    require_once '../../../../lib/BaseUrl.php';
}


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!(getenv('WEBSITE_SITE_NAME'))) {

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
    $dotenv->load();
}

function validateJWTToken()
{

    if (isset($_ENV['JWT_NAME'])) {
        $jwt_token = 'session_' . $_ENV['JWT_NAME'];
    } else {
        $jwt_token = 'session_app';
    }


    if (!isset($_COOKIE[$jwt_token])) {
        header("HTTP/1.1 302 Internal Server Error");
        header("Location: " . URL_BASE_HOST . "/view/login.php");
        exit();
    }

    $token = $_COOKIE[$jwt_token];

    if (!$token) {
        header("HTTP/1.1 401 Unauthorized");
        header("Location: " . URL_BASE_HOST . "/view/login.php");
        exit();
    }

    try {
        $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
        // Additional checks if needed, e.g., user status, expiration date, etc.
        return $decoded->email;
    } catch (\Firebase\JWT\ExpiredException $e) {
        http_response_code(401);
        // setcookie("check_auth", '', 0, "/");
        // setcookie("jwt_token".$_ENV['JWT_NAME'], '', 0, "/");
        header("Location: " . URL_BASE_HOST . "/view/login.php");
        exit();
    } catch (\Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        header("Location: " . URL_BASE_HOST . "/view/login.php");
        exit();
    }
}
