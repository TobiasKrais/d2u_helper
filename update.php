<?php
// Update modules
if(class_exists(D2UModuleManager)) {
	$d2u_modules = [];
	$d2u_modules[] = new D2UModule("00-1",
		"Umbruch ganze Breite",
		2);
	$d2u_modules[] = new D2UModule("01-1",
		"Texteditor",
		4);
	$d2u_modules[] = new D2UModule("01-2",
		"Texteditor mit Bild und Überschrift",
		3);
	$d2u_modules[] = new D2UModule("02-1",
		"Ueberschrift",
		3);
	$d2u_modules[] = new D2UModule("03-1",
		"Bild",
		3);
	$d2u_modules[] = new D2UModule("03-2",
		"Bildergallerie Ekko Lightbox",
		3);
	$d2u_modules[] = new D2UModule("04-1",
		"Google Maps",
		3);
	$d2u_modules[] = new D2UModule("05-1",
		"Artikelweiterleitung",
		2);
	$d2u_modules[] = new D2UModule("06-1",
		"YouTube Video",
		2);
	$d2u_modules[] = new D2UModule("10-1",
		"Box mit Bild und Ueberschrift",
		1);
	$d2u_modules[] = new D2UModule("10-2",
		"Box mit Bild und Text",
		1);
	$d2u_module_manager = new D2UModuleManager($d2u_modules);
	$d2u_module_manager->autoupdate();
}

// Update templates
if(class_exists(D2UTemplateManager)) {
	$d2u_templates = [];
	$d2u_templates[] = new D2UTemplate("00-1",
		"Big Header Template",
		3);
	$d2u_templates[] = new D2UTemplate("01-1",
		"Side Picture Template",
		1);
	$d2u_templates[] = new D2UTemplate("02-1",
		"Header Pic Template",
		1);
	$d2u_templates[] = new D2UTemplate("99-1",
		"Feed Generator",
		1);
	$d2u_template_manager = new D2UTemplateManager($d2u_templates);
	$d2u_template_manager->autoupdate();
}

// Update standard settings
if (!$this->hasConfig('subhead_include_articlename')) {
	$this->setConfig('subhead_include_articlename', '"true"');
}
if (!$this->hasConfig('show_breadcrumbs')) {
	$this->setConfig('show_breadcrumbs', '"true"');
}