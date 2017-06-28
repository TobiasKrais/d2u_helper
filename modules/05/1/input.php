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

		$select_link->addOption("Redaxo Artikel", "article"); 
		$select_link->addOption("Externer Link", "link"); 
		if(rex_addon::get('d2u_immo')->isAvailable()) {
			$select_link->addOption("D2U Immobilien Addon - Immobilie", "d2u_immo_property");
		}
		if(rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
			$select_link->addOption("D2U Maschinen Addon - Branche", "d2u_machinery_industry_sector");
		}
		if(rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
			$select_link->addOption("D2U Maschinen Addon - Gebrauchtmaschine", "d2u_machinery_used_machine");
		}
		if(rex_addon::get('d2u_machinery')->isAvailable()) {
			$select_link->addOption("D2U Maschinen Addon - Maschine", "d2u_machinery_machine");
		}

		$select_link->setSelected("REX_VALUE[1]");

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
</div>

<div class="row" id="link">
	<div class="col-xs-4">
		Link URL:
	</div>
	<div class="col-xs-8">
		<input type="text" size="250" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" style="max-width: 100%"/>
	</div>
</div>

<?php
// Machinery Addon - Machine
if(rex_addon::get('d2u_machinery')->isAvailable()) {
	print '<div class="row" id="d2u_machinery_machine">';
	print '<div class="col-xs-4">Zu welcher Maschine soll weitergeleitet werden?</div>';
	print '<div class="col-xs-8">';
		$select_link = new rex_select(); 
		$select_link->setName('REX_INPUT_VALUE[3]'); 
		$select_link->setSize(1);
		$select_link->setAttribute('class', 'form-control');

		$machines = Machine::getAll(rex_clang::getCurrentId(), TRUE);
		foreach($machines as $machine)  {
			$select_link->addOption($machine->name, $machine->machine_id); 
		}

		$select_link->setSelected("REX_VALUE[3]");

		echo $select_link->show();
	print '</div>';
	print '</div>';
}
// Machinery Addon - Industry Sector
if(rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
	print '<div class="row" id="d2u_machinery_industry_sector">';
	print '<div class="col-xs-4">Zu welcher Branche soll weitergeleitet werden?</div>';
	print '<div class="col-xs-8">';
		$select_link = new rex_select(); 
		$select_link->setName('REX_INPUT_VALUE[4]'); 
		$select_link->setSize(1);
		$select_link->setAttribute('class', 'form-control');

		$industry_sectors = IndustrySector::getAll(rex_clang::getCurrentId(), TRUE);
		foreach($industry_sectors as $industry_sector)  {
			$select_link->addOption($industry_sector->name, $industry_sector->industry_sector_id); 
		}

		$select_link->setSelected("REX_VALUE[4]");

		echo $select_link->show();
	print '</div>';
	print '</div>';
}
// Machinery Addon - Used machines
if(rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
	print '<div class="row" id="d2u_machinery_used_machine">';
	print '<div class="col-xs-4">Zu welcher Gebrauchtmaschine soll weitergeleitet werden?</div>';
	print '<div class="col-xs-8">';
		$select_link = new rex_select(); 
		$select_link->setName('REX_INPUT_VALUE[5]'); 
		$select_link->setSize(1);
		$select_link->setAttribute('class', 'form-control');

		$used_machines = UsedMachine::getAll(rex_clang::getCurrentId(), TRUE);
		foreach($used_machines as $used_machine)  {
			$select_link->addOption($used_machine->name, $used_machine->used_machine_id); 
		}

		$select_link->setSelected("REX_VALUE[5]");

		echo $select_link->show();
	print '</div>';
	print '</div>';
}
// Immo Addon - Properties
if(rex_addon::get('d2u_immo')->isAvailable()) {
	print '<div class="row" id="d2u_immo_property">';
	print '<div class="col-xs-4">Zu welcher Immobilie soll weitergeleitet werden?</div>';
	print '<div class="col-xs-8">';
		$select_link = new rex_select(); 
		$select_link->setName('REX_INPUT_VALUE[6]'); 
		$select_link->setSize(1);
		$select_link->setAttribute('class', 'form-control');

		$properties = Property::getAll(rex_clang::getCurrentId(), '', TRUE);
		foreach($properties as $property)  {
			$select_link->addOption($property->name, $property->property_id); 
		}

		$select_link->setSelected("REX_VALUE[6]");

		echo $select_link->show();
	print '</div>';
	print '</div>';
}
?>

<script>
	function changeType() {
		if($('#selector').val() === "article") {
			$('#article').show();
			$('#link').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
		}
		else if($('#selector').val() === "link") {
			$('#article').hide();
			$('#link').show();
			$('#d2u_machinery_machine').show();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
		}
		else if($('#selector').val() === "d2u_machinery_machine") {
			$('#article').hide();
			$('#link').hide();
			$('#d2u_machinery_machine').show();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
		}
		else if($('#selector').val() === "d2u_machinery_industry_sector") {
			$('#article').hide();
			$('#link').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').show();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').hide();
		}
		else if($('#selector').val() === "d2u_machinery_used_machine") {
			$('#article').hide();
			$('#link').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').show();
			$('#d2u_immo_property').hide();
		}
		else if($('#selector').val() === "d2u_immo_property") {
			$('#article').hide();
			$('#link').hide();
			$('#d2u_machinery_machine').hide();
			$('#d2u_machinery_industry_sector').hide();
			$('#d2u_machinery_used_machine').hide();
			$('#d2u_immo_property').show();
		}
	}
	
	// On init
	changeType();
	
	// On change
	$('#selector').on('change', function() {
		changeType();
	});
</script>