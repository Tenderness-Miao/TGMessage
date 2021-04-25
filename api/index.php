<?php
require_once 'Bot.php';

header('content-type: application/json');
$arr = json_decode(file_get_contents('php://input'), true);
$alarmId = "告警编号" . " " . $arr['alarmId'];
$creationTime = "告警时间" . " " . $arr['creationTime'];
$priority = "告警级别" . " " . $arr['priority'];
$alarmName = "告警标题" . " " . $arr['alarmName'];
$alarmContent = "告警内容" . " " . $arr['alarmContent'];
$entityName = "告警对象" . " " . $arr['entityName'];
$app = "告警应用" . " " . $arr['app'];
$count = "告警次数" . " " . $arr['count'];
$status = "告警状态" . " " . $arr['status'];

$data = $alarmId . "\n" . $creationTime . "\n" . $priority . "\n" . $alarmName . "\n" . $alarmContent . "\n" . $entityName . "\n" . $app . "\n" . $count . "\n" . $status;
$token = $_REQUEST['token'] ?? null;
$message = $_REQUEST['message'] ?? $data;


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