$( document ).ready( 
	function() {
		$("#logout").bind( "click", function() {
		  	$.ajax({
		  		type: "GET",
		  		url: "functions.php",
		  		data: {function: "logout"}
		  	}).done(function(data) {
		  		window.location = data;
		  	});
	 	});
	}
)

$( document ).ready( 
			
	function() {
		
		$('#mess_container').hide();
		
		$("#add_btn").bind( "click", function() {
		  	
			var name_val = $('#name_txt').val();
			var message_val = $('#message_ta').val();
			var pid =  $('#mess_inputs').val();
			// make ajax call to logout
			$.ajax({
				type: "GET",
			  	url: "functions.php",
			  	data: {function: "add", name: name_val, message: message_val, pid: pid}
			}).done(function(data) {
				var output = data.split("<>");
				if (output[0] != "" && output[1] != "") {
					$( "#mess_p_mess" ).prepend( "<p class='message_container'>" + output[0] +"<br />Skrivet av: " + output[1] +"</p>");
				}
				
			});
		  
	  });
	}
)

// Called when we click on a producer link - gets the id for the producer 
function changeProducer(pid) {

//console.log("pid --> " +pid);
				
// Clear and update the hidden stuff
$( "#mess_inputs").val(pid);
$( "#mess_p_mess").text("");

// get all the stuff for the producers
// make ajax call to functions.php with teh data
$.ajax({
	type: "GET",
  	url: "functions.php",
  	data: {function: "producers", pid: pid}
}).done(function(data) { // called when the AJAX call is ready
	console.log(data);
	var j = JSON.parse(data);
	
	$("#mess_p_headline").text("Meddelande till " +j.name +", " +j.city);
	
	
	if(j.url !== "") {
		
		$("#mess_p_kontakt").text("Länk till deras hemsida " +j.url);
	}
	else {
		$("#mess_p_kontakt").text("Producenten har ingen webbsida");
	}
	
	if(j.imageURL !== "") {
		$("#p_img_link").attr("href", j.imageURL); 
		$("#p_img").attr("src", j.imageURL); 
	}
	else {
		$("#p_img_link").attr("href", "#"); 
		$("#p_img").attr("src", "img/noimg.jpg"); 
	}
});

// Get all the messages for the producers through functions.php
$.ajax({
	type: "GET",
  	url: "functions.php",
  	data: {function: "getIdsOfMessages", pid: pid}
	
}).done(function(data) {
	
	// all the id:s for the messages for this producer
	var ids = JSON.parse(data);
	//console.log(ids);
	
	// Loop through all the ids and make calls for the messages
	if(ids !== false){
	 ids.forEach(function(entry) {
		// problems with the messages not coming in the right order :/
		$.ajax({
			type: "GET",
		  	url: "functions.php",
		  	data: {function: "getMessage", serial: entry.serial},
			timeout: 2000
		}).done(function(data) {
			var j = JSON.parse(data);
		//	console.log(j);
			$( "#mess_p_mess" ).prepend( "<p class='message_container'>" +j.message +"<br />Skrivet av: " +j.name +"</p>");
	
		});
	});
	}
	
});

// show the div if its unvisible
$("#mess_container").show("slow");
	
}