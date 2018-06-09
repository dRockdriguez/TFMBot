<?php

require_once("./latch/LatchAuth.php");
require_once("./latch/LatchApp.php");
require_once("./latch/LatchUser.php");
require_once("./latch/Latch.php");
require_once("./latch/LatchResponse.php");
require_once("./latch/Error.php");

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
		getNoticias($chatId);
		break;
	default:
	break;
}

function sendMessage($chatId, $response) {
	$url = $GLOBALS[webSite] . "/sendMessage?chat_id=" . $chatId . "&parse_mode=HTML&text=" . urlencode($response);
	file_get_contents($url);
}

function getNoticias($chatId) {
	include('./simple_html_dom.php');

	$context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
	$url = "http://www.publico.es/rss/espana";

	$xmlString = file_get_contents($url, false, $context);
	$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
	$json = json_encode($xml);
	
	$array = json_decode($json, TRUE);
	$titulos = "<b>Noticias Público</b>";
	for ($i=0; $i < 9; $i++) { 
		$titulos = $titulos . "\n\n". $array['channel']['item'][$i]['title'] . "<a href='" .  $array['channel']['item'][$i]['link'] 
							. "'>+info</a>";
	}						

	var_dump($titulos);
	sendMessage($chatId, $titulos);
}

?>