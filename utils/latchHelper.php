<?php

	function pairPetition ($chatId) {
		include("conexion.php");


		$query = "SELECT token FROM pairPetitions WHERE userId='$chatId'";
		$res = $conn->query($query);

		// Si ya tenía una petición, la borro y añado una nueva, si no tenía petición simplemente la añado
		if ($res->num_rows > 0) {
			$delete = "DELETE FROM pairPetitions WHERE userId = '$chatId'";
			$conn->query($delete);
		}
 
 		$insert = "INSERT INTO pairPetitions(userId, token) values ('$chatId', '')";
		$res = $conn->query($insert);

		$conn->close();
	}

	function isPaired ($chatId) {
		include("conexion.php");

		// OCompruebo si el usuario tiene latchId (está pareado)
		$query = "SELECT latchId FROM usuarios WHERE usuario='$chatId'";
		$res = $conn->query($query);

		// Ya estábas suscrito
		if ($res->num_rows > 0) {
			$conn->close();
		   return true; // Ya está pareado
		} else {
			$conn->close();
			return false; // no está pareado
		}
 
	}

?>