<?php
require_once '../lib/BaseUrl.php';
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
} else if (file_exists('../../vendor/autoload.php')) {
    require '../../vendor/autoload.php';
} else if (file_exists('../../../vendor/autoload.php')) {
    require '../../../vendor/autoload.php';
}

if (!(getenv('WEBSITE_SITE_NAME'))) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

if (isset($_ENV['JWT_NAME'])) {
    $token_session = 'session_' . $_ENV['JWT_NAME'];
} else {
    $token_session = 'session_app';
}
?>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $title ?? 'AllDrip' ?></title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/app.min.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/style.css">
    <link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/components.css">

    <!-- Custom style CSS -->
    <link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?= URL_BASE_HOST ?>/public/template/assets/img/favicon.ico' />

    <!-- Jquery -->
    <script src="<?= URL_BASE_HOST ?>/public/assets/js/axios.min.js"></script>
    <script src="<?= URL_BASE_HOST ?>/public/assets/js/ajax.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/bundles/izitoast/css/iziToast.min.css" />

</head>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form method="POST" id="loginForm">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" name="email" tabindex="1" required>
                        </div>
                        <div class="form-group">
                            <div class="d-block">
                                <label for="password" class="control-label">Senha</label>
                                <div class="float-right">
                                    <a href="javascript:void(0)" class="text-small">
                                        Recuperar senha?
                                    </a>
                                </div>
                            </div>
                            <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="rememberme">
                                <label class="custom-control-label" for="rememberme">Lembrar de mim</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button id="submitForm" type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                Entrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('admin/layouts/scripts.php') ?>

<script>
    const form = document.querySelector("#loginForm");

    // Captura o evento de submissão do formulário
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Previne o comportamento padrão de submissão do formulário
        realizarLogin(); // Chama a função para realizar o login
    });

    // Ouvinte de evento para o link "Entrar"
    document.getElementById('submitForm').addEventListener('click', function(event) {
        event.preventDefault(); // Previne a ação padrão do link
        realizarLogin(); // Chama a função para realizar o login
    });

    // Ouvinte de evento para os campos de entrada (para capturar "Enter")
    document.getElementById('email').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') { // Verifica se a tecla pressionada é "Enter"
            event.preventDefault(); // Previne o comportamento padrão do "Enter" no campo
            realizarLogin(); // Chama a função para realizar o login
        }
    });

    document.getElementById('password').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') { // Verifica se a tecla pressionada é "Enter"
            event.preventDefault(); // Previne o comportamento padrão do "Enter" no campo
            realizarLogin(); // Chama a função para realizar o login
        }
    });

    async function realizarLogin() {
        if ($('#email').val() == '') {
            exibirToastr('Preencha o campo "Email"!', 'danger', 'email');
            return;
        }

        if ($('#password').val() == '') {
            exibirToastr('Preencha o campo "Senha"!', 'danger', 'password');
            return;
        }

        try {
            const formData = new FormData(document.getElementById('loginForm'));
            const {
                data
            } = await axios.post('../lib/jwt_login.php', formData);

            localStorage.setItem('<?= $token_session ?>', data);
            window.location.href = '../view/admin/index.php';
        } catch (error) {
            exibirToastr('Credenciais inválidas!', 'danger');
        }
    }

    function RecuperarSenha() {
        event.preventDefault();
        var email = $('#email').val();

        if (!(validateEmail(email))) {
            exibirToastr('Preencha um email válido!', 'danger', 'email');
            return;
        }

        if (email !== '') {
            swal({
                title: 'Deseja redefinir sua senha?',
                text: 'Caso concorde, uma nova senha será enviada ao seu e-mail',
                icon: 'warning',
                buttons: {
                    cancel: {
                        text: 'Cancelar',
                        value: null,
                        visible: true,
                        className: '',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'OK!',
                        value: true,
                        visible: true,
                        className: '',
                        closeModal: true
                    }
                }
            }).then((willConfirm) => {
                if (willConfirm) {
                    $.post('../htpp/controller/LoginController.php', {
                        JQueryFunction: 'RedefinirSenhaUserLogin',
                        email: email
                    }, function(resposta) {
                        if (resposta === '') {
                            exibirToastr('Sem resposta do servidor!', 'danger');
                        } else {
                            var resp = JSON.parse(resposta);
                            if (resp.success) {
                                exibirToastr(resp.msg, 'success');
                            } else {
                                exibirToastr(resp.msg, 'danger');
                            }
                        }
                    });
                } else {
                    swal({
                        title: 'Cancelado',
                        text: 'Operação cancelada.',
                        icon: 'error',
                        button: {
                            text: 'OK',
                            closeModal: true
                        }
                    });
                }
            });
        }
    }
</script>