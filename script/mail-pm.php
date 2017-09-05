<?php

$method = $_SERVER['REQUEST_METHOD'];

$message = '';

//Script Foreach
$c = true;
if ( $method === 'POST' ) {
	$project_name = trim($_POST["project_name"]);
	$admin_email  = trim($_POST["admin_email"]);
	$form_subject = trim($_POST["form_subject"]);

	foreach ( $_POST as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" ) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
} else if ( $method === 'GET' ) {
	$project_name = trim($_GET["project_name"]);
	$admin_email  = trim($_GET["admin_email"]);
	$form_subject = trim($_GET["form_subject"]);

	foreach ( $_GET as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" ) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
}

$message = "<table style='width: 100%;'>$message</table>";

function adopt($text) {
	return '=?UTF-8?B?'.base64_encode($text).'?=';
}

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (RFC)
$eol = PHP_EOL;

require '../vendor/autoload.php';

$mail = new PHPMailer();

$host = 'smtp.gmail.com';
$user = 'some.email@gmail.com';
// If email is different than username then replace line below:
$email = $user;
$password = 'some.password';

$mail->isSMTP();
$mail->Host = $host;
$mail->SMTPAuth = true;
$mail->Username = $user;
$mail->Password = $password;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom($email, $email);
$mail->addAddress($admin_email, $admin_email);


foreach ($_FILES as $name => $file_det) {
	$filename = $_FILES[$name]['name'];
	$file = $_FILES[$name]['tmp_name'];
	$mail->addAttachment($file, $filename);
}


$mail->isHTML(true);

$mail->Subject = 'Subject';
$mail->Body = $message;

if(!$mail->send()) {
	echo 'Message could not be sent.';
	echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
	echo 'Message has been sent';
}