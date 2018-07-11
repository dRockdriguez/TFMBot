<?php

	function pairPetition ($chatId, $token) {
		include("conexion.php");

		$query = "SELECT token FROM pairPetitions WHERE userId='$chatId'";
		$res = $conn->query($query);

		// Si ya tenía una petición, la borro y añado una nueva, si no tenía petición simplemente la añado
		if ($res->num_rows > 0) {
			$delete = "DELETE FROM pairPetitions WHERE userId = '$chatId'";
			$conn->query($delete);
		}
 
 		error_log('inserto');
 		$insert = "INSERT INTO pairPetitions(userId, token) values ('$chatId', '$token')";
 		error_log($insert);
		$res = $conn->query($insert);

		$conn->close();
	}

	function getAccountId($chatId) {
		include("conexion.php");

		$query = "SELECT token FROM pairPetitions WHERE userId='$chatId'";
		$res = $conn->query($query);
		$token = "";

		// Si ya tenía una petición, la borro y añado una nueva, si no tenía petición simplemente la añado
		if ($res->num_rows > 0) {
			  while($row = $res->fetch_assoc()) {
		    	$token = $row["token"];
		    	error_log($token . ' en la query');
		    }
		}

		$conn->close();

		return $token;
	}

	function unpair($chatId) {
		include("conexion.php");

		$query = "SELECT token FROM pairPetitions WHERE userId='$chatId'";
		$res = $conn->query($query);

		// Si ya tenía una petición, la borro y añado una nueva, si no tenía petición simplemente la añado
		if ($res->num_rows > 0) {
			$delete = "DELETE FROM pairPetitions WHERE userId = '$chatId'";
			$conn->query($delete);
		}
 
		$conn->close();
	}

	function isPaired ($chatId) {
		include("conexion.php");

		$query = "SELECT token FROM pairPetitions WHERE userId='$chatId'";
		$res = $conn->query($query);

		error_log($query);

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