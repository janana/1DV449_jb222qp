<?php

require_once("config.php");
require_once("Producer.php");
require_once("DAL.php");

ini_set('display_errors', 1);  
error_reporting(E_ALL);

$DAL = new DAL();
$producerList = $DAL->getProducers();

$producerHTML = "<table class='table table-striped table-bordered table-condensed'><thead>
					<tr>
						<td>ID</td>
						<td>Namn</td>
						<td>Ort</td>
						<td>Hemsida</td>
						<td>Skrapnings-status</td>
					</tr>
				</thead><tbody>";



foreach($producerList as $producer) {

	$producerHTML .= "
					<tr>
						<td>$producer->id</td>
						<td>$producer->name</td>
						<td>$producer->location</td>
						<td>$producer->homepage</td>
						<td>$producer->status</td>
					</tr>";

}

$producerHTML .= "</tbody></table>";

$producerHTMLEncode = iconv('UTF-8', "ISO-8859-1", $producerHTML);

echo "<!DOCTYPE html>
			<html lang='sv'>
				<head>
					<meta charset='UTF-8' />
					<link href='css/Stylesheet.css' type='text/css' rel='stylesheet' />
					<link href='css/bootstrap.css' type='text/css' rel='stylesheet' />
					<title>Laboration 1 - jb222qp</title>
				</head>
				<body>
					<div id='container'>
					<h1>Laboration 1 - Jb222qp</h1>
						<h2>Skrapad data</h2>
						$producerHTMLEncode
					</div>
				</body>
			</html>";