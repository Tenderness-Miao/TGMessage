<?php
require_once 'Bot.php';

header('content-type: application/json');
$obj = json_decode(file_get_contents('php://input'), true);

$token = $_REQUEST['token'] ?? null;
$message = $_REQUEST['message'] ?? $obj;

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