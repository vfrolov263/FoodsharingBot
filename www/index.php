<?php
define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation');
define('CALLBACK_API_EVENT_MESSAGE_NEW', 'message_new');
define('CALLBACK_API_EVENT_WALL_POST_NEW', 'wall_post_new');

require_once 'config.php';
require_once 'global.php';

require_once 'api/vk_api.php';
require_once 'bot/bot.php';

if (!isset($_REQUEST)) {
	exit;
}

callback_handleEvent();

function callback_handleEvent() {
	$event = _callback_getEvent();

	try {
		switch ($event['type']) {
		//Подтверждение сервера
		case CALLBACK_API_EVENT_CONFIRMATION:
        	_callback_handleConfirmation();
        	break;

      		//Получение нового сообщения
      		case CALLBACK_API_EVENT_MESSAGE_NEW:
		_callback_handleMessageNew($event['object']);
        	break;

   		//Получение новой новости
   		case CALLBACK_API_EVENT_WALL_POST_NEW:  //wall_post_new
		_callback_handleNewPOST($event['object']);
       		break;

     		 default:
      		_callback_okResponse();
      		break;
		}
	} catch (Exception $e) {
		log_error($e);
	}
}

function _callback_getEvent() {
	return json_decode(file_get_contents('php://input'), true);
}

function _callback_handleConfirmation() {
	_callback_response(CALLBACK_API_CONFIRMATION_TOKEN);
}

function _callback_handleMessageNew($data) {
	// Получем id пользователя и текст сообщения, затем запускаем диалог (парсинг сообщения и запись критериев).
	$user_id = $data['user_id'];
	$user_msg = $data['body'];
	bot_dialog($user_id, $user_msg);
	_callback_okResponse();
}

function _callback_handleNewPOST($data) {
	// В рамках прототипа идентификаторы статичны, просматривается одна группа.
	$arr = array('116570111', '88914416', '18181353'); 
	$attachment = array('wall-196461737_'.$data['id']);
	$message = $data['text'];	
	
	// Критерии для определения подходящей новости.
	$city = file_get_contents("city.txt");
	$cat = file_get_contents("cat.txt");
	
	// Ищем название города и категории пищи в тексте новости.
	$pos1 = mb_strpos($message, $city);
	$pos2 = mb_strpos($message, $cat);

	if (($pos1 === false) || ($pos2 === false)) {
		 _callback_okResponse();
		 return;
	}
	
	bot_sendMessage($arr[1], 'Найдено предложение:', $attachment);
	_callback_okResponse();
}


function _callback_okResponse() {
	_callback_response('ok');
}

function _callback_response($data) {
	echo $data;
	exit();
}

?>
