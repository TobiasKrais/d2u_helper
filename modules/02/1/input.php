<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf größeren Geräten:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
		$values = [12=>"12 von 12 Spalten (ganze Breite)", 8=>"8 von 12 Spalten", 6=>"6 von 12 Spalten", 4=>"4 von 12 Spalten", 3=>"3 von 12 Spalten"];
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
<div class="row">
	<div class="col-xs-12"><div style="border-top: 1px darkgrey solid; margin: 1em 0;"></div></div>
</div>
<div class="row">
	<div class="col-xs-4">
		&Uuml;berschrift:
	</div>
	<div class="col-xs-6">
		<input type="text" size="50" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" class="form-control"/>
	</div>
	<div class="col-xs-2">
		<select name="REX_INPUT_VALUE[2]" class="form-control">
		<?php
		foreach (["h1","h2","h3","h4","h5","h6"] as $value) {
			echo '<option value="'.$value.'" ';
	
			if ( "REX_VALUE[2]"=="$value" ) {
				echo 'selected="selected" ';
			}
			echo '>'.$value.'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[3]" value="true" <?php echo "REX_VALUE[3]" == 'true' ? ' checked="checked"' : ''; ?> class="form-control d2u_helper_toggle" />
	</div>
	<div class="col-xs-8">
		Überschrift zentrieren
	</div>
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[4]" value="true" <?php echo "REX_VALUE[4]" == 'true' ? ' checked="checked"' : ''; ?> class="form-control d2u_helper_toggle" />
	</div>
	<div class="col-xs-8">
		kurze Linie unter der Überschrift zeichnen
	</div>
	<div class="col-xs-12">&nbsp;</div>
</div>