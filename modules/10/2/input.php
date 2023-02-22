<div class="row">
	<div class="col-xs-4">
		Breite des Blocks auf Smartphones:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
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
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
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
        $values = [12 => '12 von 12 Spalten (ganze Breite)', 6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten'];
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
</div>
<div class="row">
	<div class="col-xs-4">
		Soll Block die gleiche Höhe haben wie andere Box-Blöcke auf dieser Seite?
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[17]" class="form-control">
		<?php
        $values = ['true' => 'Ja', 'false' => 'Nein'];
        foreach ($values as $key => $value) {
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
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
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
		<?php
            $sql = rex_sql::factory();
            $result = $sql->setQuery('SELECT name FROM ' . \rex::getTablePrefix() . 'media_manager_type ORDER BY status, name');
            for ($i = 0; $i < $result->getRows(); ++$i) {
                $name = $result->getValue('name');
                echo '<option value="'. $name .'" ';

                if ('REX_VALUE[3]' == $name) {
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
	<div class="col-xs-12">
		Text:<br />
	</div>
	<div class="col-xs-12">
		<br>
		<textarea name="REX_INPUT_VALUE[1]" class="form-control <?= d2u_addon_backend_helper::getWYSIWYGEditorClass() ?>" style="height: 500px">REX_VALUE[1]</textarea>
	</div>
</div>
