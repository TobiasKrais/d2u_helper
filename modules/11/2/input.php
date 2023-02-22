<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Smartphones:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]"  class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 9 => '9 von 12 Spalten', 8 => '8 von 12 Spalten', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
        foreach ($values as $key => $value) {
            echo '<option value="'. $key .'" ';

            if ((int) 'REX_VALUE[20]' === $key) { /** @phpstan-ignore-line */
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
		<select name="REX_INPUT_VALUE[19]"  class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 9 => '9 von 12 Spalten', 8 => '8 von 12 Spalten', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
        foreach ($values as $key => $value) {
            echo '<option value="'. $key .'" ';

            if ((int) 'REX_VALUE[19]' === $key) { /** @phpstan-ignore-line */
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
		<select name="REX_INPUT_VALUE[18]"  class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 9 => '9 von 12 Spalten', 8 => '8 von 12 Spalten', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
        foreach ($values as $key => $value) {
            echo '<option value="'. $key .'" ';

            if ((int) 'REX_VALUE[18]' === $key) { /** @phpstan-ignore-line */
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
        $values_offset = [0 => 'Nicht zentrieren.', 1 => 'Zentrieren, wenn freie Breite von anderem Inhalt nicht genutzt wird'];
        foreach ($values_offset as $key => $value) {
            echo '<option value="'. $key .'" ';

            if ((int) 'REX_VALUE[17]' === $key) { /** @phpstan-ignore-line */
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
		<select name="REX_INPUT_VALUE[1]" class="form-control">
			<option value="none">Bild im Original einbinden</option>
		<?php
            $sql = rex_sql::factory();
            $result = $sql->setQuery('SELECT name FROM ' . \rex::getTablePrefix() . 'media_manager_type ORDER BY status, name');
            for ($i = 0; $i < $result->getRows(); ++$i) {
                $name = $result->getValue('name');
                echo '<option value="'. $name .'" '. ('REX_VALUE[1]' == $name ? 'selected="selected" ' : '') .'>'. $name .'</option>';
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
	<div class="col-xs-4">Name:</div>
	<div class="col-xs-8"><input type="text" style="width: 100%" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" class="form-control" /></div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">Straße und Hausnummer:</div>
	<div class="col-xs-8"><input type="text" style="width: 100%" name="REX_INPUT_VALUE[5]" value="REX_VALUE[5]" class="form-control" /></div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">PLZ und Ort:</div>
	<div class="col-xs-8"><input type="text" style="width: 100%" name="REX_INPUT_VALUE[6]" value="REX_VALUE[6]" class="form-control" /></div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">Telefon:</div>
	<div class="col-xs-8"><input type="text" style="width: 100%" name="REX_INPUT_VALUE[3]" value="REX_VALUE[3]" class="form-control" /></div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">E-Mail-Adresse:</div>
	<div class="col-xs-8"><input type="text" style="width: 100%" name="REX_INPUT_VALUE[4]" value="REX_VALUE[4]" class="form-control" /></div>
</div>