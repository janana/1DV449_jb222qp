var name = "";
var diet = "";
var id = "";

$(document).ready(function() {
	// Add facebook buttons on each recipe SHARE
	// Check query in url on startup for what page to enter
	// Set custom query in url for each page
	// Because the page isn't reloaded during run, logging out of facebook from another page wont effect this app
	// Link to each recipe from favourite-recipe-page
	
	$("#profile-button").hide();
  	$("#random-button").hide();
  	getFrontPage();
  	
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '574244782654416',
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
	  	});
	  	
		FB.Event.subscribe('auth.authResponseChange', function(response) {
			if (response.status === 'connected') {
				runApplication();
			} else {
				getFrontPage();
		    }
		});
	 };
	// Load the SDK asynchronously
	(function(d){
 		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
 		if (d.getElementById(id)) {return;}
 		js = d.createElement('script'); js.id = id; js.async = true;
 		js.src = "//connect.facebook.net/en_US/all.js";
 		ref.parentNode.insertBefore(js, ref);
	}(document));
	
	$("#random-button").click(function(e) {
		generateRecipe();
		
		e.preventDefault();
	});
	$("#profile-button").click(function(e) {
		getProfilePage();
		e.preventDefault();
	});
	$("#brand").click(function(e) {
		getFrontPage();
		e.preventDefault();
	});
});

function runApplication() {
	FB.api('/me', function(response) {
		try {
			// Save user in db if it's the first login
			$.ajax({
				type: "GET",
				url: "user.php",
				data: { funct: "addUser", name: response.name, id: response.id }
			}).done(function(data) {
				var str = data.split(";");
				if (str[0] == "User found") {
					$("#profile-button").show();
					$("#random-button").show();
					
					diet = str[1];
					name = response.name;
					id = response.id;
					getFrontPage();
				} else if (data == "User saved") {	// New user, need to init diet to db
					$("#profile-button").show();
					$("#random-button").show();
					
					name = response.name;
					id = response.id;
					chooseDiet();
				} else {
					console.log(data); // Something is wrong
				}
			});
		} catch (exception) {
			alert("Ett oväntat fel inträffade."); // Add better error-handling
		}
	});
}
function generateRecipe() {
	$("#content").empty();
	$("#content").append("<div id='recipe-div' class='padding'><input type='button' class='btn btn-default' value='Favorisera recept' id='recipe-favour' /><input type='button' class='btn btn-default' id='recipe-remove' value='Rata recept' /></div>");
	$.ajax({
		type: "GET",
		url: "recipe.php",
		data: { funct: "getRandomUserRecipe", id: id, diet: diet }
	}).done(function(data) {
		$("#recipe-div").append(data);
	});
	
	$("#recipe-remove").click(function() {
		// Happens when recipe is removed from generator
		var recipeID = $("#recipeID").val();
		$.ajax({
			type: "GET",
			url: "recipe.php",
			data: { funct: "recipeUserBan", id: id, recipeID: recipeID }
		}).done(function(data) {
			if (data != "") {
				alert("Receptet '"+data+"' har ratats, och kommer inte visas igen. \n\nDu kan hantera ratade recept på din profil."); // TODO: Design/remove alerts
				generateRecipe();
			}
		});
	});
	$("#recipe-favour").click(function() {
		// Happens when recipe is favoured from generator
		var recipeID = $("#recipeID").val();
		$.ajax({
			type: "GET",
			url: "recipe.php",
			data: { funct: "recipeUserFavour", id: id, recipeID: recipeID }
		}).done(function(data) {
			if (data != "") {
				alert("Receptet '"+data+"' har favoriserats. \n\nDu kan hantera favoriserade recept på din profil."); // TODO: Design/remove alerts
			}
		});
	});
}
function chooseDiet() {
	$("#content").empty();
	var html = "<div id='veg-div' class='padding'><h3>";
	if (diet == "") {
		html += "Välkommen, "+name+"!</h3><p>För att få så passande recept slumpade som möjligt bör du välja din kost:</p><form id='veg-form'><div class='radio'><input type='radio' name='veg' value='all' checked id='all'><label for='all'>Allätare</label></div><div class='radio'><input type='radio' name='veg' value='veg' id='veg'><label for='veg'>Vegetarian</label></div><div class='radio'><input type='radio' name='veg' value='vegan' id='vegan'><label for='vegan'>Vegan</label></div><input type='submit' class='btn btn-default' id='veg-btn' value='Fortsätt' /></form></div>";
	} else {
		html += "Välj kost</h3><p>För att få så passande recept slumpade som möjligt bör du välja din kost:</p><form id='veg-form'>";
		if (diet == "all") {
			html += "<div class='radio'><input type='radio' name='veg' value='all' checked id='all'><label for='all'>Allätare</label></div><div class='radio'><input type='radio' name='veg' value='veg' id='veg'><label for='veg'>Vegetarian</label></div><div class='radio'><input type='radio' name='veg' value='vegan' id='vegan'><label for='vegan'>Vegan</label></div>";
		} else if (diet == "veg") {
			html += "<div class='radio'><input type='radio' name='veg' value='all' id='all'><label for='all'>Allätare</label></div><div class='radio'><input type='radio' name='veg' value='veg' checked id='veg'><label for='veg'>Vegetarian</label></div><div class='radio'><input type='radio' name='veg' value='vegan' id='vegan'><label for='vegan'>Vegan</label></div>";
		} else if (diet == "vegan") {
			html += "<div class='radio'><input type='radio' name='veg' value='all' id='all'><label for='all'>Allätare</label></div><div class='radio'><input type='radio' name='veg' value='veg' id='veg'><label for='veg'>Vegetarian</label></div><div class='radio'><input type='radio' name='veg' checked value='vegan' id='vegan'><label for='vegan'>Vegan</label></div>";
		}
		html += "<input type='submit' class='btn btn-default' id='veg-btn' value='Välj' /><input type='button' class='btn btn-default' id='veg-cancel' value='Avbryt' /></form><div>";
	}
	$("#content").append(html);
	if (diet != "") {
		$("#veg-cancel").click(function() {
			getProfilePage();
		});
	}
	$("#veg-form").submit(function(e) {
		diet = $("[name='veg']:checked").val(); // Get the selected value
		
		$.ajax({
			type: "GET",
			url: "user.php",
			data: { funct: "saveDiet", name: name, id: id, diet: diet }
		}).done(function(data) {
			if (data !== "Diet saved") {
				alert("Ett fel inträffade när kosten skulle sparas i databasen.");
			} else {
				getProfilePage();
			}
		});
		e.preventDefault();
	});
}
function getFrontPage() {
	$("#content").empty();
	$("#content").append("<div class='padding'><img src='http://säsongsmat.nu//w/images/thumb/a/a9/346.JPG/300px-346.JPG' id='fimage' /><h3>Välkommen till FoodGen!</h3><p>Vid inloggning med facebook kan du slumpa fram recept, favorisera, rata eller dela dem med dina vänner. Du kan även lista och hantera dina favoriserade eller ratade recept på din profil.</p><p>Sidan hämtar recept från <a href='http://säsongsmat.nu/' target='_blank'>säsongsmat.nu</a>, så det är dit du bör vända dig om du vill lägga till recept som du saknar här! Recepten som läses in är ur kategorierna: Varmrätter, Förrätter och smårätter, Soppor och Sallader.</p></div>");
}
function getProfilePage() {
  	console.log(id+" "+name+" "+diet);
	$("#content").empty();
	$("#content").append("<div id='profile-div' class='padding'><h3>Profil</h3><p>Här kan du ändra dina inställningar och visa listor på recept du favoriserat eller ratat</p><br/><p><a href='#' id='change-diet'>Ändra kostinställning</a></p><p><a id='manage-favoured' href='#'>Visa favoritrecept</a></p><p><a id='manage-removed' href='#'>Visa ratade recept</a></p><br><p><a id='logout-button' href='#'>Logga ut</a></p></div>");
	$("#change-diet").click(function(e) {
		chooseDiet();
		e.preventDefault();
	});
	$("#logout-button").click(function(e) {
		FB.logout(function() {
        	window.location = "../Projekt/";
   		});
		e.preventDefault();
	});
	var backLink = "<a id='back' href='#'>Tillbaka till profil</a>";
	$("#manage-favoured").click(function(e) {
		$.ajax({
			type: "GET",
			url: "recipe.php",
			data: { funct: "recipeGetFavoured", id: id }
		}).done(function(data) {
			$("#content").empty();
			$("#content").append("<div class='padding'><h3>Hantera favoritrecept</h3><p>Klicka på krysset vid receptet för att ta bort favorisering av receptet.</p>"+data+"<br>"+backLink+"</div>");
			$(".disfavour-link").click(function(e) {
				var href = $(this).attr("href");
				var recipeID = href.replace(/^\D+/g, "");
				if (recipeID != "") {
					$.ajax({
						type: "GET",
						url: "recipe.php",
						data: { funct: "recipeUserRemoveComment", id: id, recipeID: recipeID }
					}).done(function(data) {
						if (data == "Deleted") {
							alert("Borttagen!"); 
							getProfilePage();
						} else {
							console.log(data); // Something went wrong
						}
					});
				}
				e.preventDefault();
			});
			$("#back").click(function(e) {
				$("#content").empty();
				getProfilePage();
				e.preventDefault();			
			});
		});
		e.preventDefault();
	});
	$("#manage-removed").click(function(e) {
		$.ajax({
			type: "GET",
			url: "recipe.php",
			data: { funct: "recipeGetBanned", id: id }
		}).done(function(data) {
			$("#content").empty();
			$("#content").append("<div class='padding'><h3>Hantera ratade recept</h3><p>Klicka på krysset vid receptet för att häva ratandet av receptet.</p>"+data+"<br>"+backLink+"</div>");
			$(".disban-link").click(function(e) {
				var href = $(this).attr("href");
				var recipeID = href.replace(/^\D+/g, "");
				if (recipeID != "") {
					$.ajax({
						type: "GET",
						url: "recipe.php",
						data: { funct: "recipeUserRemoveComment", id: id, recipeID: recipeID }
					}).done(function(data) {
						if (data == "Deleted") {
							alert("Borttagen!"); 
							getProfilePage();
						} else {
							console.log(data); // Something went wrong
						}
					});
				}
				e.preventDefault();
			});
			$("#back").click(function(e) {
				$("#content").empty();
				getProfilePage();
				e.preventDefault();			
			});
		});
		e.preventDefault();
	});
}
