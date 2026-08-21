//Initialise Important Maps Vars
var map, mapCanvas, mapOptions, infowindow, route_window, offer_window, feature_window, direction_window, marker, input, searchBox, autocomplete, panel, bounds, directionsDisplay, directionsService, my_loc, markerCluster, heatmap;
//Set Group Station Marker Variables
var all_markers = [], markers_custom = [], markers_caltex = [];
//Set Single Station Marker Variables
var marker_custom, marker_caltex;
var cluster_caltex;
var markers = [];
var route_markers = [];
var route_marker;
var routeMark;
var route_toggle = 0;
/*
 * Initialise Google Maps Functionality
 */
function initialise() {

	//Build Google Components
	initiate_mapObjects(); //Instantiate Primary Map Objects
	initiate_geoLocationHandler(); //Request User's Location
	initiate_searchBox(); //Instantiate Search Box Functionality
	initiate_trafficLayers();
	initiate_directions();

	var mcOptions = {
		gridSize : 100,
		maxZoom : 11,
		imagePath : 'images/cluster/cluster',
		imageSizes : [20, 30, 40, 45, 50],
		clusterClass : 'caltexCluster',
		title : 'Click to Zoom in on this area'
	};

	cluster_caltex = new MarkerClusterer( map, null, mcOptions );
	//Build Stations
	generate_markers();
}

function callback( results, status ) {
	if (status == google.maps.places.PlacesServiceStatus.OK) {
		for ( var i = 0; i < results.length; i++) {
			search_createMarker(results[i]);
		}
	}
}

function add_new_marker(i, loc, marker_type, marker_icon, map) {
	var locale = new google.maps.LatLng(Number(loc.latitude),
		Number(loc.longitude)
	);

	var loc_brand = loc['address'];

	//New Marker
	var marker = new google.maps.Marker({
		position : locale,
		map : map,
		title : loc_brand,
		icon : marker_icon,
		zIndex : 1
	});

	if ( marker_type == 'custom' ) {
		markers_custom.push(marker);
	} else if ( marker_type == 'caltex' ) {
		markers_caltex.push(marker);
	}


	//console.log(marker.getPlace());
	google.maps.event.addListener(marker, 'click', function() {
		buildStationInfoWindow(marker, loc, locale, infowindow);
	});
}

function build_route_window(route_marker, text) {
	route_window.close();
	google.maps.event.addListener(route_marker, 'click', function() {
		if (route_toggle == 1) {
			route_window.close();
			route_toggle = 0;
		} else {
			route_window.setContent('<div class="route_window">' + text
					+ '</div>');
			route_window.open(map, route_marker);
			route_toggle = 1;
		}
	});
}

function buildStationInfoWindow(marker, loc, locale, infowindow) {

	var windowContent = generateWindowContent(loc, f_icon);
	var window_div, content_feature, content_offers, content_directions;
	panel = document.getElementById('window_directions');

	infowindow.close();
	feature_window.close();
	offer_window.close();
	direction_window.close();

	map.panTo(locale);
	infowindow.setContent(windowContent);
	infowindow.open(map, marker);
	google.maps.event.clearListeners(infowindow, 'domready');
	google.maps.event.addListener(infowindow, 'domready', function() {
		document.getElementById('locDirections').addEventListener('click', function(){
			generate_directions(marker, loc, locale, infowindow)
		});

		document.getElementById('clear_route').addEventListener('click', function() {
			document.getElementById('canvas').style.width = '100%';
			panel.style.display = "none";
			directionsDisplay.setMap(null);
			map.panTo(my_loc);
			map.setZoom(14);
			//Clear Route Markers
			clear_markers(route_markers);
			route_window.close();
		});

		document.getElementById('locOffers').addEventListener('click', function() {
			var offer_content = generate_offerWindow(loc);
			offer_window.setContent(offer_content);
			offer_window.setOptions({
				'pixelOffset' : new google.maps.Size(-500, 200)
			});
			offer_window.open(map, marker);
		});

		document.getElementById('locFeatures').addEventListener('click', function() {
			var feature_content = generate_featureWindow(loc);
			feature_window.setContent(feature_content);
			feature_window.setOptions({
				'pixelOffset' : new google.maps.Size(500, 200)
			});
			feature_window.open(map, marker);
		});
	});
}

//Remove all markers in the passed array
function clear_markers(markers) {
	thisMarker = null;
	for ( var i = 0; i < markers.length, thisMarker = markers[i]; i++) {
		thisMarker.setMap( null );
	}
}

function generate_directions(marker, loc, locale, infowindow) {
	//Directions Service
	var route_request = {
		'origin' : my_loc,
		'destination' : locale,
		'travelMode' : 'DRIVING'
	};

	directionsService.route(route_request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(result);

			generate_route_markers(result);

			panel.style.display = "block";
			document.getElementById('canvas').style.width = '80%';
			infowindow.close();
			offer_window.close();
			feature_window.close();
		}
	});
}

function generate_route_markers(result) {
	google.maps.event.clearInstanceListeners(directionsDisplay);

	var routes = result.routes;

	for ( var ri = 0, route = routes[ri]; ri < routes.length; ri++ ) {

		var leg = route.legs[0];
		var step;
		for ( var ii = 0; ii < leg.steps.length; ii++ ) {
			step = leg.steps[ii];
		/*
		 	route_marker = new google.maps.Marker({
		 		map: map,
				position: step.start_point,
				draggable: true
			});
		*/
			//route_markers.push( route_marker );
			//build_route_window( route_marker, step.instructions );
		}

		google.maps.event.addListener(directionsDisplay, 'directions_changed',
				function( result, status ) {
					var newRoute = directionsDisplay.getDirections();
					redraw_route_markers( newRoute );
				});

		directionsDisplay.setMap( map );
		directionsDisplay.setPanel( panel );
		directionsDisplay.setOptions({
			draggable : true
		});
	}
}

function redraw_route_markers( newRoute ) {
	var route = newRoute.routes[0];
	var leg = route.legs[0];
	var step;

	clear_markers(route_markers);

	for ( var ii = 0; ii < leg.steps.length; ii++ ) {
		step = leg.steps[ii];

		/*route_marker = new google.maps.Marker({
			map: map,
			position: step.start_point,
			draggable: true
		});*/

		route_markers.push(route_marker);

		build_route_window(route_marker, step.instructions);
	}
	directionsDisplay.setMap(map, route_marker);
}

function getLocPrices(loc) {
	var carwash = loc['Carwash'];
	if ( carwash !== 'Yes' ) {
		carwash = 'No';
	}
	var prices_html = '<div>' + carwash + '</div>';
	return prices_html;
}

function getLocFeatures( loc ) {
	var feature_html = '<div>' + loc['Truckstop'] + '</div>';
	return feature_html;
}

function getLocFuelTypes( loc ) {
	var fuel_html = '';
	var aFuelTypes = [
       	[ 'E10', 'a' ], [ 'ULP', 'b' ], [ 'PULP', 'c' ], [ 'VX95', 'd' ],
       	[ 'VX98', 'e' ], [ 'LPG', 'f' ], [ 'Diesel', 'g' ],
       	[ 'Vortex Diesel', 'h' ], [ 'Biodiesel', 'i' ], [ 'Eftpos', 'j' ]
	];

	aFuelTypes.forEach( function( entry ) {
		if ( loc[entry[0]] == 1 ) {
			fuel_html += '<li class="loc fuel_type ' + entry[1] + '"></li>';
		} else {
			fuel_html += '<li class="loc fuel_type ' + entry[1] + ' gs"></li>';
		}
	});

	return fuel_html;
}

function generateWindowContent( loc, f_icon ) {
	if ( ! loc ) {
		return false;
	}

	var feature = getLocFeatures( loc );
	var prices = getLocPrices( loc );
	var fuels = getLocFuelTypes( loc );

	var sContent = '<div class="station" id="info-window">'
		+ '<h2 class="info title">'
		+ loc['brand']
		+ '</h2>'
		+ '<p class="info address">'
			+ loc['address']
		+ '</p>'
		+ '<div class="loc fuel_types">'
			+ fuels
		+ '</div>'
		+ '<input type="button" class="btn info-window middle" id="locDirections" value="Directions" />'
		+ '<input type="button" class="btn info-window left" id="locOffers" value="Offers" />'
		+ '<input type="button" class="btn info-window right" id="locFeatures" value="Features" />'
	+ '</div>';

	return sContent;
}

function handleNoGeolocation( errorFlag ) {
	if ( errorFlag ) {
		var content = 'Error: The Geolocation service failed.';
	} else {
		var content = 'Error: Your browser doesn\'t support geolocation.';
	}

	var options = {
		map : map,
		position : new google.maps.LatLng(60, 105),
		content : content
	};

	var infowindow = new google.maps.InfoWindow(options);
	map.setCenter(options.position);
}

function search_changeBounds() {
	bounds = map.getBounds();
	searchBox.setBounds(bounds);
}

function search_changePlace() {
	infowindow.close();

	var place = autocomplete.getPlace();
	if ( ! place.geometry ) {
		return false;
	}

	// If the place has a geometry, then present it on a map.
	if ( place.geometry.viewport ) {
		map.fitBounds( place.geometry.viewport );
	} else {
		map.setCenter( place.geometry.location );
		map.setZoom( 14 );
	}
	var image = {
		url : place.icon,
		scaledSize : new google.maps.Size(40, 40)
	};
	marker.setIcon(image);
	marker.setPosition(place.geometry.location);

	var address = '';
	if (place.address_components) {
		address = [
				(place.address_components[0]
						&& place.address_components[0].short_name || ''),
				(place.address_components[1]
						&& place.address_components[1].short_name || ''),
				(place.address_components[2]
						&& place.address_components[2].short_name || '') ]
				.join(' ');
	}
	infowindow.setContent('<div><strong>' + place.name + '</strong><br>'
			+ address);
	infowindow.open(map, marker);
}

function search_createMarker(place) {
	var placeLoc = place.geometry.location;

	google.maps.event.addListener(search_marker, 'click', function() {
		infowindow.setContent(place.name);
		infowindow.open(map, this);
	});
}

function generate_featureWindow(loc) {
	//Add Close Button
	var window_html = '<div id="window_features">';//'<input type="button" class="btn-close" id="window-close" value="X" />';
	//Add Heading
	window_html += '<h2>Features</h2>';
	//Components
	window_html += generate_featureComponents(loc);

	window_html += '</div>';

	return window_html;
}
function generate_offerWindow(loc) {
	//Add Close Button
	var window_html = '<div id="window_offers">';//'<input type="button" class="btn-close" id="window-close" value="X" />';
	//Add Heading
	window_html += '<h2>Offers</h2>';
	//Components
	window_html += '<div class="components">';
	window_html += 'If your a fellow Fuel Chaser, take a look at our special offers!';
	window_html += '</div>';
	window_html += '</div>';

	return window_html;
}

function generate_featureComponents(loc) {
	var component_html = '<div class="components">';
	if (loc['OpenAllHours'] == 1) {
		component_html += '<p><strong>Opening 24-7</strong></p>';
	}
	if (loc['Carwash'] == 'Yes') {
		component_html += '<p><strong>Carwash</strong></p>';
	}
	if (loc['Disabled_Toilet'] == 1) {
		component_html += '<p><strong>Disabled Toilet</strong></p>';
	}
	if (loc['ATM'] == 1) {
		component_html += '<p><strong>ATM</strong></p>';
	}
	if (loc['BBQ_Gas'] == 1) {
		component_html += '<p><strong>BBQ Gas</strong></p>';
	}
	if (loc['Truckstop'] == 'Yes') {
		component_html += '<p><strong>Truckstop</strong></p>';
	}
	if (loc['Starcash'] == 'True') {
		component_html += '<p><strong>Starcash</strong></p>';
	}
	if (loc['E_FLEX'] == 1) {
		component_html += '<p><strong>E-FLEX</strong></p>';
	}
	component_html += '</div>';

	return component_html;

}

/*
 * Initiate Map and Primary Map Components
 * Builds a new object for Map, InfoWindow and Marker
 */
function initiate_mapObjects() {
	//Set Map Options
	var latlng = new google.maps.LatLng(-37.81361110, 144.96305559);
	var mapCanvas = document.getElementById('map_canvas');
	var mapOptions = {
		center : latlng,
		zoom : 8,
		mapTypeId : google.maps.MapTypeId.ROADMAP
	}

	//Map Objects
	map = new google.maps.Map(mapCanvas, mapOptions);
	//Info Windows
	infowindow = new google.maps.InfoWindow();
	route_window = new google.maps.InfoWindow();
	offer_window = new google.maps.InfoWindow();
	feature_window = new google.maps.InfoWindow();
	direction_window = new google.maps.InfoWindow();

	google.maps.event.addListener(infowindow, 'closeclick', function() {
		infowindow.close();
		route_window.close();
		feature_window.close();
		offer_window.close();
		direction_window.close();
	});

	route_marker = new google.maps.Marker({
		map : map
	});

	marker = new google.maps.Marker({
		map : map
	});
}

/*
 * Run Geo Location handler.
 * Attempts Find Current Location, with user's permission.
 */
function initiate_geoLocationHandler() {

	//Load Array of locations
	//My Location Coords =
	// Lat:  -37.8483614
	// test  -37.846602
	//test2  -37.845427
	// Long: 145.07859890000003
	// test  145.062921
	// test2 144.9854

	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = new google.maps.LatLng(position.coords.latitude,
				position.coords.longitude);
				map.setCenter(pos);
				my_loc = pos;
		}, function() {
			handleNoGeolocation(true);
		});
	} else {
		// Browser doesn't support Geolocation
		handleNoGeolocation(false);
	}
}

function initiate_searchBox() {

	/* SEARCH BOX */
	// Create the search box and link it to the UI element.
	input = (document.getElementById('pac-input'));

	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	//SearchBox
	searchBox = new google.maps.places.SearchBox(input);
	//Auto-Complete
	autocomplete = new google.maps.places.Autocomplete(input, {
		'componentRestrictions' : {
			'country' : 'au'
		}
	});
	autocomplete.bindTo('bounds', map);

	// Bias the SearchBox results towards places that are within the bounds of the
	// current map's viewport.
	google.maps.event.addListener(map, 'bounds_changed', search_changeBounds);

	google.maps.event.addListener(autocomplete, 'place_changed', search_changePlace);
}

function initiate_trafficLayers() {

	//Traffic Layer
	/*var trafficLayer = new google.maps.TrafficLayer();
	  trafficLayer.setMap(map);*/

	//Transit Layer (Public Transport)
	/*var transitLayer = new google.maps.TransitLayer();
	transitLayer.setMap(map);*/

	/* var request = {
	    location: latlng,
	    radius: 50000,
	    types: ['gas_station']
	}; */

	//var service = new google.maps.places.PlacesService( map );
	//service.nearbySearch( request, callback );

}

function initiate_directions() {
	//Directions Services
	directionsDisplay = new google.maps.DirectionsRenderer({
		draggable : true,
		hideRouteList: true
	});

	directionsService = new google.maps.DirectionsService();
}

function initiate_locations_custom() {
	//Create Marker for Each caltex in array
	markers_custom = [];
	for ( var i = 0; i < custom.length; i++ ) {
		add_new_marker( i, custom[i], 'custom', f_icon.custom, map );
	}
	cluster_caltex.addMarkers(markers_custom);
}

function initiate_locations_caltex() {
	//Create Marker for Each caltex in array
	markers_caltex = [];
	for ( var i = 0; i < caltex.length; i++ ) {
		add_new_marker( i, caltex[i], 'caltex', f_icon.caltex, map );
	}
	cluster_caltex.addMarkers(markers_caltex);
}

function generate_markers() {
	cluster_caltex.clearMarkers();

	if (  document.getElementById('filter_cbox_custom').checked == true ) {
		initiate_locations_custom(); //Generate Markers for Default Locations
	} else {
		cluster_caltex.removeMarkers(markers_custom);
		clear_markers(markers_custom);
	}


	if ( document.getElementById('filter_cbox_caltex').checked == true ) {
		initiate_locations_caltex(); //Generate Markers for Caltex Locations
	} else {
		cluster_caltex.removeMarkers(markers_caltex);
		clear_markers(markers_caltex);
	}
	map.setTilt(45);

/*	if ( document.getElementById('filter_cbox_mobile').checked == true ) {
		initiate_locations_mobile(); //Generate Markers for Caltex Locations
	} else {
		cluster_caltex.clearMarkers();
		clear_markers(markers_mobile);
	}*/

}