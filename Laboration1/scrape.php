<?php

require_once("config.php");
require_once("DAL.php");
require_once("Producer.php");

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
			$newURL = "";
			if (preg_match('#(.*)producenter.php#', $loggedInURL, $u)) {
				$newURL = $u[1];
			}

			$DAL = new DAL();

			foreach ($producerLinkList as $link) {
				$href = $link->getAttribute("href");
				$producerURL = $newURL . $href;
				$producerPage = getPageFromURL($producerURL, true);

				$id = "";
				if (preg_match('#(.*)producent_([1-9]*)(.*)#', $producerURL, $i)) {
					$id = $i[2];
				}
				$name = $link->nodeValue;
				
				if ($producerPage == false) {
					$producer = new Producer($name, "-", "-", $id, "Sidan existerar inte");
				} else {
					$status = "";
					$linkItems = getQuery($producerPage, '//p//a');
					$homepage = "#";
					if ($linkItems->length == 1) {
						$homepage = $linkItems->item(0)->getAttribute("href");
					} 
					if ($homepage == "#") {
						$status .= "Producentens hemsida saknas. ";
						$homepage = "-";
					}

					$locationItem = getQuery($producerPage, '//span[@class = "ort"]');
					$locationString = $locationItem->item(0)->nodeValue;
					$location = "";
					if (preg_match('#Ort: (.*)#', $locationString, $l)) {
						$location = trim($l[1]);
					} else {
						$status .= "Ort saknas.";
					}
					
					$producer = new Producer($name, $homepage, $location, $id, $status);
				}
				$DAL->addProducer($producer);
			}
				echo "Sidan har skrapats";
		}	
	}
} catch (Exception $e) {
	echo("<br/><br/>".$e->getMessage());
}


function getQuery($page, $query) {
	$dom = new DOMDocument();
	if (@$dom->loadHTML($page)) { //@ to delete warning for unescaped chars in HTML-code which is not relevant
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


