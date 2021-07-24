<?php

function sendPostReq($url, $data, $header = array("Content-type: application/json"))
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

  $res = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return $err;
  }

  if ($res) {
    return $res;
  }
}

function sendDingMsg($secret, $ding_hook, $msg, $title, $type = "text", $ats = array(), $atAll = false)
{
  // $now = (int) (microtime(true) * 1000);
  $now = time() * 1000;
  $stringToSign = $now . "\n" . $secret;
  $sign = base64_encode(hash_hmac('sha256', $stringToSign, $secret, true));
  $sendUrl = $ding_hook . '&' . http_build_query(array(
    'timestamp' => $now,
    'sign' => $sign
  ));
  $data = json_encode(array(
    'msgtype' => $type, // text/markdown
    'markdown' => array(
      'title' => $title,
      'text' => $msg,
    ),
    'text' => array(
      'content' => $msg,
    ),
    'at' => array(
      'atMobiles' => $ats,
      'isAtAll' => $atAll,
    ),
  ));

  $res = json_decode(sendPostReq($sendUrl, $data));

  return $res->errcode == 0;
}

function getFileContent($fileName, $formatJSON = true, $newFileContent = '[]')
{
  if (!file_exists($fileName)) {
    $f = fopen($fileName, 'w+');

    fwrite($f, $newFileContent);
    fclose($f);
  }

  $content = file_get_contents($fileName);
  return $formatJSON ? json_decode($content) : $content;
}

function setFileContent($fileName, $content)
{
  $f = fopen($fileName, 'w+');

  fwrite($f, $content);
  fclose($f);
}
