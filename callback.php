<?php
	/* 輸入申請的Line Developers 資料  */
	$channel_id = "Channel ID";
	$channel_secret = "Channel Secret";
	$mid = "MID";
	 
	/* 將收到的資料整理至變數 */
	$receive = json_decode(file_get_contents("php://input"));
	$text = $receive->result{0}->content->text;
	$from = $receive->result[0]->content->from;
	$content_type = $receive->result[0]->content->contentType;
	 
	/* 準備Post回Line伺服器的資料 */
	$header = ["Content-Type: application/json; charser=UTF-8", "X-Line-ChannelID:" . $channel_id, "X-Line-ChannelSecret:" . $channel_secret, "X-Line-Trusted-User-With-ACL:" . $mid];
	$message = getBoubouMessage($content_type, $text);
	sendMessage($header, $from, $message);

	 
	/* 發送訊息 */
	function sendMessage($header, $to, $message) {
	 
		$url = "https://trialbot-api.line.me/v1/events";
		$data = ["to" => [$to], "toChannel" => 1383378250, "eventType" => "138311608800106203", "content" => ["contentType" => 1, "toType" => 1, "text" => $message]];
		$context = stream_context_create(array(
		"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
		));
		file_get_contents($url, false, $context);
	}
	 
	function getBoubouMessage( $type, $value){	
		if($type == 1){
			return "寶寶" . $value ."，只是寶寶不說";
		}else{
			return "寶寶看不懂，只是寶寶不說";
		}
	}
?>