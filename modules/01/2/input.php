<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
		$values = [12=>"12 von 12 Spalten (ganze Breite)", 10=>"10 von 12 Spalten", 8=>"8 von 12 Spalten", 6=>"6 von 12 Spalten", 4=>"4 von 12 Spalten", 3=>"3 von 12 Spalten"];
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
	<div class="col-xs-4">
		Auf größeren Bildschirmen zentrieren?
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[17]"  class="form-control">
		<?php
		$values_offset = [0=>"Nicht zentrieren.", 1=>"Zentrieren, wenn freie Breite von anderem Inhalt nicht genutzt wird"];
		foreach($values_offset as $key => $value) {
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
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<dl class="rex-form-group form-group">
			<dt>
				<input class="form-control d2u_helper_toggle" type="checkbox" name="REX_INPUT_VALUE[5]" value="true"<?php if("REX_VALUE[5]" == 'true') { print ' checked="checked"'; } ?>>
			</dt>
			<dd>
				<label>Soll die Höhe dieses Blocks an die Höhe anderer Blöcke dieses Moduls auf dieser Seite angeglichen werden?</label>
			</dd>
		</dl>
	</div>
</div>
<div class="row">
	<div class="col-xs-12"><div style="border-top: 1px darkgrey solid; margin-bottom: 1em;"></div></div>
</div>
<div class="row">
	<div class="col-xs-4">
		&Uuml;berschrift (Fettschrift, optional):<br />
	</div>
	<div class="col-xs-8">
		<input type="text" class="form-control" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" />
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;<div style="border-top: 1px darkgrey solid; margin-bottom: 1em;"></div></div>
</div>
<div class="row">
	<div class="col-xs-4">
		Link von Überschrift und Bild zu ...
	</div>
	<div class="col-xs-8">
		<?php
		$select_link = new rex_select(); 
		$select_link->setName('REX_INPUT_VALUE[7]'); 
		$select_link->setSize(1);
		$select_link->setAttribute('class', 'form-control');
		$select_link->setAttribute('id', 'selector');

		$select_link->addOption("ohne Link", "without"); 
		$select_link->addOption("Redaxo Artikel", "article"); 
		$select_link->addOption("Externer Link", "link"); 
		$select_link->addOption("PDF Datei aus Medienpool", "download"); 

		$select_link->setSelected("REX_VALUE[7]");

		echo $select_link->show();
		?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row" id="article">
	<div class="col-xs-4">
		Redaxo Artikel
	</div>
	<div class="col-xs-8">
		REX_LINK[id="1" widget="1"]
	</div>
</div>

<div class="row" id="link">
	<div class="col-xs-4">
		Link URL:
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[8]" value="REX_VALUE[8]" class="form-control"/>
	</div>
</div>
<div class="row" id="download">
	<div class="col-xs-4">
		Medienpool PDF Datei
	</div>
	<div class="col-xs-8">
		REX_MEDIA[id="2" types="pdf" widget="2"]
	</div>
</div>
<script>
	function changeType() {
		if($('#selector').val() === "article") {
			$('#article').show();
			$('#link').hide();
			$('#download').hide();
		}
		else if($('#selector').val() === "link") {
			$('#article').hide();
			$('#link').show();
			$('#download').hide();
		}
		else if($('#selector').val() === "download") {
			$('#article').hide();
			$('#link').hide();
			$('#download').show();
		}
		else {
			$('#article').hide();
			$('#link').hide();
			$('#download').hide();
		}
	}
	
	// On init
	changeType();
	
	// On change
	$('#selector').on('change', function() {
		changeType();
	});
</script>
<div class="row">
	<div class="col-xs-12">&nbsp;<div style="border-top: 1px darkgrey solid; margin-bottom: 1em;"></div></div>
</div>
<div class="row">
	<div class="col-xs-4">
		Bild:
	</div>
	<div class="col-xs-8">
		REX_MEDIA[id="1" types="jpg,png,webp" widget="1"]
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
		<select name="REX_INPUT_VALUE[3]" class="form-control">
			<option value="">Bild im Original einbinden</option>
		<?php
			$sql = rex_sql::factory();
			$result = $sql->setQuery('SELECT name FROM ' . \rex::getTablePrefix() . 'media_manager_type ORDER BY status, name');
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
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Breite des Bildes:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[6]" class="form-control">
		<?php
		$values = [6=>"6 von 12 Spalten", 4=>"4 von 12 Spalten", 3=>"3 von 12 Spalten", 2=>"2 von 12 Spalten"];
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[6]" == $key) {
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
		Position Bild:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[4]" class="form-control">
			<?php
				$picture_position = "REX_VALUE[4]";
				print '<option value="top"'. ($picture_position == "top" ? ' selected="selected"' : '') .'>Oberhalb vom Text</option>';
				print '<option value="left"'. ($picture_position == "left" ? ' selected="selected"' : '') .'>Links vom Text</option>';
				print '<option value="right"'. ($picture_position == "right" ? ' selected="selected"' : '') .'>Rechts vom Text</option>';
				print '<option value="bottom"'. ($picture_position == "bottom" ? ' selected="selected"' : '') .'>Unterhalb vom Text</option>';
			?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<dl class="rex-form-group form-group">
			<dt>
				<input class="form-control d2u_helper_toggle" type="checkbox" name="REX_INPUT_VALUE[9]" value="true"<?php if("REX_VALUE[9]" == 'true') { print ' checked="checked"'; } ?>>
			</dt>
			<dd>
				<label>Soll der Bildtitel aus dem Medienpool unterhalb des Bildes angezeigt werden?
					<?php
						if(count(rex_clang::getAllIds(false)) > 1) {
							print '<br><small>(Sprachbezogenes Feld med_title_<i>sprach_id</i> kann angelegt werden um Titel in mehreren Sprachen zu verwalten.)</small>';
						}
					?>
				</label>
			</dd>
		</dl>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;<div style="border-top: 1px darkgrey solid; margin-bottom: 1em;"></div></div>
</div>
<div class="row">
	<div class="col-xs-12">
		<textarea name="REX_INPUT_VALUE[2]" class="form-control <?php print d2u_addon_backend_helper::getWYSIWYGEditorClass(); ?>" style="height: 500px">REX_VALUE[2]</textarea>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[10]" value="true" <?php echo "REX_VALUE[10]" == 'true' ? ' checked="checked"' : ''; ?> class="form-control d2u_helper_toggle" />
	</div>
	<div class="col-xs-8">
		Unterhalb vom Text einen zusätzlichen aufklappbaren Text anzeigen<br />
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">Text, der zusätzliche eingeblendet werden kann:</div>
	<div class="col-xs-12">
		<textarea name="REX_INPUT_VALUE[11]" class="form-control <?php print d2u_addon_backend_helper::getWYSIWYGEditorClass(); ?>" style="height: 500px">REX_VALUE[11]</textarea>
	</div>
</div>
<script>
	function container_changer() {
		if ($("input[name='REX_INPUT_VALUE[10]']").is(':checked')) {
			$("textarea[name='REX_INPUT_VALUE[11]']").parent().parent().slideDown();
		}
		else {
			$("textarea[name='REX_INPUT_VALUE[11]']").parent().parent().slideUp();
		}
	}

	// Hide on document load
	$(document).ready(function() {
		container_changer();
	});

	// Hide on selection change
	$("input[name='REX_INPUT_VALUE[10]']").on('change', function(e) {
		container_changer();
	});
</script>