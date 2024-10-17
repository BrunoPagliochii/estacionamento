<?php
if (file_exists('../vendor/autoload.php')) {
	require_once '../lib/BaseUrl.php';
	require_once '../vendor/autoload.php';
	require_once '../lib/PHP_conecta.php';
	include_once '../http/model/admin/Helper_model.php';
} else if (file_exists('../../lib/BaseUrl.php')) {
	require_once '../../lib/BaseUrl.php';
	require_once '../../vendor/autoload.php';
	require_once '../../lib/PHP_conecta.php';
	include_once '../../http/model/admin/Helper_model.php';
} else if (file_exists('../../../lib/BaseUrl.php')) {
	require_once '../../../lib/BaseUrl.php';
	require_once '../../../vendor/autoload.php';
	require_once '../../../lib/PHP_conecta.php';
	include_once '../../../http/model/admin/Helper_model.php';
}

if (!(getenv('WEBSITE_SITE_NAME'))) {
	$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 4));
	$dotenv->load();
}

if (isset($_ENV['JWT_NAME'])) {
	$token_session = 'session_' . $_ENV['JWT_NAME'];
} else {
	$token_session = 'session_app';
}
?>

<!DOCTYPE html>
<html lang="pt-Br">

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
	<title><?= $dados['NomePagina'] ?? 'Estacionamento' ?></title>

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

<script>
	// Função para obter dados do usuário
	async function getTokenValido() {
		try {
			// Verificar se o token está presente
			const authSession = localStorage.getItem('<?= $token_session ?>');

			if (!authSession) {
				// Se o token não estiver presente, redirecionar para a página de login do usuário sem autenticação
				window.location.href = '<?= URL_BASE_HOST ?>/view/login.php';
				document.cookie.split(";").forEach(function(c) {
					document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
				});
				return;
			}

			// Verificar se o token é válido
			const response = await fetch('<?= URL_BASE_HOST ?>/lib/jwt_auth.php', {
				method: 'GET',
				headers: {
					'Authorization': 'Bearer ' + authSession
				}
			});

			if (!response.ok) {
				// Se o token não for válido, redirecionar para a página de login do usuário sem autenticação
				window.location.href = '<?= URL_BASE_HOST ?>/view/login.php';
				document.cookie.split(";").forEach(function(c) {
					document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
				});
				return;
			}

			// Tentar analisar a resposta como JSON
			const jsonData = await response.json();

			// Verificar se a resposta contém um token expirado
			if (jsonData && jsonData.message === 'Token expirado') {
				window.location.href = '<?= URL_BASE_HOST ?>/view/login.php'; // Redirecionar se o token estiver expirado
				document.cookie.split(";").forEach(function(c) {
					document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
				});
				return;
			}

		} catch (error) {
			// Se ocorrer um erro, redirecionar para a página de login
			console.error(error);
			window.location.href = '<?= URL_BASE_HOST ?>/view/login.php';
		}
	}

	function logout() {
		localStorage.removeItem('<?= $token_session ?>');
		document.cookie.split(";").forEach(function(c) {
			document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
		});
		window.location.replace("<?= URL_BASE_HOST ?>/view/login.php");
	}

	// Chamar a função ao carregar a página
	getTokenValido();
</script>