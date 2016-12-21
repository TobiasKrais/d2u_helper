<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" >
		<?php
		$values = array(1=>"1 von 12 Spalten", 2=>"2 von 12 Spalten", 3=>"3 von 12 Spalten", 4=>"4 von 12 Spalten", 5=>"5 von 12 Spalten", 6=>"6 von 12 Spalten", 7=>"7 von 12 Spalten", 8=>"8 von 12 Spalten", 9=>"9 von 12 Spalten", 10=>"10 von 12 Spalten", 11=>"11 von 12 Spalten", 12=>"12 von 12 Spalten (ganze Breite)");
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
		&Uuml;berschrift:<br />
	</div>
	<div class="col-xs-8">
		<input type="text" size="50" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" />
		<select name="REX_INPUT_VALUE[2]" >
		<?php
		foreach (array("h1","h2","h3","h4","h5","h6") as $value) {
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
