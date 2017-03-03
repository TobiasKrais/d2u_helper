<?php
// Update modules
if(class_exists(D2UModuleManager)) {
	$d2u_modules = [];
	$d2u_modules[] = new D2UModule("00-1",
		"00-1 Umbruch ganze Breite - Input.php",
		"00-1 Umbruch ganze Breite - Output.php",
		"Umbruch ganze Breite",
		2);
	$d2u_modules[] = new D2UModule("01-1",
		"01-1 Texteditor - Input.php",
		"01-1 Texteditor - Output.php",
		"Texteditor",
		3);
	$d2u_modules[] = new D2UModule("01-2",
		"01-2 Texteditor mit Bild und Ueberschrift - Input.php",
		"01-2 Texteditor mit Bild und Ueberschrift - Output.php",
		"Texteditor mit Bild und Überschrift",
		3,
		"module-box/module-box.css");
	$d2u_modules[] = new D2UModule("02-1",
		"02-1 Ueberschrift - Input.php",
		"02-1 Ueberschrift - Output.php",
		"Ueberschrift",
		3);
	$d2u_modules[] = new D2UModule("03-1",
		"03-1 Bild - Input.php",
		"03-1 Bild - Output.php",
		"Bild",
		3);
	$d2u_modules[] = new D2UModule("03-2",
		"03-2 Bildergallerie Ekko Lightbox - Input.php",
		"03-2 Bildergallerie Ekko Lightbox - Output.php",
		"Bildergallerie Ekko Lightbox",
		3,
		"ekko-lightbox/ekko-lightbox.css",
		"ekko-lightbox/ekko-lightbox.min.js");
	$d2u_modules[] = new D2UModule("04-1",
		"04-1 Google Maps - Input.php",
		"04-1 Google Maps - Output.php",
		"Google Maps",
		3);
	$d2u_modules[] = new D2UModule("05-1",
		"05-1 Artikelweiterleitung - Input.php",
		"05-1 Artikelweiterleitung - Output.php",
		"Artikelweiterleitung",
		2);
	$d2u_modules[] = new D2UModule("06-1",
		"06-1 YouTube Video - Input.php",
		"06-1 YouTube Video - Output.php",
		"YouTube Video",
		2,
		"youtube-wrapper/youtube-wrapper.css");
	$d2u_module_manager = new D2UModuleManager($d2u_modules);
	$d2u_module_manager->autoupdate();
}

// Update templates
if(class_exists(D2UTemplateManager)) {
	$d2u_templates = [];
	$d2u_templates[] = new D2UTemplate("00-1",
		"template-default.php",
		"Big Header Template",
		2,
		"template-default.css",
		"<p>Empfohlene Addons: yrewrite</p>"
			. "<p>Unterstützte Addons: d2u_machinery</p>");
	$d2u_template_manager = new D2UTemplateManager($d2u_templates);
	$d2u_template_manager->autoupdate();
}

// Update standard settings
if (!$this->hasConfig(subhead_include_articlename)) {
	$this->setConfig('subhead_include_articlename', '"true"');
}