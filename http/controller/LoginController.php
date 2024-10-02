<?php
if (file_exists('../lib/PHP_conecta.php')) {
  require_once '../lib/jwt_auth_functions.php';
  require_once '../lib/PHP_conecta.php';
  require_once '../lib/PHP_email.php';
  require_once '../functions/helpers.php';
} else {
  require_once '../../lib/jwt_auth_functions.php';
  require_once '../../lib/PHP_conecta.php';
  require_once '../../functions/helpers.php';
  require_once '../../lib/PHP_email.php';
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//



if ($_POST["JQueryFunction"] == 'RedefinirSenhaUserLogin') {

  $email   = $_POST["email"];

  $consulta = sqlsrv_query($conexao, "SELECT TOP 1 NOME AS NOME, HANDLE, EMAIL 
  FROM BD_USUARIOS 
  WHERE EMAIL = '$email'");

  if (sqlsrv_has_rows($consulta)) {

    while ($dados = sqlsrv_fetch_array($consulta)) {
      $NomePessoa = $dados['NOME'];
      $HandlePessoa = $dados['HANDLE'];
      $EmailPessoa = $dados['EMAIL'];
      $UsuarioPessoa = $dados['EMAIL'];
    }

    $GeraSenha = geraSenha(11, true, true, true);
    $NovaSenha = password_hash($GeraSenha, PASSWORD_BCRYPT);


    $query = "UPDATE BD_USUARIOS
    SET SENHA = '$NovaSenha',
    ALTERADOEM = dbo.getdate2(),
    ALTERADOPOR = '1'
    WHERE HANDLE = '$HandlePessoa'";

    if (sqlsrv_query($conexao, $query) === false) {
      die(print_r(sqlsrv_errors(), true));
    }

    $destinatarios = [$EmailPessoa];
    $bcc_emails = ['notificacoes@droyds.com.br'];

    enviarEmail($destinatarios, "Recuperação de senha - Droyds Tecnologia", "
    Olá $NomePessoa,<br><br>
    Informamos que sua senha foi alterada! 🔒 Seguem suas novas credenciais:<br>
    <br>
    Link de acesso: <a href='https://droydsforbusiness.azurewebsites.net/'>https://droydsforbusiness.azurewebsites.net/</a><br>
    Senha: $GeraSenha<br>
    <br>
    🚨Atenção:<br>
    - Por questões de segurança, sua senha deve ser alterada a cada 3 meses.<br>
    - Sua senha deverá conter Letras maiúsculas, minúsculas, números, caracteres especiais e ter no mínimo 10 caracteres no total.<br>
    - Sua senha é pessoal e intransferível! Não deverá ser compartilhada com outros usuários.<br>
    - Recomendamos que sua senha seja alterada no primeiro login, clicando sobre o seu nome no menu lateral esquerdo, navegando até Parâmetros do Usuário e acessando o campo 'Alteração de Senha'.<br>
    <br>
    📢 Caso nosso sistema detecte a não-conformidade com nossa política de segurança, sua senha será alterada automaticamente e reencaminhada em seu e-mail.<br>
    ", $bcc_emails);

    $array = array(
      'success' =>  1,
      'msg' =>  'Credenciais redefinidas! Em alguns minutos as novas credenciais serão enviadas no seu e-mail.'
    );
  } else {
    $array = array(
      'success' =>  0,
      'msg' =>  'Nenhum resultado encontrado.'
    );
  }
  echo json_encode($array);
}

if ($_POST["JQueryFunction"] == 'cadastrarUsuario') {
  // Recebe os dados do POST
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
  $nome = $_POST['nome'];

  // Verifica se o email já está cadastrado
  $query_verifica_email = "SELECT EMAIL FROM BD_USUARIOS WHERE EMAIL = ?";
  $params_verifica_email = array(&$email);
  $stmt_verifica_email = sqlsrv_prepare($conexao, $query_verifica_email, $params_verifica_email);

  if (sqlsrv_execute($stmt_verifica_email) === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Verifica se já existe algum resultado
  if (sqlsrv_has_rows($stmt_verifica_email)) {
    // Email já cadastrado, retorna mensagem de erro
    $array = array(
      'success' => 0,
      'msg' => 'Esse e-mail já está em uso, utilize outro e-mail.',
    );
    echo json_encode($array);
    exit;
  }

  // Monta a query SQL para inserção
  $queryInsert = "INSERT INTO BD_EMPRESAS (RAZAOSOCIAL) 
      OUTPUT INSERTED.HANDLE
      VALUES (?)";

  $params_insert = array(&$nome);

  // Prepara a query de inserção
  $stmtInsert = sqlsrv_prepare($conexao, $queryInsert, $params_insert);

  // Executa a query de inserção
  if (sqlsrv_execute($stmtInsert) === false) {
    // Trate o erro de maneira adequada (lançando exceção ou registrando)
    die(print_r(sqlsrv_errors(), true));
  }

  // Obtém o HANDLE da empresa inserida
  $insertedHandle = sqlsrv_fetch_array($stmtInsert, SQLSRV_FETCH_ASSOC)['HANDLE'];

  // Se o email não está cadastrado, realiza a inserção
  $query_insert = "INSERT INTO BD_USUARIOS (NOME, EMAIL, EMPRESA, SENHA, INCLUIDOEM)
                    VALUES (?, ?, ?, ?, DBO.GETDATE2())";
  $params_insert = array(&$nome, &$email, &$insertedHandle, &$senha);
  $stmt_insert = sqlsrv_prepare($conexao, $query_insert, $params_insert);

  if (sqlsrv_execute($stmt_insert) === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Cadastro realizado com sucesso
  $array = array(
    'success' => 1,
    'msg' => 'Usuário cadastrado com sucesso!',
  );

  echo json_encode($array);
}
