<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
?>
<div class="col-sm-<?php echo $cols . $offset_lg; ?>">
<?php
	$longitude = "REX_VALUE[4]" == "" ? 0 : "REX_VALUE[4]";
	$latitude = "REX_VALUE[5]" == "" ? 0 : "REX_VALUE[5]";
	$map_type = "REX_VALUE[6]" == "" ? "HYBRID" : "REX_VALUE[6]";
	$maps_zoom = "REX_VALUE[3]" == "" ? 15 : "REX_VALUE[3]";
	$height = "REX_VALUE[7]" == "" ? "500" : "REX_VALUE[7]";
	$height_unit = "REX_VALUE[8]" == "" ? "px" : "REX_VALUE[8]";
	$height .= $height_unit;
	$api_key = "REX_VALUE[11]" == "" ? rex_config::get('d2u_helper', 'maps_key') : "REX_VALUE[11]";
	if($api_key != "") {
		$api_key = "?key=". $api_key;
	}
	$substitute = ["\r\n" => "", "\r" => "", "\n" => "", '"' => "'"];
	$infotext = "REX_VALUE[id=2 output=html]";
	$infotext = strtr($infotext, $substitute);
	
	$map_id = rand();
?>
	<div id="map_canvas" style="display: block; width: 100%; height: <?php echo $height; ?>">
		<div id="maps-gdpr-hint-<?= $map_id; ?>" class="maps-gdpr-hint">
			<p><?php echo \Sprog\Wildcard::get('d2u_helper_module_04_gdpr_hint'); ?></p>
			<button type="button" id="map-<?= $map_id; ?>" ><?php echo \Sprog\Wildcard::get('d2u_helper_module_04_load_map'); ?></button>
		</div>
	</div>
	<script>
		// Add event listener to activate map
		document.getElementById('map-<?= $map_id; ?>').addEventListener('click', function() {
			// Load Google Maps JS and go on
			$.getScript("https://maps.googleapis.com/maps/api/js<?php echo $api_key; ?>", function(){
				// Remove hint
				g = document.getElementById('maps-gdpr-hint-<?= $map_id; ?>');
				g.outerHTML = '';

				// Init
				infowindow = new google.maps.InfoWindow();
				initialize();

			 });
		});

		var map;
		var infowindow;
		var address = [<?php
			print "[";
			// Address for Geocoder
			print '"REX_VALUE[1]", ';
			// Infotext
			print '"'. $infotext .'", ';
			// Latitude and Longitude
			print $latitude .', '. $longitude;
			print "]";
		?>];
		var address_position = 0;
		var timeout = 100;

		/**
		 * Initialize map
		 */
		function initialize() {
			// Default center of map
			var latlng = new google.maps.LatLng(47.6242,7.67378);
			// Map settings
			var myOptions = {
				zoom: <?php print $maps_zoom; ?>,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.<?php echo $map_type; ?>
			};
			// create map
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

			// Address position
			calcPosition(address_position);
		}

		/**
		 * Calculate address map position
		 * @param {int} position address array position
		 */
		function calcPosition(position) {
			if(address[position][2] > 0 && address[position][3] > 0) {
				// Use given latitude and longitude
				addMarker(new google.maps.LatLng(address[position][2], address[position][3]), position);
			}
			else {
				// Geocode
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({'address': address[position][0]}, function(results, status) {
					if (status === google.maps.GeocoderStatus.OK) {
						addMarker(results[0].geometry.location, position);
					}
					else {
						if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
							// Too many queries, just wait a while and then retry geocoding again
							setTimeout(function() { calcPosition(position); }, (timeout * 3));
						}
					}
				});
			}

			// Go to next address
			address_position++;
			if (address_position < address.length) {
				setTimeout(function() { calcPosition(address_position); }, (timeout));
			}
		}

		/**
		 * Add marker with infowindow on map
		 * @param {google.maps.LatLng} location
		 * @param {int} position address array position
		 */
		function addMarker(location, position) {
			// Create marker
			var marker = new google.maps.Marker({
				position: location,
				map: map
			});
			// Show info window
			infowindow.setContent('<div id="infoWindow" style="white-space: nowrap;">' + address[position][1] + '</div>');
			infowindow.open(map, marker);
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map, marker);
			});
			// Set map center on address
			map.setCenter(location);
		}
	</script>
</div>