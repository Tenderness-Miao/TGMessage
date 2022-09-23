<?php
require_once 'Bot.php';
date_default_timezone_set("Asia/Shanghai");
header('content-type: application/json');
$arr = json_decode(file_get_contents('php://input'), true);
$alarmId = "告警编号:" . "\t" . $arr['alarmId'];
$creationTime = "告警时间:" . "\t" . $arr['creationTime'];
$priority = "告警级别:" . "\t" . $arr['priority'];
$alarmName = "告警标题:" . "\t" . $arr['alarmName'];
$alarmContent = "告警内容:" . "\t" . $arr['alarmContent'];
$appDescription = "告警应用:" . "\t" . $arr['appDescription'];
$count = "告警次数:" . "\t" . $arr['count'];
if ($arr['status'] == "ACTIVE") {
    $alarmStatus = "新触发";
} elseif ($arr['status'] == "ACK") {
    $alarmStatus = "确认";
} elseif ($arr['status'] == "CLOSED") {
    $alarmStatus = "已关闭";
} else {
    $level = "已认领或已转发";
}
$status = "告警状态:" . "\t" . $alarmStatus;


$data = "您有新的告警信息" . "\n" . $alarmId . "\n" . $creationTime . "\n" . $priority . "\n" . $alarmName . "\n" . $alarmContent . "\n" . $appDescription . "\n" . $count . "\n" . $status;

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
