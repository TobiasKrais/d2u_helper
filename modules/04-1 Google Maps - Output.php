<?php
/**
 * Version 1.0
 */
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
?>
<div class="col-xs-12 col-sm-<?php echo $cols; ?>">
<?php
	$longitude = "REX_VALUE[4]" == "" ? 0 : "REX_VALUE[4]";
	$latitude = "REX_VALUE[5]" == "" ? 0 : "REX_VALUE[5]";
	$maptype = "REX_VALUE[6]" == "" ? "HYBRID" : "REX_VALUE[6]";
	$zoom = "REX_VALUE[3]" == "" ? 15 : "REX_VALUE[3]";
	$height = "REX_VALUE[7]" == "" ? "500" : "REX_VALUE[7]";
	$height_unit = "REX_VALUE[8]" == "" ? "px" : "REX_VALUE[8]";
	$height .= $height_unit;
	$api_key = "REX_VALUE[11]";
	if($api_key != "") {
		$api_key = "?key=". $api_key;
	}
	$substitute = array("\r\n" => "", "\r" => "", "\n" => "", '"' => "'");
	$infotext = "REX_VALUE[id=2 output=html]";
	$infotext = strtr($infotext, $substitute);
?>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js<?php echo $api_key; ?>"></script> 
	<div id="map_canvas" style="display: block; width: 100%; height: <?php echo $height; ?>"></div> 
	<script type="text/javascript"> 
		function createGeocodeMap() {
			var geocoder = new google.maps.Geocoder();
			var map; 
			var address = "<?php print 'REX_VALUE[1]'; ?>";
			if (geocoder) {
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						var marker = new google.maps.Marker({
							map: map,
							position: results[0].geometry.location
						});
						var infowindow = new google.maps.InfoWindow({
							content: "<?php print $infotext; ?>",
	//						size: new google.maps.Size(50,50)
							position: results[0].geometry.location
						});
						infowindow.open(map, marker);
						google.maps.event.addListener(marker, 'click', function() {
							infowindow.open(map,marker);
						});
					} else {
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
			}

			var myOptions = {
				zoom: <?php echo $zoom ?>,
				mapTypeId: google.maps.MapTypeId.<?php echo $maptype ?>
			}
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		}

		function createDegreeMap() {
			var myLatlng = new google.maps.LatLng(<?php echo $latitude .", ".$longitude; ?>);
			var myOptions = {
				zoom: <?php echo $zoom ?>,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.<?php echo $maptype ?>
			}
			var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

			var marker = new google.maps.Marker({
				position: myLatlng, 
				map: map
			});

			var infowindow = new google.maps.InfoWindow({
				content: "<?php print $infotext; ?>",
				position: myLatlng
			});
			infowindow.open(map, marker);
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
			});
		}

		// Beim direkten Kartenaufruf initialisieren
		<?php
			// Wenn Laengen- und Breitengrade vorhanden sind: Karte erstellen
			if($longitude != 0 && $latitude != 0) {
				print "createDegreeMap();";
			}
			// Falls auf Geocoding zurueck gegriffen werden muss
			else {
				print "createGeocodeMap();";
			}
		?>
	</script>
</div>
