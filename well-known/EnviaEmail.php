<?php
// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require("phpmailer/PHPMailerAutoload.php");

//Configura dados do e-mail - Alterar somente essas configurações

// Credenciais SMTP vêm de variáveis de ambiente ou config.local.php (gitignored).
// Configure SMTP_HOST, SMTP_USER, SMTP_PASS, SMTP_FROM, SMTP_TO antes de usar.
$servidor       = getenv('SMTP_HOST') ?: ($SMTP_HOST       ?? null);
$usuario        = getenv('SMTP_USER') ?: ($SMTP_USER       ?? null);
$senha          = getenv('SMTP_PASS') ?: ($SMTP_PASS       ?? null);
$de             = getenv('SMTP_FROM') ?: ($SMTP_FROM       ?? null);
$para           = getenv('SMTP_TO')   ?: ($SMTP_TO         ?? null);
$descricao_para = 'Contato - Newco Brazil';
$assunto        = 'Contato através do site - Newco Brazil';

if (!$servidor || !$usuario || !$senha || !$de || !$para) {
    $local = __DIR__ . '/config.local.php';
    if (is_file($local)) {
        require $local;
        $servidor = $servidor ?: ($SMTP_HOST ?? null);
        $usuario  = $usuario  ?: ($SMTP_USER ?? null);
        $senha    = $senha    ?: ($SMTP_PASS ?? null);
        $de       = $de       ?: ($SMTP_FROM ?? null);
        $para     = $para     ?: ($SMTP_TO   ?? null);
    }
}

if (!$servidor || !$usuario || !$senha || !$de || !$para) {
    die('Configuração SMTP ausente. Defina SMTP_HOST/SMTP_USER/SMTP_PASS/SMTP_FROM/SMTP_TO via env ou config.local.php.');
}
$url_volta = getenv('SITE_URL') ?: ($SITE_URL ?? 'http://www.newcobrazil.com/#contact');
$telefones = getenv('CONTACT_PHONES') ?: ($CONTACT_PHONES ?? '+55 (35) 9989-6978 ou +55 (35) 9126-9835');


//=================================================
$header = "Content-type: text/html; charset=utf-8";
//Busca dados no formulário
$name = $_POST['name']; 
$email_address = $_POST['email'];
$message = $_POST['message']; 

// Inicia a classe PHPMailer
$mail = new PHPMailer;
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = $servidor; // Endereço do servidor SMTP
$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
$mail->Username = $usuario; // Usuário do servidor SMTP
$mail->Password = $senha; // Senha do servidor SMTP
$mail->Port = 456;
// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->From = $de; // Seu e-mail
$mail->Sender = $de;
$mail->AddReplyTo($email_address);
$mail->FromName = $name; // Seu nome
// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAddress( $para, $descricao_para);
//$mail->AddAddress('jorge@p2dgital.com.br');
//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
// Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = $assunto; // Assunto da mensagem
$mail->Body = "Mensagem enviada através do formulário do site <strong>www.newcobrazil.com</strong> <br /><br /><strong>Nome:</strong> $name <br /><strong>Email:</strong> $email_address <br /><strong><br /><strong>Mensagem:</strong><br /> $message";
$mail->AltBody = ""; 
// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
// Envia o e-mail
$enviado = $mail->Send();
// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();
// Exibe uma mensagem de resultado
if ($enviado) {

	echo <<<HTML_Ok
	<html lang="pt-br">
	<head>
		<title>Obrigado!</title>

		<meta charset="utf-8">

		<link href="assets/css/email.css" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" /> <!-- font-awesome -->
		<!-- a helper script for vaidating the form-->
	</head>	

	<body>
		<div class="outer">
			<div class="middle">
				<div class="inner">
					<span class="icone glyphicon glyphicon-thumbs-up"></span>
					<h1>Obrigado pelo contato!</h1>
					Recebemos a sua mensagem. Entraremos em contato o mais breve possível!
					<br>
					<br>
					<br>
					<br>
					<a href="{$url_volta}/"><font-size = "10px"><i class="fa fa-undo" aria-hidden="true"></i> Voltar ao site</font>
					</div>
				</div>
			</div>	
		</body>
		</html>
HTML_Ok;

	} else {
  //var_dump($mail);

		echo <<<HTML_Erro

		<html lang="pt-br">
		<head>
			<title>Desculpe-nos!</title>

			<meta charset="utf-8">

			<link href="assets/css/email.css" rel="stylesheet">
			<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" /> <!-- font-awesome -->
			<!-- a helper script for vaidating the form-->
		</head>	

		<body>
			<div class="outer">
				<div class="middle">
					<div class="inner">
						<span class="icone glyphicon glyphicon-thumbs-down"></span>
						<h1>Desculpe-nos o transtorno</h1>
						Tivemos um problema ao enviar sua mensagem. Tente novamente e se o problema persistir por favor entre em contato conosco através dos telefones {$telefones} .
						<br>
						<br>

						<p>
							Mensagem de Erro: {$mail->ErrorInfo} 
						</p>

						<br>
						<br>
						<a href="{$url_volta}/"><font-size = "10px"><i class="fa fa-undo" aria-hidden="true"></i> Voltar ao site</font>
						</div>
					</div>
				</div>
			</body>
			</html>
HTML_Erro;
}