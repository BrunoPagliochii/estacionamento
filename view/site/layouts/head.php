<?php
if (file_exists('../vendor/autoload.php')) {
	require_once '../lib/BaseUrl.php';
	require_once '../vendor/autoload.php';
	require_once '../lib/PHP_conecta.php';
} else if (file_exists('../../lib/BaseUrl.php')) {
	require_once '../../lib/BaseUrl.php';
	require_once '../../vendor/autoload.php';
	require_once '../../lib/PHP_conecta.php';
} else if (file_exists('../../../lib/BaseUrl.php')) {
	require_once '../../../lib/BaseUrl.php';
	require_once '../../../vendor/autoload.php';
	require_once '../../../lib/PHP_conecta.php';
}
?>

<!DOCTYPE html>
<html lang="pt-Br">

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
	<title><?= $dados['NomePagina'] ?? 'AllDrip' ?></title>

	<?php
	if (file_exists('layouts/styles.php')) {
		include('layouts/styles.php');
	} else if (file_exists('../layouts/styles.php')) {
		include('../layouts/styles.php');
	} else if (file_exists('../../layouts/styles.php')) {
		include('../../layouts/styles.php');
	} else if (file_exists('../../../layouts/styles.php')) {
		include('../../../layouts/styles.php');
	}
	?>
</head>