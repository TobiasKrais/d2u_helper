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
		<input type="checkbox" name="REX_INPUT_VALUE[2]" value="true" <?php echo "REX_VALUE[2]" == 'true' ? ' checked="checked"' : ''; ?> style="float: right;" />
	</div>
	<div class="col-xs-8">
		Ähnlichkeitssuche aktivieren wenn keine Treffer gefunden werden?<br />
		<?php
			if(rex_config::get('search_it', 'similarwordsmode', 0) === 0) {
				print "<b>Die Ähnlichkeitssuche muss in den Search It Einstellungen aktiviert werden!</b>";
			}
		?>
	</div>
</div>
