<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
?>
<div class="col-sm-<?php echo $cols; ?>">
<?php
	$longitude = "REX_VALUE[4]" == "" ? 0 : "REX_VALUE[4]";
	$latitude = "REX_VALUE[5]" == "" ? 0 : "REX_VALUE[5]";
	$maps_zoom = "REX_VALUE[3]" == "" ? 15 : "REX_VALUE[3]";
	$height = "REX_VALUE[7]" == "" ? "500" : "REX_VALUE[7]";
	$height_unit = "REX_VALUE[8]" == "" ? "px" : "REX_VALUE[8]";
	$substitute = ["\r\n" => "", "\r" => "", "\n" => "", '"' => "'"];
	$infotext = "REX_VALUE[id=2 output=html]";
	$popup_js = $infotext != "" ? ".bindPopup('". strtr($infotext, $substitute) ."').openPopup()" : "";
	
	$map_id = rand();

	$leaflet_js_file = 'modules/04-2/leaflet.js';
	print '<script src="'. rex_url::addonAssets('d2u_helper', $leaflet_js_file) .'?buster='. filemtime(rex_path::addonAssets('d2u_helper', $leaflet_js_file)) .'"></script>' . PHP_EOL;

?>
	<div id="map-<?php echo $map_id; ?>" style="width:100%;height: <?php echo $height . $height_unit; ?>"></div>
	<script type="text/javascript" async="async">
		var map = L.map('map-<?php echo $map_id; ?>').setView([<?= $latitude; ?>, <?= $longitude; ?>], <?php echo $maps_zoom; ?>);
		L.tileLayer('/?osmtype=german&z={z}&x={x}&y={y}', {
			attribution: 'Map data &copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		map.scrollWheelZoom.disable();
		var myIcon = L.icon({
			iconUrl: '<?php echo rex_url::addonAssets('d2u_helper', 'modules/04-2/marker-icon.png'); ?>',
			shadowUrl: '<?php echo rex_url::addonAssets('d2u_helper', 'modules/04-2/marker-shadow.png'); ?>',

			iconSize:     [25, 41], // size of the icon
			shadowSize:   [41, 41], // size of the shadow
			iconAnchor:   [12, 40], // point of the icon which will correspond to marker's location
			shadowAnchor: [13, 40], // the same for the shadow
			popupAnchor:  [0, -41]  // point from which the popup should open relative to the iconAnchor
		});
		var marker = L.marker([<?= $latitude; ?>, <?= $longitude; ?>], {
			draggable: false,
			icon: myIcon
		}).addTo(map)<?php echo $popup_js; ?>;
	</script>
</div>