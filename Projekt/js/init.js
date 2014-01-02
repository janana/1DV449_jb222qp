$(document).ready(function() {
	// Add description on index-page startup
	// Load recepies from säsongsmat api
	// Add facebook buttons on each recipe
	// Save recepies in custom db for caching purposes
	
	// Custom db linked to user-db for information regarding recepies
	// Add functionality for liking/disliking recipes
	// Disclude the disliked recipes from recipe-generation
	// On profile - users can display list of liked recipes
	
	// If I get time: Add search function to recipes
	//				  User can manage their liked/disliked recipes on their profile page	  
	
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '574244782654416',
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
	  	});
	
		// Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
		// for any authentication related change, such as login, logout or session refresh. This means that
		// whenever someone who was previously logged out tries to log in again, the correct case below 
		// will be handled. 
		FB.Event.subscribe('auth.authResponseChange', function(response) {
			if (response.status === 'connected') { // Logged in
				runApplication();
				
				// Save user in db
				// Do application
				// Is user gonna be able to logout???? - logout affects all facebook-windows open
			} else if (response.status === 'not_authorized') { // Logged in but not accepted the app
				// In real-life usage, you wouldn't want to immediately prompt someone to login 
				// like this, for two reasons:
				// (1) JavaScript created popup windows are blocked by most browsers unless they 
				// result from direct interaction from people using the app (such as a mouse click)
				// (2) it is a bad experience to be continually prompted to login upon page load.
				FB.login();
			} else { // Not logged in
				FB.login();
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
	
	function runApplication() {
		FB.api('/me', function(response) {
			// User is logged in to facebook and should be set to profile page
			try {
				// Save user in db if it's the first login
				$.ajax({
					type: "GET",
					url: "user.php",
					data: { name: response.name, id: response.id  }
				}).done(function(data) {
					console.log(data);
				});
				
				// Add button to generate random recipe
			} catch {
				alert("Ett oväntat fel inträffade."); // Add better error-handling
			}
		});
	}
	
});
