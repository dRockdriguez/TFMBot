<?php

require_once("./utils/functions.php");
require_once("./controller/latchController.php");
require_once("./controller/latchConfig.php");

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


$par = explode(" ", $message);

switch($par[0]) {
	case '/ayuda':
		$response = "Tranquilo amiguito, todavía me están construyendo...";
		sendMessage($chatId, $response);
		break;
	case '/noticias':
		$latchStatus = LatchController::checkLatchStatus($chatId,  LatchConfig::$noticias);
		if($latchStatus == 'off') {
			sendMessage($chatId, 'Latch está cerrado');	
		} else {
			$noticias = getSubscriptions($chatId);	
			sendMessage($chatId, $noticias);	
		}
		break;
	case '/publico':
		$latchStatus = LatchController::checkLatchStatus($chatId,  LatchConfig::$subscription);
		if($latchStatus == 'off') {
			sendMessage($chatId, 'Latch está cerrado');	
		} else {
			$r = subscribe($chatId, '1');
			if($r == "1") {
				sendMessage($chatId, 'Desubscrito a Público correctamente');	
			} else{
				sendMessage($chatId, 'Subscrito a Público correctamente');
			}
		}
		break;
	case '/mundo':
		$latchStatus = LatchController::checkLatchStatus($chatId,  LatchConfig::$subscription);
		if($latchStatus == 'off') {
			sendMessage($chatId, 'Latch está cerrado');	
		} else {
			$r = subscribe($chatId, '2');
			if($r == "1") {
				sendMessage($chatId, 'Desubscrito a El Mundo correctamente');	
			} else{
				sendMessage($chatId, 'Subscrito a El Mundo correctamente');
			}
		}
		break;
	case '/pais':
		$latchStatus = LatchController::checkLatchStatus($chatId,  LatchConfig::$subscription);
		if($latchStatus == 'off') {
			sendMessage($chatId, 'Latch está cerrado');	
		} else {
			$r = subscribe($chatId, '3');
			if($r == "1") {
				sendMessage($chatId, 'Desubscrito a El País correctamente');	
			} else{
				sendMessage($chatId, 'Subscrito a El País correctamente');
			}
		}
		break;
	case '/abc':
		$latchStatus = LatchController::checkLatchStatus($chatId,  LatchConfig::$subscription);
		if($latchStatus == 'off') {
			sendMessage($chatId, 'Latch está cerrado');	
		} else {
			$r = subscribe($chatId, '4');
			if($r == "1") {
				sendMessage($chatId, 'Desubscrito a ABC correctamente');	
			} else{
				sendMessage($chatId, 'Subscrito a ABC correctamente');
			}
		}
		break;
	case '/suscripciones':
		$latchStatus = LatchController::checkLatchStatus($chatId,  LatchConfig::$subscription);
		if($latchStatus == 'off') {
			sendMessage($chatId, 'Latch está cerrado');	
		} else {
			$lista = getListSubscriptions($chatId);
			sendMessage($chatId, $lista);
		}
		break;
	case '/pair':
		if (count($par) < 2) {
			sendMessage($chatId, 'Se ha olvidado de enviar el token');	
		} else {
			$r = LatchController::doPair($chatId, $par[1]);
			error_log('se ha pareao ' . $r);
			if($r) {
				sendMessage($chatId, 'Se ha pareado con éxito');	
			} else {
				sendMessage($chatId, 'No se ha podido parear');	
			}
		}
		break;
	case '/unpair':
		$r = LatchController::doUnpair($chatId);
		if($r) {
			sendMessage($chatId, 'Se ha despareado con éxito');	
		} else {
			sendMessage($chatId, 'No se ha podido desparear');	
		}
		break;
	default:
		sendMessage($chatId, 'El comando aún no se ha implementado');
}

function sendMessage($chatId, $response) {
	$url = $GLOBALS[webSite] . "/sendMessage?chat_id=" . $chatId . "&parse_mode=HTML&text=" . urlencode($response);
	file_get_contents($url);
}

?>