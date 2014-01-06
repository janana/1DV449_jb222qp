<?php

require_once("db/RecipeDAL.php");

error_reporting(E_ALL);

// Handle recipes in db

$recipeDAL = new RecipeDAL();
$savedRecipes = $recipeDAL->getRecipes();