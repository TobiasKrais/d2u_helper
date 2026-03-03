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
		<select name="REX_INPUT_VALUE[17]" class="form-control">
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
		offset_changer($("select[name='REX_INPUT_VALUE[20]']").val());
	});

	// Hide on selection change
	$("select[name='REX_INPUT_VALUE[20]']").on('change', function() {
		offset_changer($(this).val());
	});
</script>
<div class="row">
	<div class="col-xs-12"><div style="border-top: 1px darkgrey solid; margin: 1em 0;"></div></div>
</div>
<div class="row">
	<div class="col-xs-4">
		Button Text:
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[1]" value="REX_VALUE[1]" class="form-control"/>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Link zu ...
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
        $select_link->addOption('Datei aus Medienpool', 'media');

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
<div class="row" id="media">
	<div class="col-xs-4">
		Datei aus Medienpool
	</div>
	<div class="col-xs-8">
		REX_MEDIA[id="2" widget="2"]
	</div>
</div>
<script>
	function changeType() {
		if($('#selector').val() === "article") {
			$('#article').show();
			$('#link').hide();
			$('#media').hide();
		}
		else if($('#selector').val() === "link") {
			$('#article').hide();
			$('#link').show();
			$('#media').hide();
		}
		else if($('#selector').val() === "media") {
			$('#article').hide();
			$('#link').hide();
			$('#media').show();
		}
		else {
			$('#article').hide();
			$('#link').hide();
			$('#media').hide();
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
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Button Art:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[9]" class="form-control">
			<option value="primary"<?= 'REX_VALUE[9]' === 'secondary' ? '' : ' selected="selected"' /** @phpstan-ignore-line */ ?>>primary</option>
			<option value="secondary"<?= 'REX_VALUE[9]' === 'secondary' ? ' selected="selected"' : '' /** @phpstan-ignore-line */ ?>>secondary</option>
		</select>
	</div>
</div>
