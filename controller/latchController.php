<?php

require_once("Latch.php");
require_once("LatchResponse.php");
require_once("Error.php");

require_once("latchHelper.php");
require_once("latchConfig.php");




use ElevenPaths\Latch\Latch as Latch;

class LatchController {

	public static function paring() {
		// $userPaired = 
	}

	public static function otp() {

	}

	public static function doPair($chatId, $token) {
		error_log('doPair');
		$api = new Latch(LatchConfig::$appId, LatchConfig::$secret);
		$response = $api->pair($token);
		$data = $response->getData();
		error_log($data->accountId . ' endopair');
		if (!is_null($data) && property_exists($data, "accountId")) {
			$accountId = $data->accountId;
			error_log($accountId);
			error_log(!isPaired($chatId) . ' endopair');
			if (!isPaired($chatId)) {//esta pareado
				//guardo accountid
				pairPetition($chatId, $accountId);
				return true;
			} 
		} else {
			// Error Pareando
			return false;
		}
	}

	public static function doUnpair($chatId) {
		if (isPaired($chatId)) {
			$api = new Latch(LatchConfig::$appId, LatchConfig::$secret);
			$accountId = getAccountId($chatId);
			if ($accountId != null && $accountId != '') {
				$api->unpair($accountId);
				// Eliminamos el pair de la base de datos
				unpair($chatId);
				return true;
			}
		} else {
			return false;
		}
	}

	public static function checkOtp() {

	}
}
?>