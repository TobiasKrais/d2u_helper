<?php
$line = "REX_VALUE[1]" == "line" ? true : false;
$container_new = "REX_VALUE[2]" == "true" ? true : false;
$container_fluid = "REX_VALUE[3]" == "true" ? true : false;

$current_template = new rex_template(rex_article::getCurrent()->getTemplateId());
$compatible_template = (in_array(str_replace('d2u_', '', $current_template->getKey()), ['00-1', '02-1', '04-1', '04-2']));
if(\rex::isBackend()) {
	print "Umbruch ". ($line ? 'mit' : 'ohne') ." Linie";
	if($container_new && $compatible_template) {
		print "Neuen Container beginnen: ". ($container_new ? 'Ja' : 'Nein') ."<br>";
		print "Neuen Container Fluid setzen (komplette Bildschirmbreite): ". ($container_fluid ? 'Ja' : 'Nein') ."<br>";
		print "CSS Klasse des neuen Containers: REX_VALUE[3]";
	}
}
else {
	if($container_new && $compatible_template) {
		print '</div>';
		print '</div>';
		print '<div class="container'. ($container_fluid == "true" ? '-fluid' : '') . ("REX_VALUE[4]" != "" ? ' REX_VALUE[4]' : '') .'">';
		print '<div class="row">';
	}
	
	print '<div class="col-12">';
	print '<div class="spacer'. ($line ? ' line' : '') .'">';
	print '</div>';
	print '</div>';
}