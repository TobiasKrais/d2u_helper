<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
		$values = [12=>"12 von 12 Spalten (ganze Breite)", 9=>"9 von 12 Spalten", 8=>"8 von 12 Spalten", 6=>"6 von 12 Spalten", 4=>"4 von 12 Spalten", 3=>"3 von 12 Spalten"];
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if (intval("REX_VALUE[20]") === $key) { /** @phpstan-ignore-line */
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		Auf größeren Bildschirmen zentrieren?
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[17]"  class="form-control">
		<?php
		$values_offset = [0=>"Nicht zentrieren.", 1=>"Zentrieren, wenn freie Breite von anderem Inhalt nicht genutzt wird"];
		foreach($values_offset as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if (intval("REX_VALUE[17]") === $key) { /** @phpstan-ignore-line */
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<script>
	function offset_changer(value) {
		if (value === "12") {
			$("select[name='REX_INPUT_VALUE[17]']").parent().parent().slideUp();
		}
		else {
			$("select[name='REX_INPUT_VALUE[17]']").parent().parent().slideDown();
		}
	}

	// Hide on document load
	$(document).ready(function() {
		offset_changer($("select[name='REX_INPUT_VALUE[20]']").val());
	});

	// Hide on selection change
	$("select[name='REX_INPUT_VALUE[20]']").on('change', function(e) {
		offset_changer($(this).val());
	});
</script>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Adresse (Straße Nr., PLZ Ort, DE):
	</div>
	<div class="col-xs-8">
		<input type="text" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" class="form-control"/>
	</div>
</div>
<?php
if(rex_config::get("d2u_helper", "maps_key", "") == "") {
?>
	<div class="row"><div class="col-xs-12">&nbsp;</div></div>
	<div class="row">
		<div class="col-xs-4">
			Google API key:
		</div>
		<div class="col-xs-8">
			<input type="text" name="REX_INPUT_VALUE[11]" value="REX_VALUE[11]"  class="form-control"/>
		</div>
	</div>
<?php
}
else {
?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo rex_config::get("d2u_helper", "maps_key", ""); ?>"></script>
	<script>
		function geocode() {
			if($("input[name='REX_INPUT_VALUE[1]']").val() === "") {
				alert("<?php echo rex_i18n::msg('d2u_helper_geocode_fields'); ?>");
				return;
			}

			// Geocode
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({'address': $("input[name='REX_INPUT_VALUE[1]']").val()}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					$("input[name='REX_INPUT_VALUE[5]']").val(results[0].geometry.location.lat);
					$("input[name='REX_INPUT_VALUE[4]']").val(results[0].geometry.location.lng);
					// Show check geolocation button and set link to button
					$("#check_geocode").attr('href', "https://maps.google.com/?q=" + $("input[name='REX_INPUT_VALUE[5]']").val() + "," + $("input[name='REX_INPUT_VALUE[4]']").val() + "&z=17");
					$("#check_geocode").parent().show();
				}
				else {
					alert("<?php echo rex_i18n::msg('d2u_helper_geocode_failure'); ?>");
				}
			});
		}
	</script>
	<div class="row"><div class="col-xs-12">&nbsp;</div></div>
	<div class="row">
		<div class="col-xs-4"></div>
		<div class="col-xs-8">
			<input type="submit" value="<?php echo rex_i18n::msg('d2u_helper_geocode'); ?>" onclick="geocode(); return false;" class="btn btn-save">
			<div class="btn btn-abort"><a href="https://maps.google.com/?q=REX_VALUE[4],REX_VALUE[5]&z=17" id="check_geocode" target="_blank"><?php echo rex_i18n::msg('d2u_helper_geocode_check'); ?></a></div>
		</div>
	</div>
<?php
	$latitude = "REX_VALUE[4]";
	$longitude = "REX_VALUE[5]";
	if($latitude == "" && $longitude == "") {
		print '<script>jQuery(document).ready(function($) { $("#check_geocode").parent().hide(); });</script>';
	}
}
?>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4"></div>
	<div class="col-xs-8"><?php print htmlspecialchars_decode(rex_i18n::msg('d2u_helper_geocode_hint')); ?></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		L&auml;ngengrad:
	</div>
	<div class="col-xs-8">
		<input type="text" size="12" name="REX_INPUT_VALUE[4]" value="REX_VALUE[4]" class="form-control"/>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Breitengrad:
	</div>
	<div class="col-xs-8">
		<input type="text" size="12" name="REX_INPUT_VALUE[5]" value="REX_VALUE[5]" class="form-control"/>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Infotext <small>(HTML-Notation ist erlaubt. Wenn kein Text vorhanden ist, wird die Infobox ausgeblendet.)</small>:
	</div>
	<div class="col-xs-8">
		<textarea cols="50" rows="3" name="REX_INPUT_VALUE[2]" class="form-control">REX_VALUE[2]</textarea>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Zoom-Faktor:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[3]" class="form-control">
			<?php
			foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19] as $value) {
				echo '<option value="'.$value.'" ';
	
				if ("REX_VALUE[3]" == "$value") {
					echo 'selected="selected" ';
				}
				echo '>'.$value.'</option>';
			}
			?>
		</select>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Kartenart:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[6]" class="form-control">
			<?php
			foreach (['HYBRID', 'ROADMAP', 'SATELLITE', 'TERRAIN'] as $value) {
				echo '<option value="'.$value.'" ';
	
				if ( "REX_VALUE[6]"=="$value" ) {
					echo 'selected="selected" ';
				}
				echo '>'.$value.'</option>';
			}
			?>
		</select>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		H&ouml;he der angezeigten Karte:
	</div>
	<div class="col-xs-6">
		<input type="text" size="5" name="REX_INPUT_VALUE[7]" value="REX_VALUE[7]" class="form-control"/>
	</div>
	<div class="col-xs-2">
		<select name="REX_INPUT_VALUE[8]"class="form-control">
			<?php
			foreach (array("px", "%") as $value) {
				echo '<option value="'.$value.'" ';
	
				if ( "REX_VALUE[8]"=="$value" ) {
					echo 'selected="selected" ';
				}
				echo '>'.$value.'</option>';
			}
			?>
		</select>
	</div>
</div>