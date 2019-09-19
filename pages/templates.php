<?php
$d2u_template_manager = new D2UTemplateManager(D2UTemplateManager::getD2UHelperTemplates());

// D2UTemplateManager actions
$d2u_template_id = rex_request('d2u_template_id', 'string');
$paired_template = rex_request('pair_'. $d2u_template_id, 'int');
$function = rex_request('function', 'string');
if($d2u_template_id != "") {
	$d2u_template_manager->doActions($d2u_template_id, $function, $paired_template);
}

// D2UTemplateManager show list
$d2u_template_manager->showManagerList();
?>
<h2>FAQ</h2>
<ul>
	<li><b>Wie kann ein Favicon für die Templates eingebunden werden?</b><br>
		Die Templates können ein Favicon einbinden. Hierzu einfach eine Datei
		favicon.ico in den Medienpool laden.</li>
	<li><b>Wo wird in den Templates das Impressum / die Links im Footer ausgegeben?</b><br>
		Im Footer werden die Artikel der Root Kategorie aufgelistet. Einfach
			einen Artikel mit dem Impressum in der Root Kategorie anlegen. Der
			Artikel muss online sein. Tipp: das Impressum und auch die
			Datenschutzerklärung sollten aus dem Webseitenindex entfernt werden,
			damit Abmahnanwälte durch eine Google Suche nicht auf eventuelle
			Fehler aufmerksam werden.</li>
	<li><b>Wie groß müssen die Bilder für die Slider / Headerbilder sein?</b><br>
		Es ist keine fixe Größe vorgesehen. Die Bilder werden bei Bedarf gedehnt.
		Einfach ausprobieren.</li>
</ul>
<h2>Beispielseiten</h2>
<ul>
	<li><b>00-1	Big Header Template</b><br>
		Template eignet sich für das D2U Maschinen und D2U Veranstaltungen Addon, funktioniert aber auch ohne diese Addons.<br>
		<a href="https://www.aberer-automobile.de/" target="_blank">Aberer Automobile - Ihr Renault und Dacia Partner in Lörrach</a><br>
		<a href="https://www.justjazz.net/" target="_blank">Aberer Automobile - Ihr Renault und Dacia Partner in Lörrach</a><br>
		<a href="https://www.design-to-use.de/" target="_blank">design-to-use.de - Benutzerfreundliches Webdesign</a><br>
	</li>
	<li><b>01-1	Side Picture Template</b><br>
		<a href="http://www.staub-schoene-zaehne.de/" target="_blank">Staub Zahntechnik - Schöne Zähne</a><br>
		<a href="http://www.optik-muelhaupt.de/" target="_blank">Optik Mülhaupt</a><br>
	</li>
	<li><b>02-1	Header Pic Template</b><br>
		<a href="https://www.cr-vision.de/" target="_blank">CR-VISION - Highspeed Marketingkommunikation</a><br>
		<a href="https://erfolgreich-im-gesundheitswesen.de/" target="_blank">Erfolgreich im Gesundheitswesen - Annette Götzel</a><br>
		<a href="https://gewert-consulting.de/" target="_blank">Gewert Consulting</a><br>
	</li>
	<li><b>03-1	Immo Template - 2 Columns</b><br>
		Template eignet sich für das D2U Immo Addon, funktioniert aber auch ohne das Addon.<br>
		<a href="https://www.immobiliengaiser.de/" target="_blank">Immobilien Gaiser</a><br>
	</li>
	<li><b>03-2	Immo Window Advertising Template</b><br>
		Template benötigt das D2U Immo Addon.<br>
		<a href="https://www.immobiliengaiser.de/immobilien/schaufensteransicht/" target="_blank">Immobilien Gaiser - Schaufensterangebote</a><br>
	</li>
	<li><b>04-1	Header Slider Template with Slogan</b><br>
		Template eignet sich für das D2U Maschinen und D2U Veranstaltungen Addon, funktioniert aber auch ohne diese Addons.<br>
		<a href="https://meier-krantechnik.de/" target="_blank">Meier Krantechnik</a><br>
	</li>
	<li><b>04-2 Header Slider Template</b><br>
		Template eignet sich für das D2U Maschinen und D2U Veranstaltungen Addon, funktioniert aber auch ohne diese Addons.<br>
		<a href="https://www.inotec-gmbh.com/" target="_blank">Inotec GmbH - Wir machen Ihre Baustelle leichter!</a><br>
		<a href="https://sit-connect.de/" target="_blank">SIT Fassadenplanung - Visionen von Gebäudehüllen sind unser Metier</a><br>
	</li>
	<li><b>04-3 Header Slider Template with news column</b><br>
		Template eignet sich für das D2U Maschinen und D2U Veranstaltungen Addon, funktioniert aber auch ohne diese Addons. Für die News Spalte wird das D2U News Addon vorausgesetzt.<br>
		<a href="https://propatient-beratungsstelle.de/" target="_blank">Pro-Patient - Beratungsstelle für Betroffene bei misslungener Schönheits-OP</a><br>
	</li>
	<li><b>05-1	Double Logo Template</b><br>
		<a href="https://kita-am-baechle.de/" target="_blank">KiTa am Bächle</a><br>
		<a href="https://kita-ideenreich.de/" target="_blank">KiTa Ideenreich - Die Roche Kindertagesstätte</a><br>
	</li>
	<li><b>06-1	Paper Sheet Template</b><br>
		<a href="https://egh-hauingen.de/" target="_blank">Elektrizitäts-Genossenschaft Hauingen e.G.</a><br>
	</li>
	<li><b>99-1	Feed Generator</b><br>
		<a href="https://gewert-consulting.de/feed/" target="_blank">Gewert Consulting Blog Feed</a><br>
	</li>
</ul>