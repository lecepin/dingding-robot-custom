<?php
error_reporting(0);

include './utils.php';

// 机器人 WebHook
$DING_WEB_HOOK = "https://oapi.dingtalk.com/robot/send?access_token=xxxx";
// 机器人加签密钥
$DING_SECRET = "xxxx";

$RECORD_FILE = 'user-list.txt';
$receiveDingMsg = json_decode(file_get_contents("php://input"));
$dingContent = trim($receiveDingMsg->text->content);
$record = getFileContent($RECORD_FILE, true, '{"index":0,"users":[]}');

// 命令：加人
if (mb_substr($dingContent, 0, 3) == '加人:') {
  $name = explode(':', $dingContent)[1];

  if ($name) {
    array_push($record->users, $name);
  }

  setFileContent($RECORD_FILE, json_encode($record));
  sendUserInfo($record, $DING_SECRET, $DING_WEB_HOOK);
  die;
}

// 命令：上一人
if ($dingContent == '上一人') {
  $len = count($record->users);

  if ($record->index == 0) {
    $record->index = $len - 1;
  } else {
    $record->index--;
  }

  setFileContent($RECORD_FILE, json_encode($record));
  sendUserInfo($record, $DING_SECRET, $DING_WEB_HOOK);
  die;
}

// 命令：下一人
if ($dingContent == '下一人') {
  $len = count($record->users);

  if ($record->index >= $len - 1) {
    $record->index = 0;
  } else {
    $record->index++;
  }

  setFileContent($RECORD_FILE, json_encode($record));
  sendUserInfo($record, $DING_SECRET, $DING_WEB_HOOK);
  die;
}

// 命令：排期
if ($dingContent == '排期') {
  sendUserInfo($record, $DING_SECRET, $DING_WEB_HOOK);
  die;
}

// 命令：空
if ($receiveDingMsg->text) {
  sendDingMsg(
    $DING_SECRET,
    $DING_WEB_HOOK,
    "### 你说的我听不懂唉，我只能听懂下面的命令:\n\n" .
      "- 排期\n\n" .
      "- 上一人\n\n" .
      "- 下一人\n\n" .
      "- 加人:张三\n\n",
    '支持命令',
    'markdown'
  );
  die;
}

function sendUserInfo($record, $secret, $ding_hook)
{
  $list = '';
  foreach ($record->users as $key => $value) {
    if ($key == $record->index) {
      $list .= '> **➤' . $value . "**\n\n";
    } else {
      $list .= '> ' . $value . "\n\n";
    }
  }

  sendDingMsg(
    $secret,
    $ding_hook,
    "### 排期人员:\n\n" .
      $list,
    "排期人员",
    'markdown'
  );
}
