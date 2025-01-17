<?php

$method = $_SERVER['REQUEST_METHOD'];

//Script Foreach
$c = true;

$project_name = 'sudo.team';
$admin_email  = 'info@sudo.team';
$form_subject = 'Сообщение с сайта sudo.team';
$message = '';

$formTargetToSubject = [
    'Навыки соискателя' => 'резюме',
    'Сотрудничество/услуга' => 'сотрудничество/услуга',
    'Вопросы' => 'вопросы',
];

$params = $method === 'POST' ? $_POST : ($method ===  'GET' ? $_GET : []);
$notAllowedKeys = ["project_name", "admin_email", "form_subject"];

foreach ($params as $key => $value) {
    if(!isset($value) || $value === '' || in_array($key, $notAllowedKeys)) continue;
    $message .= "
    " . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
	<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
	<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
	</tr>";
    if (array_key_exists($key, $formTargetToSubject))
        $form_subject = $form_subject . ' (' . $formTargetToSubject[$key] . ').';
}


$message = "<table style='width: 100%;'>$message</table>";

function adopt($text) {
	return '=?UTF-8?B?'.Base64_encode($text).'?=';
}



$headers = "MIME-Version: 1.0" . PHP_EOL .
"Content-Type: text/html; charset=utf-8" . PHP_EOL;

try {
    $result = mail($admin_email, adopt($form_subject), $message, $headers );
} catch (Exception $e) {
    header("HTTP/1.1 500 Server Error");;
    exit();
}
if (!$result) header("HTTP/1.1 500 Server Error");
else header("HTTP/1.1 200 OK");
exit();
