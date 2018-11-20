<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
		$values = [12=>"12 von 12 Spalten (ganze Breite)", 6=>"6 von 12 Spalten", 4=>"4 von 12 Spalten", 3=>"3 von 12 Spalten"];
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[20]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Google API key:
	</div>
	<div class="col-xs-8">
		<?php
		$api_key = rex_config::get('d2u_helper', 'maps_key');
		$placeholder = $api_key != '' ? "Wird aus D2U Helper Addon Einstellungen übernommen: ". $api_key : ''
		?>
		<input type="text" name="REX_INPUT_VALUE[11]" value="REX_VALUE[11]" placeholder="<?php print $placeholder; ?>" class="form-control"/>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Adresse (Straße Nr., PLZ Ort, DE):
	</div>
	<div class="col-xs-8">
		<input type="text" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" class="form-control"/>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-12">
		Wenn Google Maps die Adresse nicht korrekt geocodiert, k&ouml;nnen
		optional die L&auml;ngen- und Breitengrade eingegeben werden (z.B. 7.69295).
		(<a href="http://www.linkr.de/2009/03/24/mit-google-maps-laengen-breitengrade-ermitteln-freies-geocoding-skript/" target="_blank">L&auml;ngen- und Breitengradrechner</a>.)
	</div>
	<div class="col-xs-4">
		L&auml;ngengrad:
	</div>
	<div class="col-xs-8">
		<input type="text" size="12" name="REX_INPUT_VALUE[4]" value="REX_VALUE[4]" class="form-control"/>
	</div>
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
		Infotext (HTML-Notation ist erlaubt. Wenn kein Text vorhanden ist, wird die Infobox ausgeblendet.):
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
			foreach (array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19) as $value) {
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
			foreach (array(HYBRID, ROADMAP, SATELLITE, TERRAIN) as $value) {
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