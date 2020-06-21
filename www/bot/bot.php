<?php

function bot_sendMessage($user_id, $msg, $attachments) {
	vkApi_messagesSend($user_id, $msg, $attachments);
}

function bot_dialog($user_id, $user_msg) {
	if (strripos($user_msg, ", ") == false) return;
	
	$mas = explode(", ", $user_msg);
	
	$city_file = fopen("city.txt", "w");
	fwrite($city_file, $mas[0]);
	fclose($city_file);
	
	$cat_file = fopen("cat.txt", "w");	
	fwrite($cat_file, $mas[1]);
	fclose($cat_file);
}

?>