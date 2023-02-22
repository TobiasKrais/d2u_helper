<div class="row">
	<div class="col-xs-4">
		Weiterleitung zu ...
	</div>
	<div class="col-xs-8">
		<?php
        $select_link = new rex_select();
        $select_link->setName('REX_INPUT_VALUE[1]');
        $select_link->setSize(1);
        $select_link->setAttribute('class', 'form-control');
        $select_link->setAttribute('id', 'selector');

        $select_link->addOption('Redaxo Artikel', 'article');
        $select_link->addOption('Externer Link', 'link');
        $select_link->addOption('PDF Datei aus Medienpool', 'download');
        if (rex_addon::get('d2u_immo')->isAvailable()) {
            $select_link->addOption('D2U Immobilien Addon - Immobilie', 'd2u_immo_property');
        }
        if (rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
            $select_link->addOption('D2U Maschinen Addon - Branche', 'd2u_machinery_industry_sector');
        }
        if (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
            $select_link->addOption('D2U Maschinen Addon - Gebrauchtmaschine', 'd2u_machinery_used_machine');
        }
        if (rex_addon::get('d2u_machinery')->isAvailable()) {
            $select_link->addOption('D2U Maschinen Addon - Maschine', 'd2u_machinery_machine');
        }
        if (rex_addon::get('d2u_courses')->isAvailable()) {
            $select_link->addOption('D2U Veranstaltungen Addon - Kategorie', 'd2u_courses_category');
        }

        $select_link->setSelected('REX_VALUE[1]');

        echo $select_link->show();
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
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		Parameter ohne "?", Beispiel "param1=1&amp;param2=2" (optional)
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[8]" value="REX_VALUE[8]" class="form-control"/>
	</div>
	<div class="col-xs-12">&nbsp;</div>
	<div class="col-xs-4">
		Anker (optional)
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[7]" value="REX_VALUE[7]" class="form-control"/>
	</div>
</div>

<div class="row" id="link">
	<div class="col-xs-4">
		Link URL:
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" class="form-control"/>
	</div>
</div>

<div class="row" id="download">
	<div class="col-xs-4">
		Medienpool PDF Datei
	</div>
	<div class="col-xs-8">
		REX_MEDIA[id="1" types="pdf" widget="1"]
	</div>
</div>

<?php
// Machinery Addon - Machine
if (rex_addon::get('d2u_machinery')->isAvailable()) {
    echo '<div class="row" id="d2u_machinery_machine">';
    echo '<div class="col-xs-4">Zu welcher Maschine soll weitergeleitet werden?</div>';
    echo '<div class="col-xs-8">';
        $select_link = new rex_select();
        $select_link->setName('REX_INPUT_VALUE[3]');
        $select_link->setSize(1);
        $select_link->setAttribute('class', 'form-control');

        $machines = Machine::getAll(rex_clang::getCurrentId(), true);
        foreach ($machines as $machine) {
            $select_link->addOption($machine->name, $machine->machine_id);
        }

        $select_link->setSelected('REX_VALUE[3]');

        echo $select_link->show();
    echo '</div>';
    echo '</div>';
}
// Machinery Addon - Industry Sector
if (rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
    echo '<div class="row" id="d2u_machinery_industry_sector">';
    echo '<div class="col-xs-4">Zu welcher Branche soll weitergeleitet werden?</div>';
    echo '<div class="col-xs-8">';
        $select_link = new rex_select();
        $select_link->setName('REX_INPUT_VALUE[4]');
        $select_link->setSize(1);
        $select_link->setAttribute('class', 'form-control');

        $industry_sectors = IndustrySector::getAll(rex_clang::getCurrentId(), true);
        foreach ($industry_sectors as $industry_sector) {
            $select_link->addOption($industry_sector->name, $industry_sector->industry_sector_id);
        }

        $select_link->setSelected('REX_VALUE[4]');

        echo $select_link->show();
    echo '</div>';
    echo '</div>';
}
// Machinery Addon - Used machines
if (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
    echo '<div class="row" id="d2u_machinery_used_machine">';
    echo '<div class="col-xs-4">Zu welcher Gebrauchtmaschine soll weitergeleitet werden?</div>';
    echo '<div class="col-xs-8">';
        $select_link = new rex_select();
        $select_link->setName('REX_INPUT_VALUE[5]');
        $select_link->setSize(1);
        $select_link->setAttribute('class', 'form-control');

        $used_machines = UsedMachine::getAll(rex_clang::getCurrentId(), true);
        foreach ($used_machines as $used_machine) {
            $select_link->addOption($used_machine->name, $used_machine->used_machine_id);
        }

        $select_link->setSelected('REX_VALUE[5]');

        echo $select_link->show();
    echo '</div>';
    echo '</div>';
}
// Immo Addon - Properties
if (rex_addon::get('d2u_immo')->isAvailable()) {
    echo '<div class="row" id="d2u_immo_property">';
    echo '<div class="col-xs-4">Zu welcher Immobilie soll weitergeleitet werden?</div>';
    echo '<div class="col-xs-8">';
        $select_link = new rex_select();
        $select_link->setName('REX_INPUT_VALUE[6]');
        $select_link->setSize(1);
        $select_link->setAttribute('class', 'form-control');

        $properties = \D2U_Immo\Property::getAll(rex_clang::getCurrentId(), '', true);
        foreach ($properties as $property) {
            $select_link->addOption($property->name, $property->property_id);
        }

        $select_link->setSelected('REX_VALUE[6]');

        echo $select_link->show();
    echo '</div>';
    echo '</div>';
}
// Veranstaltungen Addon
if (rex_addon::get('d2u_courses')->isAvailable()) {
    // Categories
    echo '<div class="row" id="d2u_courses_category">';
    echo '<div class="col-xs-4">Zu welcher Veranstaltungskategorie soll weitergeleitet werden?</div>';
    echo '<div class="col-xs-8">';
        $select_link = new rex_select();
        $select_link->setName('REX_INPUT_VALUE[6]');
        $select_link->setSize(1);
        $select_link->setAttribute('class', 'form-control');

        $categories = \D2U_Courses\Category::getAll(true);
        foreach ($categories as $category) {
            $select_link->addOption(($category->parent_category ? ($category->parent_category->parent_category ? $category->parent_category->parent_category->name .' → ' : ''). $category->parent_category->name .' → ' : ''). $category->name, $category->category_id);
        }

        $select_link->setSelected('REX_VALUE[6]');

        echo $select_link->show();
    echo '</div>';
    echo '</div>';
}
?>

<script>
	function changeType() {
		if($('#selector').val() === "article") {
			$('#article').show();
			$('#link').hide();
			$('#download').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "link") {
			$('#article').hide();
			$('#link').show();
			$('#download').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "download") {
			$('#article').hide();
			$('#link').hide();
			$('#download').show();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "d2u_machinery_machine") {
			$('#article').hide();
			$('#link').hide();
			$('#download').hide();
			$('#d2u_machinery_machine').show();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "d2u_machinery_industry_sector") {
			$('#article').hide();
			$('#link').hide();
			$('#download').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').show();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "d2u_machinery_used_machine") {
			$('#article').hide();
			$('#link').hide();
			$('#download').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').show();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "d2u_immo_property") {
			$('#article').hide();
			$('#link').hide();
			$('#download').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').show();
			$('#d2u_courses_category').hide();
		}
		else if($('#selector').val() === "d2u_courses_category") {
			$('#article').hide();
			$('#link').hide();
			$('#download').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
			$('#d2u_courses_category').show();
		}
	}

	// On init
	changeType();

	// On change
	$('#selector').on('change', function() {
		changeType();
	});
</script>