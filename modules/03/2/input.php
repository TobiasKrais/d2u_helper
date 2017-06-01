<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" >
		<?php
		$values = array(4=>"4 von 12 Spalten", 5=>"5 von 12 Spalten", 6=>"6 von 12 Spalten", 7=>"7 von 12 Spalten", 8=>"8 von 12 Spalten", 9=>"9 von 12 Spalten", 10=>"10 von 12 Spalten", 11=>"11 von 12 Spalten", 12=>"12 von 12 Spalten (ganze Breite)");
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
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Bilder:
	</div>
	<div class="col-xs-8">
		REX_MEDIALIST[id="1" type="jpg,png" widget="1"]
	</div>
</div>