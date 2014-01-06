<?php

require_once("db/config.php");
require_once("db/DAL.php");
require_once("db/UserDAL.php");

error_reporting(E_ALL);

// Handle users in db

$userDAL = new UserDAL();
$savedUsers = $userDAL->getUsers();
$userFound = false;
$user = null;

$name = $_GET["name"];
$id = $_GET["id"];

foreach($savedUsers as $savedUser) {
	if ($name == $savedUser["name"] && 
		$id == $savedUser["id"]) {
		
		$userFound = true;
		$user = $savedUser;
	}
}

if ($_GET["funct"] == "addUser") {
	if ($userFound == false) {
		try {
			$userDAL->addUser($name, $id);
			echo "User saved";
		} catch (Exception $e) {
			echo $e;
		}
	} else {
		echo "User found;".$user["diet"];
	}
} else if ($_GET["funct"] == "saveDiet") {
	$diet = $_GET["diet"];
	
	if ($userFound == true) {
		try {
			$userDAL->saveDiet($id, $diet);
			echo "Diet saved";
		} catch (Exception $e) {
			echo $e;  
		}
	} else {
		echo "Användaren hittades inte när kosten skulle sparas i databasen.";
	}
	
}
