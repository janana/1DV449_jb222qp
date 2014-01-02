<?php

require_once("db/config.php");
require_once("db/DAL.php");
require_once("db/UserDAL.php");

error_reporting(E_ALL);

// Handle users in db

$name = $_GET["name"];
$id = $_GET["id"];

$userDAL = new UserDAL();
$savedUsers = $userDAL->getUsers();
$userFound = false;

foreach($savedUsers as $user) {
	if ($name == $user["name"] && 
		$id == $user["id"]) {
		
		$userFound = true;
	}
}
if ($userFound == false) {
	$userDAL->addUser($name, $id);
	echo "Användare tillagd ".$name." ".$id;
} else {
	echo "Välkommen ".$name;
}

