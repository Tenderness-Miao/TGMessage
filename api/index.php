<?php
require_once 'Bot.php';

header('content-type: application/json');
$arr = json_decode(file_get_contents('php://input'), true);
$alarmId = "告警编号:" . "\t" . $arr['alarmId'];
$creationTime = "告警时间:" . "\t" . date('Y-m-d H:i:s', $arr['creationTime'] / 1000);
if ($arr['priority'] == "1") {
    $level = "提醒";
} elseif ($arr['priority'] == "2") {
    $level = "警告";
} elseif ($arr['priority'] == "3") {
    $level = "严重";
} else {
    $level = "未知";
}
$priority = "告警级别:" . "\t" . $level;
$alarmName = "告警标题:" . "\t" . $arr['alarmName'];
$alarmContent = "告警内容:" . "\t" . $arr['alarmContent'];
$entityName = "告警对象:" . "\t" . $arr['entityName'];
$app = "告警应用:" . "\t" . $arr['app'];
$count = "告警次数:" . "\t" . $arr['count'];
if ($arr['status'] == "ACTIVE") {
    $alarmStatus = "新触发";
} elseif ($arr['status'] == "ACT") {
    $alarmStatus = "确认";
} elseif ($arr['status'] == "CLOSED") {
    $alarmStatus = "已关闭";
}
$status = "告警状态:" . "\t" . $alarmStatus;

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