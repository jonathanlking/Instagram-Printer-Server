function venuesForLocation(longitude, latitude, name) {
	$.getJSON("http://instagram.jonathanlking.com/tools/location/foursquare.php", {
		lat: latitude,
		lng: longitude
	}).done(function(data) {
		var venues = [];
		for (var i = 0; i < data.response.venues.length; i++) {
			var venue = {
				'name': data.response.venues[i].name,
				'id': data.response.venues[i].id
			};
			venues.push(venue);
		}
		updateSearchbar(name);
		printVenues(venues);
	});
	//Also do error checking to make sure a. Data was received b. The API call worked
}

function geocodeAddress(address) {
	$.get("http://maps.google.com/maps/api/geocode/json", {
		'address': address,
		sensor: "false"
	}).done(function(data) {
		var addresses = [];
		for (var i = 0; i < data.results.length; i++) {
			var address = {
				'latitude': data.results[i].geometry.location.lat,
				'longitude': data.results[i].geometry.location.lng,
				'name': data.results[i].formatted_address
			};
			addresses.push(address);
		}
		printGeocodedAddress(addresses);
	});
	//Also do error checking to make sure a. Data was received b. The API call worked
}

function instagramVenueFromFoursquareVenueId(id) {
	$.getJSON("http://instagram.jonathanlking.com/tools/location/instagram.php", {
		'id': id
	}).done(function(data) {
		var venue = {
			'id': data.data[0].id,
			'name': data.data[0].name,
			'longitude': data.data[0].longitude,
			'latitude': data.data[0].latitude
		};
		appendSearchbarText(data.data[0].name);
		printInstagramVenue(venue);
	});
	//Also do error checking to make sure a. Data was received b. The API call worked
}

function printGeocodedAddress(data) {
	clearList();
	if (data.length === 0) {
		var empty = $('<p class="result empty">No results</p>');
		empty.appendTo('#content');
	} else {
		var results = $('<ul id="results"></ul>');
		results.appendTo('#content');
		for (var i = 0; i < data.length; i++) {
			var address = data[i];
			var item = $('<li class="result location click"></li>');
			item.prepend(address.name);
			item.attr({
				'data-latitude': address.latitude,
				'data-longitude': address.longitude,
				'data-location': address.name
			});
			item.appendTo('#results');
		}
		$(".location").click(function() {
			var latitude = $(this).attr("data-latitude");
			var longitude = $(this).attr("data-longitude");
			var name = $(this).attr("data-location");
			venuesForLocation(longitude, latitude, name);
		});
	}
}

function printVenues(data) {
	clearList();
	if (data.length === 0) {
		var empty = $('<p class="result empty">No results</p>');
		empty.appendTo('#content');
	} else {
		var results = $('<ul id="results"></ul>');
		results.appendTo('#content');
		for (var i = 0; i < data.length; i++) {
			var venue = data[i];
			var item = $('<li class="result venue click"></li>');
			item.prepend(venue.name);
			item.attr({
				'data-name': venue.name,
				'data-id': venue.id
			});
			item.appendTo('#results');
		}
		$(".venue").click(function() {
			var id = $(this).attr("data-id");
			instagramVenueFromFoursquareVenueId(id);
		});
	}
}

function printInstagramVenue(data) {
	clearList();
	console.log(data);
	var item = $('<p class="result"></p>');
	item.prepend('Name: ' + data.name + "</br>" + 'Instagram Location ID: ' + data.id + "</br>" + 'Longitude: ' + data.longitude + "</br>" + 'Latitude: ' + data.latitude);
	item.attr({
		'data-name': data.name,
		'data-id': data.id,
		'data-latitude': data.latitude,
		'data-longitude': data.longitude
	});
	item.appendTo('#content');
}

function clearList() {
	$('#content').empty();
}

function updateSearchbar(text) {
	$('#form input').val(text);
}

function appendSearchbarText(text) {
	var existing = $('#form input').val();
	var formattedLength = 25;
	if (existing.length >= formattedLength) {
		var truncatedText = existing.substring(0, formattedLength - 3);
		existing = truncatedText + '...';
	}
	updateSearchbar(existing + ' - ' + text);
}

function showOverlay() {
	$.blockUI({
		message: '<h1>Loading...</h1>',
		overlayCSS: {
			backgroundColor: 'white',
			opacity: 1.0,
			cursor: 'default'
		},
		css: {
			border: 'none',
			'padding': 'none',
			'font-size': '12px',
			'font-family': 'Helvetica, sans-serif',
			position: 'fixed',
			top: 0,
			left: 0,
			width: '100%',
			backgroundColor: '#009ce8',
			opacity: 0.8,
			color: 'white',
			cursor: 'default'
		}
	});
}
$(document).ajaxStart(function() {
	showOverlay();
});
$(document).ajaxComplete(function(event, request, settings) {
	$.unblockUI();
});
$("#form").submit(function() {
	var data = $(this).serializeArray();
	geocodeAddress(data[0].value);
	event.preventDefault();
});
$(document).ready(function() {
	
	$('#form input').focus();
});
