<?php
$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */
?>
<div class="col-sm-<?= $cols . $offset_lg ?>">
<?php
    $longitude = 'REX_VALUE[4]' === '' ? 0 : 'REX_VALUE[4]'; /** @phpstan-ignore-line */
    $latitude = 'REX_VALUE[5]' === '' ? 0 : 'REX_VALUE[5]'; /** @phpstan-ignore-line */
    $maps_zoom = 'REX_VALUE[3]' === '' ? 15 : 'REX_VALUE[3]'; /** @phpstan-ignore-line */
    $height = 'REX_VALUE[7]' === '' ? '500' : 'REX_VALUE[7]'; /** @phpstan-ignore-line */
    $height_unit = 'REX_VALUE[8]' === '' ? 'px' : 'REX_VALUE[8]'; /** @phpstan-ignore-line */
    $substitute = ["\r\n" => '', "\r" => '', "\n" => '', '"' => "'"];
    $infotext = 'REX_VALUE[id=2 output=html]';

    $map_id = random_int(0, getrandmax());

	$geolocation = rex_addon::get('geolocation');
    if ($geolocation->isAvailable()) {
        try {
            if (rex::isFrontend()) {
				if(rex_version::compare('2.0.0', $geolocation->getVersion(), '<=')) {
					// Geolocation 2.x
                	\FriendsOfRedaxo\Geolocation\Tools::echoAssetTags();
				}
				else {
					// Geolocation 1.x
					\Geolocation\tools::echoAssetTags();
				}
            }
?>
	<script>
		Geolocation.default.positionColor = '<?= (string) rex_config::get('d2u_helper', 'article_color_h') ?>';

		// adjust zoom level
		Geolocation.Tools.Center = class extends Geolocation.Tools.Template{
			constructor ( ...args){
				super(args);
				this.zoom = this.zoomDefault = Geolocation.default.zoom;
				this.center = this.centerDefault = L.latLngBounds( Geolocation.default.bounds ).getCenter();
				return this;
			}
			setValue( data ){
				super.setValue( data );
				this.center = L.latLng( data[0] ) || this.centerDefault;
				this.zoom = data[1] || this.zoomDefault;
				this.radius = data[2];
				this.circle = null;
				if( data[2] ) {
					let options = Geolocation.default.styleCenter;
					options.color = data[3] || options.color;
					options.radius = this.radius;
					this.circle = L.circle( this.center, options );
				}
				if( this.map ) this.show( this.map );
				return this;
			}
			show( map ){
				super.show( map );
				map.setView( this.center, this.zoom );
				if( this.circle instanceof L.Circle ) this.circle.addTo( map );
				return this;
			}
			remove(){
				if( this.circle instanceof L.Circle ) this.circle.remove();
				super.remove();
				return this;
			}
			getCurrentBounds(){
				if( this.circle instanceof L.Circle ) {
					return this.radius ? this.circle.getBounds() : this.circle.getLatLng();
				}
				return this.center;
			}
		};
		Geolocation.tools.center = function(...args) { return new Geolocation.Tools.Center(args); };

		// add info box
		Geolocation.Tools.Infobox = class extends Geolocation.Tools.Position{
			setValue( dataset ) {
				// keine Koordinaten => Abbruch
				if( !dataset[0] ) return this;

				// GGf. Default-Farbe temporär ändern, normalen Position-Marker erzeugen
				let color = Geolocation.default.positionColor;
				Geolocation.default.positionColor = dataset[2] || Geolocation.default.positionColor;
				super.setValue(dataset[0]);
				Geolocation.default.positionColor = color;

				// Wenn angegeben: Text als Popup hinzufügen
				if( this.marker && dataset[1] ) {
					this.marker.bindPopup(dataset[1]);
					this.marker.on('click', function (e) {
						this.openPopup();
					});
				}
				return this;
			}
		};
		Geolocation.tools.infobox = function(...args) { return new Geolocation.Tools.Infobox(args); };
	</script>
<?php
        } catch (Exception $e) {
        }

        $mapsetId = (int) 'REX_VALUE[9]';

		if(rex_version::compare('2.0.0', $geolocation->getVersion(), '<=')) {
			// Geolocation 2.x
			echo \FriendsOfRedaxo\Geolocation\Mapset::take($mapsetId)
				->attributes('id', (string) $mapsetId)
				->attributes('style', 'height: '. $height . $height_unit .';width:100%;')
				->dataset('center', [[$latitude, $longitude], $maps_zoom])
				->dataset('position', [$latitude, $longitude])
				->dataset('infobox', [[$latitude, $longitude], $infotext])
				->parse();
		}
		else {
			// Geolocation 1.x
			echo \Geolocation\mapset::take($mapsetId)
				->attributes('id', (string) $mapsetId)
				->attributes('style', 'height: '. $height . $height_unit .';width:100%;')
				->dataset('center', [[$latitude, $longitude], $maps_zoom])
				->dataset('position', [$latitude, $longitude])
				->dataset('infobox', [[$latitude, $longitude], $infotext])
				->parse();
		}

    } elseif (rex_addon::get('osmproxy')->isAvailable()) {
        $popup_js = '' !== $infotext ? ".bindPopup('". addslashes(strtr($infotext, $substitute)) ."').openPopup()" : ''; /** @phpstan-ignore-line */

        $leaflet_js_file = 'modules/04-2/leaflet.js';
        echo '<script src="'. rex_url::addonAssets('d2u_helper', $leaflet_js_file) .'?buster='. filemtime(rex_path::addonAssets('d2u_helper', $leaflet_js_file)) .'"></script>' . PHP_EOL;

?>
		<div id="map-<?= $map_id ?>" style="width:100%;height: <?= $height . $height_unit ?>"></div>
		<script type="text/javascript" async="async">
			var map = L.map('map-<?= $map_id ?>').setView([<?= $latitude ?>, <?= $longitude ?>], <?= $maps_zoom ?>);
			L.tileLayer('/?osmtype=german&z={z}&x={x}&y={y}', {
				attribution: 'Map data &copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
			map.scrollWheelZoom.disable();
			var myIcon = L.icon({
				iconUrl: '<?= rex_url::addonAssets('d2u_helper', 'modules/04-2/marker-icon.png') ?>',
				shadowUrl: '<?= rex_url::addonAssets('d2u_helper', 'modules/04-2/marker-shadow.png') ?>',

				iconSize:     [25, 41], // size of the icon
				shadowSize:   [41, 41], // size of the shadow
				iconAnchor:   [12, 40], // point of the icon which will correspond to marker's location
				shadowAnchor: [13, 40], // the same for the shadow
				popupAnchor:  [0, -41]  // point from which the popup should open relative to the iconAnchor
			});
			var marker = L.marker([<?= $latitude ?>, <?= $longitude ?>], {
				draggable: false,
				icon: myIcon
			}).addTo(map)<?= $popup_js ?>;
		</script>
<?php
    }
?>
</div>