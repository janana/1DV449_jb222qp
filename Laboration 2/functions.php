<?php
require_once("get.php");
require_once("add.php");
require_once("sec.php");
sec_session_start();

/*
* It's here all the ajax calls goes
*/ 
if(isset($_GET['function'])) {
	
	if($_GET['function'] == 'logout') {
		logout();
    } elseif($_GET['function'] == 'add') {
       
	    $name = $_GET["name"];
		$message = $_GET["message"];
		$pid = $_GET["pid"];
		
		addToDB($name, $message, $pid);
		echo "Det gick fint! Ladda om sidan för att se ditt meddelande!";
    }
    elseif($_GET['function'] == 'producers') {
    	$pid = $_GET["pid"];
   		echo(json_encode(getProducer($pid)));
    }
    elseif($_GET['function'] == 'getIdsOfMessages') {
       	$pid = $_GET["pid"];
   	   	echo(json_encode(getMessageIdForProducer($pid)));
    }  
    elseif($_GET['function'] == 'getMessage') {
       	$serial = $_GET["serial"];
   	   	echo(json_encode(getMessage($serial)));
    }  
}