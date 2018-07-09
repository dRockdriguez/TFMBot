<?php

require_once("./latch/LatchAuth.php");
require_once("./latch/LatchApp.php");
require_once("./latch/LatchUser.php");
require_once("./latch/Latch.php");
require_once("./latch/LatchResponse.php");
require_once("./latch/Error.php");

require_once("./utils/functions.php");

$website = "";
$botToken = "606239452:AAHsCqUXbro8YZtexdnSGTMosv5VxDaLoG0";
$webSite = "https://api.telegram.org/bot" . $botToken;


// Convierte el contenido de un fichero a una cadena.
//php://input: es un flujo de sólo lectura que permite leer datos del cuerpo solicitado
$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);


$chatId = $update["message"]["chat"]["id"];
$chatType = $update["message"]["chat"]["type"];

$message = $update["message"]["text"];


switch($message) {
	case '/ayuda':
		$response = "Tranquilo amiguito, todavía me están construyendo...";
		sendMessage($chatId, $response);
		break;
	case '/noticias':
		$noticias = getSubscriptions($chatId);
				
		sendMessage($chatId, $noticias);

		break;
	default:
	case '/publico':
		$r = subscribe($chatId, '1');
		if($r == "1") {
			sendMessage($chatId, 'Desubscrito a Público correctamente');	
		} else{
			sendMessage($chatId, 'Subscrito a Público correctamente');
		}
		break;
	case '/mundo':
		$r = subscribe($chatId, '2');
		if($r == "1") {
			sendMessage($chatId, 'Desubscrito a El Mundo correctamente');	
		} else{
			sendMessage($chatId, 'Subscrito a El Mundo correctamente');
		}
		break;
	case '/pais':
		$r = subscribe($chatId, '3');
		if($r == "1") {
			sendMessage($chatId, 'Desubscrito a El País correctamente');	
		} else{
			sendMessage($chatId, 'Subscrito a El País correctamente');
		}
		break;
	case '/abc':
		$r = subscribe($chatId, '4');
		if($r == "1") {
			sendMessage($chatId, 'Desubscrito a ABC correctamente');	
		} else{
			sendMessage($chatId, 'Subscrito a ABC correctamente');
		}
		break;
	case '/suscripciones':
		$lista = getListSubscriptions($chatId);
		sendMessage($chatId, $lista);
		break;
}

function sendMessage($chatId, $response) {
	$url = $GLOBALS[webSite] . "/sendMessage?chat_id=" . $chatId . "&parse_mode=HTML&text=" . urlencode($response);
	file_get_contents($url);
}



?>