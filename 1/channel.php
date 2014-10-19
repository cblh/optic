<?php
$channel = new SaeChannel();
// channel 名称
$channel_name = 'js';
// 过期时间，默认为3600秒
$duration = 1000;
//创建一个channel，返回一个地址
$url = $channel->createChannel($channel_name,$duration);
echo $url;
// die();
// 往名为$channel_name的channel里发送一条消息
$message = 'Hello,SAE channel!';
$channel->sendMessage($channel_name,$message);
// var_dump($url);
return $url;
?>