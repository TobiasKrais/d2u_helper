<div class="row">
	<div class="col-xs-12">
		Dieser Block erzeugt einen Umbruch. Alle nachfolgenden Elemente werden
		unterhalb des Umbruches dargestellt.
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Soll eine Linie zur Abgrenzung eingefügt werden?
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[1]" class="form-control">
			<?php
                $line = 'REX_VALUE[1]';
                echo '<option value="">ohne Linie</option>';
                echo '<option value="line"'. ('line' === $line ? ' selected="selected"' : '') .'>mit Linie</option>'; /** @phpstan-ignore-line */
            ?>
		</select>
	</div>
</div>
<?php
$current_article = rex_article::getCurrent();
if ($current_article instanceof rex_article) {
    $current_template = new rex_template($current_article->getTemplateId());
    if (in_array(str_replace('d2u_', '', null !== $current_template->getKey() ? $current_template->getKey() : ''), ['00-1', '02-1', '04-1', '04-2'], true)) {
?>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[2]" value="true" <?= 'REX_VALUE[2]' === 'true' ? ' checked="checked"' : '' /** @phpstan-ignore-line */ ?> class="form-control d2u_helper_toggle" />
	</div>
	<div class="col-xs-8">
		Bootstrap Container schließen und neuen Bootstrap Container beginnen<br />
	</div>
</div>

<div class="row">
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		<input type="checkbox" name="REX_INPUT_VALUE[3]" value="true" <?= 'REX_VALUE[3]' === 'true' ? ' checked="checked"' : '' /** @phpstan-ignore-line */ ?> class="form-control d2u_helper_toggle" />
	</div>
	<div class="col-xs-8">
		Neuen Container Fluid, also gesamte Breite des Browserfensters, setzen?<br />
	</div>
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		Neuem Container CSS Klasse zuweisen:<br />
	</div>
	<div class="col-xs-8">
		<input type="text" style="width:100%" name="REX_INPUT_VALUE[4]" value="REX_VALUE[4]" class="form-control"/>
	</div>
</div>
<script>
	function container_changer() {
		if ($("input[name='REX_INPUT_VALUE[2]']").is(':checked')) {
			$("input[name='REX_INPUT_VALUE[3]']").parent().parent().slideDown();
			$("input[name='REX_INPUT_VALUE[4]']").parent().parent().slideDown();
		}
		else {
			$("input[name='REX_INPUT_VALUE[3]']").parent().parent().slideUp();
			$("input[name='REX_INPUT_VALUE[4]']").parent().parent().slideUp();
		}
	}

	// Hide on document load
	$(document).ready(function() {
		container_changer();
	});

	// Hide on selection change
	$("input[name='REX_INPUT_VALUE[2]']").on('change', function(e) {
		container_changer();
	});
</script>
<?php
    }
}
