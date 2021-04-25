<?php
require_once 'Bot.php';

header('content-type: application/json');
$json_string = $_POST["txt_json"];

if (ini_get("magic_quotes_gpc") == "1") {
    $json_string = stripslashes($json_string);
}

$data = json_decode($json_string);

$token = $_REQUEST['token'] ?? null;
$message = $_REQUEST['message'] ?? $data->alarmContent;

$bot = new Bot();

if (is_null($token)) {
    echo json_encode(['code' => 422, 'message' => 'token 不能为空']);
} else {
    // 发送消息
    $chat_id = $bot->decryption($token);
    $ret = $bot->sendMessage(['text' => $message, 'chat_id' => $chat_id]);
    if ($ret['ok']) {
        echo json_encode(['code' => 200, 'message' => 'success']);
    } else {
        echo json_encode(['code' => 422, 'message' => $ret['description']]);
    }
}