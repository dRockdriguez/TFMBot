<?php

	function subscribe ($chatId, $periodico) {
		include("conexion.php");

		// Obtiene las subscripciones
		$query = "SELECT subscription FROM subscription WHERE userId='$chatId' AND subscription='$periodico' ";
		$res = $conn->query($query);

		// Ya estábas suscrito
		if ($res->num_rows > 0) {
			$delete = "DELETE FROM subscription WHERE userId = '$chatId' AND subscription = '$periodico'";
			$conn->query($delete);
			$conn->close();
		   return "1"; // Ya está suscrito
		} else {
			// Subscribe Público
			$query = "INSERT INTO subscription(userId, subscription) values ('$chatId', '$periodico')";
			$res = $conn->query($query);

			if ($res) {
				echo("ok");
			} else {
				 die("Error al insertar");
			}
		}
 	
		$conn->close();

		return "0"; // No está suscrito
	}

	function getSubscriptions ($chatId) {
		include("conexion.php");
		include("simple_html_dom.php");
		$noticias = "";
		// Obtiene las subscripciones
		$query = "SELECT subscription FROM subscription WHERE userId='$chatId' ";
		$res = $conn->query($query);

		if ($res->num_rows > 0) {
		    // output data of each row
		    while($row = $res->fetch_assoc()) {
		    	if ($row["subscription"] == "1") {
					$noticias .= getNoticiasPublico($chatId) . "\n\n";
			    }
			    if ($row["subscription"] == "2") {
					$noticias .= getNoticiasMundo($chatId) . "\n\n";
			    }
			    if ($row["subscription"] == "3") {
					$noticias .= getNoticiasPais($chatId) . "\n\n";
			    }
			    if ($row["subscription"] == "4") {
					$noticias .= getNoticiasABC($chatId) . "\n\n";
			    }
		    }
		}
		else {
			$noticias=  'Aún no estás suscrito a nada';
		}

		$res->close();
		$conn->close();

		return $noticias;
	}

	function getListSubscriptions ($chatId) {
		include("conexion.php");
		$noticias = "";
		// Obtiene las subscripciones
		$query = "SELECT subscription FROM subscription WHERE userId='$chatId' ";
		$res = $conn->query($query);

		if ($res->num_rows > 0) {
		    // output data of each row
		    $lista = "<b>Lista de suscripciones</b>\n";
		    while($row = $res->fetch_assoc()) {		    	
		    	if ($row["subscription"] == "1") {
					$lista .= " - Público\n";
			    }
			    if ($row["subscription"] == "2") {
					$lista .= " - El Mundo\n";
			    }
			    if ($row["subscription"] == "3") {
					$lista .= " - El País\n";
			    }
			    if ($row["subscription"] == "4") {
					$lista .= " - ABC\n";
			    }
		    }
		}
		else {
			$lista =  'Aún no estás suscrito a nada';
		}

		$res->close();
		$conn->close();

		return $lista;
	}
		

	function getNoticiasPublico($chatId) {	
		$url = "http://www.publico.es/rss/espana";

		$xmlString = file_get_contents($url, false, $context);
		$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		
		$array = json_decode($json, TRUE);
		$titulos = "<b>Noticias Público</b>";
		for ($i=0; $i < 9; $i++) { 
			$titulos = $titulos . "\n\n". $array['channel']['item'][$i]['title'] . "... <a href='" .  $array['channel']['item'][$i]['link'] 
								. "'> + info</a>";
		}					
		return $titulos;
	}

	function getNoticiasMundo($chatId) {
		$context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
		$url ="http://estaticos.elmundo.es/elmundo/rss/portada.xml";
		$xmlString = file_get_contents($url, false, $context);
		$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		
		$array = json_decode($json, TRUE);
		$titulos = "<b>Noticias El Mundo</b>";
		for ($i=0; $i < 9; $i++) { 
			$titulos = $titulos . "\n\n". $array['channel']['item'][$i]['title'] . "... <a href='" .  $array['channel']['item'][$i]['link'] 
								. "'> + info</a>";
		}		
		return $titulos;
	}

	function getNoticiasPais($chatId) {
		$context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
		$url ="http://ep00.epimg.net/rss/elpais/portada.xml";
		$xmlString = file_get_contents($url, false, $context);
		$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		
		$array = json_decode($json, TRUE);
		$titulos = "<b>Noticias El País</b>";
		for ($i=0; $i < 9; $i++) { 
			$titulos = $titulos . "\n\n". $array['channel']['item'][$i]['title'] . "... <a href='" .  $array['channel']['item'][$i]['link'] 
								. "'> + info</a>";
		}									
		return $titulos;
	}

	function getNoticiasABC($chatId) {
		$context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
		$url ="https://www.abc.es/rss/feeds/abcPortada.xml";
		$xmlString = file_get_contents($url, false, $context);
		$xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		
		$array = json_decode($json, TRUE);
		$titulos = "<b>Noticias ABC</b>";
		for ($i=0; $i < 9; $i++) { 
			$titulos = $titulos . "\n\n". $array['channel']['item'][$i]['title'] . "... <a href='" .  $array['channel']['item'][$i]['link'] 
								. "'> + info</a>";
		}									
		return $titulos;
	}




?>