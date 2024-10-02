<?php
require '../vendor/autoload.php';
if (!(getenv('WEBSITE_SITE_NAME'))) {

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
    $dotenv->load();
    
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function enviarEmail($destinatarios, $assunto, $corpo, $bcc_emails = null) {

    // Inicia a Resposta
    $response = array();

    // Inicia a instancia da biblioteca PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = "{$_ENV['Email_Host']}";
        $mail->SMTPAuth   = "{$_ENV['Email_SMTPAuth']}";
        $mail->Username   = "{$_ENV['Email_Username']}";
        $mail->Password   = "{$_ENV['Email_Password']}";
        $mail->SMTPSecure = "{$_ENV['Email_SMTPSecure']}";
        $mail->Port       = "{$_ENV['Email_Port']}";
        $mail->CharSet    = "{$_ENV['Email_CharSet']}";

        // Remetente
        $mail->setFrom("{$_ENV['Email_Username']}", 'Droyds');

        // Destinatário
        foreach ($destinatarios as $destinatario) {
            $mail->addAddress($destinatario);
        }

        // Adiciona os destinatários BCC
        if ($bcc_emails !== null) {
            foreach ($bcc_emails as $bcc_email) {
                $mail->addBCC($bcc_email);
            }
        }

        // Conteúdo HTML
        $mail->isHTML(true);
        $mail->Subject = "$assunto";

        // Assinatura
        $assinatura = '<img style="width: 500px;" src="https://www.droyds.com.br/uploads/ass/ass-sys.png">';
        
        // Adicione o corpo do e-mail
        $mail->Body = "$corpo<br><br> $assinatura";

        // Verifica se o email foi enviado com sucesso
        if ($mail->send()) {
            $response['status'] = 'success';
            $response['message'] = 'Mensagem enviada!';
            $response['http_code'] = 200;

        } else {
            throw new Exception("Erro no envio do e-mail: {$mail->ErrorInfo}");
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        $response['http_code'] = 500;
    }

    return $response;
}
