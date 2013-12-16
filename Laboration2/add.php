<?php

/**
* Called from AJAX to add stuff to DB
*/
function addToDB($name, $message, $pid) {
	$db = null;
	
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
	$q = "INSERT INTO messages (message, name, pid) VALUES(?, ?, ?)";// message, name, pid
	
	try {
		$st = $db->prepare($q);
		$st->bindParam(1, $message, PDO::PARAM_STR);
		$st->bindParam(2, $name, PDO::PARAM_STR);
		$st->bindParam(3, $pid, PDO::PARAM_INT);
		if (!$st->execute()) {
			die("Fel vid insert");
		}
	}
	catch(PDOException $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
}
