<?php
require_once("sec.php");

// check tha POST parameters
$u = $_POST['username'];
$p = $_POST['password'];

$username = strip_tags($u);
$password = strip_tags($p);

// Check if user is OK
if(isUser($username, $password)) {
	// set the session
	sec_session_start();
	$_SESSION["userAgent"] = $_SERVER["HTTP_USER_AGENT"];
	$_SESSION["IP"] = $_SERVER["REMOTE_ADDR"];
	$_SESSION['login_string'] = hash('sha512', "Come_On_You_Spurs" +$u); 
	$_SESSION['user'] = $u;
	header("Location: mess.php");
}
else {
	// To bad
	header('HTTP/1.1 401 Unauthorized');
}
