<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Smartphones:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 10 => '10 von 12 Spalten', 8 => '8 von 12 Spalten', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
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
		<select name="REX_INPUT_VALUE[19]" class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 10 => '10 von 12 Spalten', 8 => '8 von 12 Spalten', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
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
		<select name="REX_INPUT_VALUE[18]" class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 10 => '10 von 12 Spalten', 8 => '8 von 12 Spalten', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
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
	<div class="col-xs-6">
		Anzahl Bilder pro Reihe auf großen Bildschirmen:
	</div>
	<div class="col-xs-6">
		<select name="REX_INPUT_VALUE[1]" class="form-control">
		<?php
        $values = [6 => '6 Bilder', 4 => '4 Bilder', 3 => '3 Bilder', 2 => '2 Bilder', 1 => '1 Bild'];
        foreach ($values as $key => $value) {
            echo '<option value="'. $key .'" ';

            if ((int) 'REX_VALUE[1]' === $key) { /** @phpstan-ignore-line */
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
	<div class="col-xs-12">
		<?php
			$pics = null !== 'REX_MEDIALIST[1]' && '' !== 'REX_MEDIALIST[1]' ? explode(',', 'REX_MEDIALIST[1]') : [];
			\FriendsOfRedaxo\D2UHelper\BackendHelper::form_imagelistfield('d2u_helper_pictures', 1, is_array($pics) ? $pics : []);
		?>
	</div>
</div>