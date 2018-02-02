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
<h2>Hinweise</h2>
<p>Die Templates können ein Favicon einbinden. Hierzu einfach eine Datei
	favicon.ico in den Medienpool laden.</p>