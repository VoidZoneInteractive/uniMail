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

$filename = $_FILES['file-0']['name'];
$file = $_FILES['file-0']['tmp_name'];

$content = file_get_contents($file);
$content = chunk_split(base64_encode($content));

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (RFC)
$eol = PHP_EOL;

// main header (multipart mandatory)
$headers = 'From: '.adopt($project_name).' <'.$admin_email.'>' . $eol;
$headers .= "MIME-Version: 1.0" . $eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
$headers .= 'Reply-To: '.$admin_email.'' . $eol;
$headers .= "This is a MIME encoded message." . $eol;

// message
$body = "--" . $separator . $eol;
$body .= "Content-Type: text/plain; charset=\"utf-8\"" . $eol;
$body .= "Content-Transfer-Encoding: 8bit" . $eol;
$body .= $message . $eol;

// attachment
$body .= "--" . $separator . $eol;
$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
$body .= "Content-Transfer-Encoding: base64" . $eol;
$body .= "Content-Disposition: attachment" . $eol;
$body .= $content . $eol;
$body .= "--" . $separator . "--";

//SEND Mail
if (mail($admin_email, $form_subject, $body, $headers)) {
	echo "mail send ... OK"; // or use booleans here
} else {
	echo "mail send ... ERROR!";
	print_r(error_get_last());
}