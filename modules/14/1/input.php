<div class="row">
	<div class="col-xs-4">
		Anzahl Treffer pro Seite
	</div>
	<div class="col-xs-8">
		<input type="number" size="3" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" min="10" max="100" class="form-control" />
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[2]" value="true" <?= 'REX_VALUE[2]' == 'true' ? ' checked="checked"' : '' ?> class="form-control d2u_helper_toggle" />
	</div>
	<div class="col-xs-8">
		Ähnliche Suchtreffer anzeigen, wenn keine Treffer gefunden werden?<br />
		<?php
            if (0 === rex_config::get('search_it', 'similarwordsmode', 0)) {
                echo '<b>Die Ähnlichkeitssuche muss in den Search It Einstellungen aktiviert werden!</b>';
            }
        ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Anzuwendender Media Manager Typ bei Vorschaubildern:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[3]" class="form-control">
		<?php
            $sql = rex_sql::factory();
            $selected = 'REX_VALUE[3]' ?: 'rex_mediapool_preview';
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