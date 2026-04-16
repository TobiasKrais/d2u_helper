<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
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
	document.addEventListener('DOMContentLoaded', function () {
		var widthSelect = document.querySelector("select[name='REX_INPUT_VALUE[20]']");
		var offsetSelect = document.querySelector("select[name='REX_INPUT_VALUE[17]']");
		if (!widthSelect || !offsetSelect) {
			return;
		}

		var offsetRow = offsetSelect.closest('.row');
		if (!offsetRow) {
			return;
		}

		function updateOffsetVisibility() {
			offsetRow.style.display = widthSelect.value === '12' ? 'none' : '';
		}

		updateOffsetVisibility();
		widthSelect.addEventListener('change', updateOffsetVisibility);
	});
</script>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<dl class="rex-form-group form-group">
			<dt>
				<input class="form-control d2u_helper_toggle" type="checkbox" name="REX_INPUT_VALUE[5]" value="true"<?php if ('REX_VALUE[5]' === 'true') { /** @phpstan-ignore-line */
                echo ' checked="checked"';
                } ?>>
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
		&Uuml;berschrift (optional):
	</div>
	<div class="col-xs-6">
		<input type="text" class="form-control" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" />
	</div>
	<div class="col-xs-2">
		<select name="REX_INPUT_VALUE[13]" class="form-control">
		<?php
		$heading_type_value = 'REX_VALUE[13]';
		foreach (['b' => 'Fettschrift', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6'] as $key => $value) {
			echo '<option value="'. $key .'" ';

			if ($heading_type_value === $key || ('' === $heading_type_value && 'b' === $key)) { /** @phpstan-ignore-line */
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
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

        $select_link->addOption('ohne Link', 'without');
        $select_link->addOption('Redaxo Artikel', 'article');
        $select_link->addOption('Externer Link', 'link');
        $select_link->addOption('PDF Datei aus Medienpool', 'download');

        $select_link->setSelected('REX_VALUE[7]');

        $select_link->show();
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
	document.addEventListener('DOMContentLoaded', function () {
		var selector = document.getElementById('selector');
		var articleRow = document.getElementById('article');
		var linkRow = document.getElementById('link');
		var downloadRow = document.getElementById('download');
		if (!selector || !articleRow || !linkRow || !downloadRow) {
			return;
		}

		function updateLinkTypeVisibility() {
			articleRow.style.display = selector.value === 'article' ? '' : 'none';
			linkRow.style.display = selector.value === 'link' ? '' : 'none';
			downloadRow.style.display = selector.value === 'download' ? '' : 'none';
		}

		updateLinkTypeVisibility();
		selector.addEventListener('change', updateLinkTypeVisibility);
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
            for ($i = 0; $i < $result->getRows(); ++$i) {
                $name = $result->getValue('name');
                echo '<option value="'. $name .'" ';

                if ('REX_VALUE[3]' === $name) {
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
        $values = [6 => '6 von 12 Spalten', 4 => '4 von 12 Spalten', 3 => '3 von 12 Spalten', 2 => '2 von 12 Spalten'];
        foreach ($values as $key => $value) {
            echo '<option value="'. $key .'" ';

            if ((int) 'REX_VALUE[6]' === $key) { /** @phpstan-ignore-line */
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
                $picture_position = 'REX_VALUE[4]';
                echo '<option value="top"'. ('top' === $picture_position ? ' selected="selected"' : '') .'>Oberhalb vom Text</option>'; /** @phpstan-ignore-line */
                echo '<option value="left"'. ('left' === $picture_position ? ' selected="selected"' : '') .'>Links vom Text</option>'; /** @phpstan-ignore-line */
                echo '<option value="right"'. ('right' === $picture_position ? ' selected="selected"' : '') .'>Rechts vom Text</option>'; /** @phpstan-ignore-line */
                echo '<option value="bottom"'. ('bottom' === $picture_position ? ' selected="selected"' : '') .'>Unterhalb vom Text</option>'; /** @phpstan-ignore-line */
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
				<input class="form-control d2u_helper_toggle" type="checkbox" name="REX_INPUT_VALUE[9]" value="true"<?php if ('REX_VALUE[9]' === 'true') { /** @phpstan-ignore-line */
                echo ' checked="checked"';
                } ?>>
			</dt>
			<dd>
				<label>Soll der Bildtitel aus dem Medienpool unterhalb des Bildes angezeigt werden?
					<?php
                        if (count(rex_clang::getAllIds(false)) > 1) {
                            echo '<br><small>(Sprachbezogenes Feld med_title_<i>sprach_id</i> kann angelegt werden um Titel in mehreren Sprachen zu verwalten.)</small>';
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
		<dl class="rex-form-group form-group">
			<dt>
				<input type="checkbox" name="REX_INPUT_VALUE[12]" value="true" <?= 'REX_VALUE[12]' === 'true' ? ' checked="checked"' : ''/** @phpstan-ignore-line */ ?> class="form-control d2u_helper_toggle" />
			</dt>
			<dd>
				<label>Textblock am Bild vertikal mittig ausrichten</label>
			</dd>
		</dl>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<textarea name="REX_INPUT_VALUE[2]" class="form-control <?= \TobiasKrais\D2UHelper\BackendHelper::getWYSIWYGEditorClass() ?>" style="height: 500px">REX_VALUE[2]</textarea>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[10]" value="true" <?= 'REX_VALUE[10]' === 'true' ? ' checked="checked"' : ''/** @phpstan-ignore-line */ ?> class="form-control d2u_helper_toggle" />
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
		<textarea name="REX_INPUT_VALUE[11]" class="form-control <?= \TobiasKrais\D2UHelper\BackendHelper::getWYSIWYGEditorClass() ?>" style="height: 500px">REX_VALUE[11]</textarea>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		var expandableToggle = document.querySelector("input[name='REX_INPUT_VALUE[10]']");
		var expandableText = document.querySelector("textarea[name='REX_INPUT_VALUE[11]']");
		var picturePosition = document.querySelector("select[name='REX_INPUT_VALUE[4]']");
		var pictureWidth = document.querySelector("select[name='REX_INPUT_VALUE[6]']");

		if (expandableToggle && expandableText) {
			var expandableRow = expandableText.closest('.row');
			if (expandableRow) {
				var updateExpandableVisibility = function () {
					expandableRow.style.display = expandableToggle.checked ? '' : 'none';
				};

				updateExpandableVisibility();
				expandableToggle.addEventListener('change', updateExpandableVisibility);
			}
		}

		if (picturePosition && pictureWidth) {
			var pictureWidthRow = pictureWidth.closest('.row');
			if (pictureWidthRow) {
				var updatePictureWidthVisibility = function () {
					pictureWidthRow.style.display = picturePosition.value === 'top' || picturePosition.value === 'bottom' ? 'none' : '';
				};

				updatePictureWidthVisibility();
				picturePosition.addEventListener('change', updatePictureWidthVisibility);
			}
		}
	});
</script>