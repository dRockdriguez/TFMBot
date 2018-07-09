<?php

	// Create connection
	$conn = new mysqli("localhost", "drockdri_root", "5vVD^![~OTF,", "drockdri_PruebaTFM");

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";

?>