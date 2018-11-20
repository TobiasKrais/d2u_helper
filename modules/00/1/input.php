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
				$line = "REX_VALUE[1]";
				print '<option value="">ohne Linie</option>';
				print '<option value="line"'. ($line == "line" ? ' selected="selected"' : '') .'>mit Linie</option>';
			?>
		</select>
	</div>
</div>