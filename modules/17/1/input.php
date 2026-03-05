<?php

use FriendsOfRedaxo\GooglePlaces\Place;

$places = Place::query()->find();
$options = [];
foreach ($places as $place) {
    $options[$place->getId()] = $place->getName();
}
?>
<div class="row">
	<div class="col-xs-4">
		Überschrift:
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" class="form-control"/>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Standort auswählen:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[1]" class="form-control">
		<?php
        foreach ($options as $key => $value) {
            echo '<option value="'. $key .'" ';
            if ((int) 'REX_VALUE[1]' === $key) { /** @phpstan-ignore-line */
                echo 'selected="selected" ';
            }
            echo '>'. rex_escape($value) .'</option>';
        }
        ?>
		</select>
	</div>
</div>
