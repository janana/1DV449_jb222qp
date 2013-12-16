$(document).ready(function () {
	// Inits map
	var mapOptions = {
		zoom: 6,
		center: new google.maps.LatLng(59.681078, 14.464188)
	};
	
	var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	
	// Inits traffic info
	$.getJSON("ajax.php", function(data) {
		var items = [];
		$.each(data.messages, function(index, value) {
			items.push(value);
			var info = "<h4>"+value.title+"</h4><p class='traffic-description'>"+value.description+"</p><p class='traffic-category'>"+value.subcategory+"</p><p class='traffic-date'>"+value.createddate+"</p>";
			$("#traffic").append("<div class='traffic-details'>"+info+"</div>");
			// Add marker to map and info-box when pushed.
			
			// TODO: Fix date format
			// TODO: Fix search by categories
			// TODO: Design
			
			var infowindow = new google.maps.InfoWindow({
	        	content: "<div>"+info+"</div>"
			});
			
			var pos = new google.maps.LatLng(value.latitude, value.longitude);
			var marker = new google.maps.Marker({
				position: pos,
				map: map,
				title: value.title
			});
			
			// Connects the infowindow to the marker and map and displays it on click
			google.maps.event.addListener(marker, 'click', function() {
				
				infowindow.open(map, marker);
			});
		});
	}).fail(function(jqxhr, textStatus, error) {
		alert(error);
		// Add errorhandling
	});
});