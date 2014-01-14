<?php

require_once("db/config.php");
require_once("db/DAL.php");
require_once("db/RecipeDAL.php");

error_reporting(E_ALL);

// Handle recipes in db

$recipeDAL = new RecipeDAL();
$savedRecipes = $recipeDAL->getRecipes(); // Without category and ingredients
$format = "d-m-y";
$todaysDate = date($format);
$isUpdateTime = false;
$savedDate = file_get_contents("updateRecipe.txt");
if ($savedDate != $todaysDate) {
	// Time to update recipes
	$isUpdateTime = true;
	file_put_contents("updateRecipe.txt", $todaysDate);
}

/**
 * CategoryID:
 * 5. Vegetariska recept
 * 6. Veganska recept
 */
 
 /**
  * Comments:
  * dislike
  * like
  */
 
if ($isUpdateTime) {
	// Get the recipes from säsongsmat website
	$domain = "http://xn--ssongsmat-v2a.nu/";
	
	$url = $domain . "ssm/Kategori:Recept?action=render";
	$page = getPageFromURL($url);
	
	$links = getQuery($page, "//table[@class='responsivetable sortable']//tr//td[@data-title='Recept']/a");
	$categLinks = getQuery($page, "//table[@class='responsivetable sortable']//tr//td[@data-title='Kategori']");
	
	if ($links->length == $categLinks->length) {
		for ($i = 0; $i < $links->length; $i++) {
			$categLink = $categLinks->item($i)->childNodes->item(1);
			
			if ($categLink->nodeValue == "VarmrÃ¤tter" ||
				$categLink->nodeValue == "Sallader" ||
				$categLink->nodeValue == "Soppor" ||
				$categLink->nodeValue == "FÃ¶rrÃ¤tter och smÃ¥rÃ¤tter") {
					
				$title = $links->item($i)->nodeValue;
				// Test if new recipes exist, if it's new, extract info and add to db
				$exists = false;
				foreach($savedRecipes as $recipe) {
					if ($recipe["title"] == $title) {
						$exists = true;
					}
				}
				if (!$exists) {
					$href = $links->item($i)->getAttribute("href");
					
					// Get each site
					if (preg_match('#(.*)Recept:(.*)#', $href, $out)) {
						$recipeURL = $domain."ssm/Recept:".$out[2] . "?action=render";
						$recipePage = getPageFromURL($recipeURL);
						
						// Pic
						$picNode = getQuery($recipePage, "//a[@class='image']/img");
						$pic = "-";
						if ($picNode->length > 0) {
							$pic = $domain . $picNode->item(0)->getAttribute("src");
						}
						
						// Portions
						$portionsNode = getQuery($recipePage, "//span[@itemprop='recipeYield']");
						$portions = " ";
						if ($portionsNode->length > 0) {
							$portions = $portionsNode->item(0)->nodeValue;
							$portions = str_replace("â€“", "", $portions);
						}
					
						// Instructions
						$instructionNodes = getQuery($recipePage, "//p");
						$instruction = "";
						for ($k = 0; $k < $instructionNodes->length; $k++) {
							if ($k >= 4) {
								$instruction .= $instructionNodes->item($k)->nodeValue ."<br/>";
							}
						}
						if ($instruction == "") {
							$instruction = "-";
						}
						
						
						// Categories
						$categoryNodesParent = getQuery($recipePage, "//p[@itemprop='recipeCategory']");
						$categoryNodes = $categoryNodesParent->item(0)->childNodes;
						$categories = array();
						foreach($categoryNodes as $categoryNode) {
							if ($categoryNode->hasAttributes()) {
								$categoryName = $categoryNode->getAttribute("title");
								switch ($categoryName) {
									case "Vegetariska recept":
										$categories[] = 5;
										break;
									case "Veganskt":
										$categories[] = 6;
										break;
									default:
										break;
								}
							}
						}
						
						
						// Ingredients
						$ingredients = array();
						$ingredientNodes = getQuery($recipePage, "//body/ul/li/span");
						foreach($ingredientNodes as $ingredientNode) {
							if ($ingredientNode->hasChildNodes()) {
								// Remove duplicated values
								$children = $ingredientNode->childNodes;
								foreach ($children as $child) {		
									if ($child->hasChildNodes()) {
										$spans = $child->childNodes;
										foreach ($spans as $span) {
											if ($span->hasAttributes()) {
												$span->removeAttribute("data-ssmchecks");
												if ($span->getAttribute("class") == "smwttcontent") {
													$child->removeChild($span);
												}
											}
										}
									}
								}
							}
							$ingredients[] = str_replace("Â", " ", $ingredientNode->nodeValue);
						}
						
						// Add recipe to db
						$recipeID = $recipeDAL->addRecipe($title, $pic, $portions, $instruction);
						// Add categories from recipe to db
						foreach($categories as $category) {
							$recipeDAL->addCategory($recipeID, $category);
						}
						foreach($ingredients as $ingredient) {
							$recipeDAL->addIngredient($recipeID, $ingredient);
						}
					}
				}
			}
		}
	} else {
		echo "Fel inträffade när recepten skulle läsas in från apiet";
	} 
}

if ($_GET["funct"] == "getRandomUserRecipe") {
	$id = $_GET["id"];
	$diet = $_GET["diet"];
	
	$okRecipes = array();
	$vegRecipes = array();
	if ($diet !== "all") {
		// Run through recipe categories
		if ($diet = "veg") { 	// Vegetarian
			$vegRecipeIDs = $recipeDAL->getRecipeCategories(5);
		} else { 				// Vegan
			$vegRecipeIDs = $recipeDAL->getRecipeCategories(6);
		}
		foreach($vegRecipeIDs as $vegID) {
			foreach ($savedRecipes as $rec) {
				if ($rec["recipeID"] == $vegID) {
					$vegRecipes[] = $rec;
				}
			}
		}
	}
	$dislikedIds = $recipeDAL->getUserDisliked($id);
	if ($diet == "all") {
		foreach($savedRecipes as $recipe) {
			$isBad = false;
			foreach ($dislikedIds as $disliked) {
				if ($recipe["recipeID"] == $disliked) {
					$isBad = true;
				}
			}
			if (!$isBad) {
				$okRecipes[] = $recipe;
			}
		}
	} else {
		foreach($vegRecipes as $recipe) {
			$isBad = false;
			foreach ($dislikedIds as $disliked) {
				if ($recipe["recipeID"] == $disliked) {
					$isBad = true;
				}
			}
			if (!$isBad) {
				$okRecipes[] = $recipe;
			}
		}
	}
	$randomIndex = array_rand($okRecipes);
	$randomRecipe = $okRecipes[$randomIndex];
	$randomRecipe["ingredients"] = $recipeDAL->getIngredients($randomRecipe["recipeID"]);
	
	
	$html = "<input type='hidden' id='recipeID' value='".$randomRecipe['recipeID']."' /><h3 id='title'>".$randomRecipe['title']."</h3>
				<p class='portions'>".$randomRecipe['portions']."</p>";
	if ($randomRecipe["pic"] != "-") {
		$html .= "<img id='image' src='".$randomRecipe['pic']."' />";
	}
	foreach($randomRecipe["ingredients"] as $ingredient) {
		$html .= "<p>".$ingredient."</p>";
	}
	$html .= "<br/><div class='instruction'>".$randomRecipe["instruction"]."</div>";
	$html = str_replace("[", "", $html);
	$html = str_replace("]", "", $html);
	$html = str_replace("â€“", "", $html);
	$html = iconv('UTF-8', "ISO-8859-1", $html); // Encodes åäö
	//$html = str_replace("�", " ", $html);
	// TODO: fix �-icons!!!
	echo $html;

} else if ($_GET["funct"] == "recipeUserBan") {
	$id = $_GET["id"];
	$recipeID = $_GET["recipeID"];
	$savedRecipe = "";
	foreach ($savedRecipes as $recipe) {
		if ($recipe["recipeID"] == $recipeID) {
			$savedRecipe = $recipe;
		}
	}
	if ($savedRecipe != "") {
		$recipeDAL->addComment($id, $savedRecipe["recipeID"], "dislike");
		echo $savedRecipe["title"];
	}
	
} else if ($_GET["funct"] == "recipeUserFavour") {
	$id = $_GET["id"];
	$recipeID = $_GET["recipeID"];
	$savedRecipe = "";
	foreach ($savedRecipes as $recipe) {
		if ($recipe["recipeID"] == $recipeID) {
			$savedRecipe = $recipe;
		}
	}
	if ($savedRecipe != "") {
		$recipeDAL->addComment($id, $savedRecipe["recipeID"], "like");
		echo $savedRecipe["title"];
	}
	
} else if ($_GET["funct"] == "recipeUserRemoveComment") {
	$id = $_GET["id"];
	$recipeID = $_GET["recipeID"];
	
	try {
		$recipeDAL->deleteComment($id, $recipeID);
		echo "Deleted";
	} catch (Exception $e){
		echo "Error occured: ".$e;
	}
	
	
	
} else if ($_GET["funct"] == "recipeGetBanned") {
	$id = $_GET["id"];
	$bannedIDs = $recipeDAL->getUserDisliked($id);
	if (count($bannedIDs) > 0) {
		$html = "<ul class='list-unstyled'>";
		foreach ($bannedIDs as $banned) {
			foreach ($savedRecipes as $recipe) {
				if ($recipe["recipeID"] == $banned) {
					$html .= "<li><a class='disban-link' href='?recipeID=".$recipe["recipeID"]."'>X</a> ".$recipe['title']."</li>";
				}
			}
		}
		$html .= "</ul>";
		echo $html; 
	} else {
		echo "Det finns inga ratade recept.";
	}
} else if ($_GET["funct"] == "recipeGetFavoured") {
	$id = $_GET["id"];
	$favouredIDs = $recipeDAL->getUserLiked($id);
	if (count($favouredIDs) > 0) {
		$html = "<ul class='list-unstyled'>";
		foreach ($favouredIDs as $favoured) {
			foreach ($savedRecipes as $recipe) {
				if ($recipe["recipeID"] == $favoured) {
					$html .= "<li><a class='disfavour-link' href='?recipeID=".$recipe["recipeID"]."'>X</a> ".$recipe['title']."</li>";
				}
			}
		}
		$html .= "</ul>";
		echo $html; 
	} else {
		echo "Det finns inga favoriserade recept.";
	}
}



function getPageFromURL($url) {
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FAILONERROR, true);

	$output = curl_exec($curl);
	curl_close($curl);

	return $output;
}
function getQuery($page, $query) {
	$dom = new DOMDocument('1.0', 'UTF-8');
	if ($dom->loadHTML($page)) { 
		$x = new DOMXPath($dom);
		return $x->query($query);
	}

	throw new Exception("Could not load HTML from page");
}

