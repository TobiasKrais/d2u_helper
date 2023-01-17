<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Smartphones:
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
</div>
<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Tablets:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[19]" class="form-control">
		<?php
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if (intval("REX_VALUE[19]") === $key) { /** @phpstan-ignore-line */
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
</div>
<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf größeren Geräten:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[18]" class="form-control">
		<?php
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if (intval("REX_VALUE[18]") === $key) { /** @phpstan-ignore-line */
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
		offset_changer($("select[name='REX_INPUT_VALUE[18]']").val());
	});

	// Hide on selection change
	$("select[name='REX_INPUT_VALUE[18]']").on('change', function(e) {
		offset_changer($(this).val());
	});
</script>
<div class="row">
	<div class="col-xs-12"><div style="border-top: 1px darkgrey solid; margin: 1em 0;"></div></div>
</div>
<div class="row">
	<div class="col-xs-4">
		YFeed Profil:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[1]" class="form-control">
		<?php
		foreach(rex_yfeed_stream::getAllActivated() as $feed) {
			echo '<option value="'. $feed->getStreamId() .'" ';
	
			if ("REX_VALUE[1]" == $feed->getStreamId()) {
				echo 'selected="selected" ';
			}
			echo '>'. $feed->getTitle() .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Anzahl Feed Items:
	</div>
	<div class="col-xs-8">
		<input type="number" min="1" max="100" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" class="form-control" />
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Anzahl Items pro Zeile:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[4]" class="form-control">
		<?php
		$values = [2, 3, 4, 6];
		foreach($values as $value) {
			echo '<option value="'. $value .'" ';
	
			if ("REX_VALUE[4]" == $value) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Detailansicht:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[3]" class="form-control">
		<?php
			echo '<option value="picture" '. ("REX_VALUE[3]" == "picture" ? 'selected="selected" ' : '') .'>Zeigt Bild in groß</option>';
			echo '<option value="feed" '. ("REX_VALUE[3]" == "feed" ? 'selected="selected" ' : '') .'>Zeigt Feed Vorschau</option>';
		?>
		</select>
	</div>
</div>