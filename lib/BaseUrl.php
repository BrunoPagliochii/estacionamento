<?php

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']) || !empty($_SERVER['HTTP_X_ARR_SSL'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}

$protocol = $uri;
$host = $_SERVER['HTTP_HOST'];

$url_base_host = $protocol . $host;
define('URL_BASE_HOST', $url_base_host);
