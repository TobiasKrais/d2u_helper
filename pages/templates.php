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
			Fehler aufmerksam werden.</>
	</li>
<ul>