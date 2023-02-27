<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
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
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
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
		L&auml;ngengrad:
	</div>
	<div class="col-xs-8">
		<input type="text" size="12" name="REX_INPUT_VALUE[4]" value="REX_VALUE[4]" class="form-control"/>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Infotext <small>(HTML-Notation ist erlaubt. Wenn kein Text vorhanden ist, wird die Infobox ausgeblendet.)</small>:
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
            foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] as $value) {
                echo '<option value="'.$value.'" ';

                if ('REX_VALUE[3]' === $value) { /** @phpstan-ignore-line */
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
            foreach (['px', '%'] as $value) {
                echo '<option value="'.$value.'" ';

                if ('REX_VALUE[8]' === $value) { /** @phpstan-ignore-line */
                    echo 'selected="selected" ';
                }
                echo '>'.$value.'</option>';
            }
            ?>
		</select>
	</div>
</div>
<?php
if (rex_addon::get('geolocation')->isAvailable()) {
?>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">
		Auswahl Geolocation Karte
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[9]" id="mapset-select" class="form-control">
            <option value="">(Standardkarte)</option>
            <?php
                $mapsets = \Geolocation\mapset::query()
                    ->orderBy('title')
                    ->findValues('title', 'id');
                foreach ($mapsets as $k => $v) {
                    echo '<option value="'. $k .'"'. ($k === 'REX_VALUE[9]' ? ' selected="selected"' : '') .'>'. $v .'</option>';
                }
            ?>
		</select>
	</div>
</div>
<?php
}
