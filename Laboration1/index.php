<?php

require_once("config.php");
require_once("DAL.php");
require_once("Producer.php");

ini_set('display_errors', 1);  
error_reporting(E_ALL);

try {
	$url = "http://vhost3.lnu.se:20080/~1dv449/scrape/";
	$frontpage = getPageFromURL($url, false);
	if ($frontpage != false) {
		$form = getQuery($frontpage, '//form[@class = "form-signin"]');
		
		$action = $form->item(0)->getAttribute("action");
		$loggedInURL = doLogin($url . $action);
		
		$loggedInPage = getPageFromURL($loggedInURL, true);
		if ($loggedInPage != false) {
			$producerLinkList = getQuery($loggedInPage, '//table[@class = "table table-striped"]//a');
			$newURL = $url . "secure/";

			$DAL = new DAL();

			foreach ($producerLinkList as $link) {
				$href = $link->getAttribute("href");
				$producerURL = $newURL . $href;
				$producerPage = getPageFromURL($producerURL, true);
				if ($producerPage == false) {
					// TAKE CARE OF DEAD LINKS!!!!! SAVE THEM IN DB ANYWAY??
					echo "Page could not be opened";
				} else {
					$name = $link->nodeValue;
					var_dump($producerPage); 
					$linkItems = getQuery($producerPage, '//div[@class = "container"]//p//a');
					
					if ($linkItems->length < 1) {
						$homepage = "#";
					} else {
						var_dump($linkItems);
						$homepage = $linkItems->item(0)->getAttribute("href");
					}
					



					var_dump($homepage);

				}
				

				// Get the name from link
				// Get the url to homepage
				// Get the location from span with class="ort"
				// Get the pic src

				// CHECK IF EVERYTHING IS THERE!!!!!!
			}
		}
		
	}
	throw new Exception("Something went wrong");
} catch (Exception $e) {
	echo("<br/><br/>".$e->getMessage());
}

//	Save values in database. check if anything is missing href/src eg...

//	TODO:
//			Get the info out of the site and save to db
//			Create site for getting items from the db
//			Log 404s in db


function getQuery($page, $query) {
	$dom = new DOMDocument();
	if ($dom->loadHTML($page)) {
		$x = new DOMXPath($dom);
		return $x->query($query);
	}

	throw new Exception("Could not load HTML from page");
}
function getPageFromURL($url, $hasSavedCookie) {
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FAILONERROR, true);

	if ($hasSavedCookie) {
		curl_setopt($curl, CURLOPT_COOKIEFILE, "kakor.txt");
	}

	$output = curl_exec($curl);
	curl_close($curl);

	return $output;
}
function doLogin($url) {
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);

	$postValues = array(
		"username" => "admin",
		"password" => "admin"
	);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postValues);
	
	curl_setopt($curl, CURLOPT_HEADER, true);
	//curl_setopt($curl, CURLINFO_HEADER_OUT, true);

	curl_setopt($curl, CURLOPT_COOKIEJAR, "kakor.txt");
	curl_exec($curl);

	$location = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
	curl_close($curl);

	if ($location != false) {
		return $location;
	}
	throw new Exeption("No location found");
}


