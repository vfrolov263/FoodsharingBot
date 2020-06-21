<?php

function bot_sendMessage($user_id, $msg, $attachments) {
	vkApi_messagesSend($user_id, $msg, $attachments);
}

function bot_dialog($user_id, $user_msg) {
	// Сообщения принимаются в формате: город, категория
	if (strripos($user_msg, ", ") == false) return;
	
	// Выделяем слова
	$mas = explode(", ", $user_msg);
	
	// Далее записываем город и категорию.
	$city_file = fopen("city.txt", "w");
	fwrite($city_file, $mas[0]);
	fclose($city_file);
	
	$cat_file = fopen("cat.txt", "w");	
	fwrite($cat_file, $mas[1]);
	fclose($cat_file);
}

?>
