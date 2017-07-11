<div class="row">
	<div class="col-xs-4">
		Vollständige URL:
	</div>
	<div class="col-xs-8">
		<input type="text" size="50" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" placeholder="https://www.redaxo.org/" />
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Breite des IFrames in Pixel:
	</div>
	<div class="col-xs-8">
		<input type="number" size="4" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" />
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Höhe des IFrames in Pixel:
	</div>
	<div class="col-xs-8">
		<input type="number" size="4" name="REX_INPUT_VALUE[3]" value="REX_VALUE[3]" />
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
 <div class="row">
	<div class="col-xs-4">
		Scrollen im IFrame erlauben?:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[4]" >
		<?php
		$values = array('auto'=>"Automatisch erlauben", 'scroll'=>"Scrollbars immer anzeigen", 'hidden'=>"Nie scrollen");
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[4]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>