<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" >
		<?php
		$values = array(3=>"3 von 12 Spalten", 4=>"4 von 12 Spalten", 6=>"6 von 12 Spalten");
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
		&Uuml;berschrift:<br />
	</div>
	<div class="col-xs-8">
		<input type="text" size="50" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" />
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Text:<br />
	</div>
	<div class="col-xs-12 col-sm-8">
		<?php
			$editor_class = "redactorEditor2-full";
			if(rex_addon::get('tinymce4')->isAvailable()) {
				$editor_class = "tinyMCEEditor";
			}
		?>
		<br>
		<textarea name="REX_INPUT_VALUE[2]" class="form-control <?php print $editor_class; ?>" style="height: 500px">
		REX_VALUE[2]
		</textarea>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Bild:
	</div>
	<div class="col-xs-8">
		REX_MEDIA[id="1" type="jpg,png" widget="1"]
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Anzuwendender Media Manager Typ:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[3]" >
		<?php
			$sql = rex_sql::factory();
			$result = $sql->setQuery('SELECT name FROM ' . rex::getTablePrefix() . 'media_manager_type ORDER BY status, name');
			for($i = 0; $i < $result->getRows(); $i++) {
				$name = $result->getValue("name");
				echo '<option value="'. $name .'" ';
	
				if ("REX_VALUE[3]" == $name) {
					echo 'selected="selected" ';
				}
				echo '>'. $name .'</option>';
				$result->next();
			}
		?>
		</select>
	</div>
</div>