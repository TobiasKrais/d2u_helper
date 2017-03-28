<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Smartphones:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" >
		<?php
		$values = array(3=>"3 von 12 Spalten", 4=>"4 von 12 Spalten", 6=>"6 von 12 Spalten", 12=>"12 von 12 Spalten (ganze Breite)");
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
<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Tablets:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[19]" >
		<?php
		$values = array(3=>"3 von 12 Spalten", 4=>"4 von 12 Spalten", 6=>"6 von 12 Spalten", 8=>"8 von 12 Spalten", 12=>"12 von 12 Spalten (ganze Breite)");
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[19]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf größeren Geräten:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[18]" >
		<?php
		$values = array(3=>"3 von 12 Spalten", 4=>"4 von 12 Spalten", 6=>"6 von 12 Spalten", 8=>"8 von 12 Spalten", 12=>"12 von 12 Spalten (ganze Breite)");
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[18]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Offset (Seitenabstand) auf größeren Geräten:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[17]" >
		<?php
		$values = array(0=>"Kein Offset", 1=>"1 Spalten", 2=>"2 Spalten", 3=>"3  Spalten");
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[17]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<textarea name="REX_INPUT_VALUE[1]" class="redactorEditor2-full" >
		REX_VALUE[1]
		</textarea>
	</div>
</div>