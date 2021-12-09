<?php
$cols_sm = "REX_VALUE[20]";
if($cols_sm == "") {
	$cols_sm = 12;
}
$cols_md = "REX_VALUE[19]";
if($cols_md == "") {
	$cols_md = 12;
}
$cols_lg = "REX_VALUE[18]";
if($cols_lg == "") {
	$cols_lg = 8;
}
$offset_lg_cols = intval("REX_VALUE[17]");
$offset_lg = "";
if($offset_lg_cols > 0) {
	$offset_lg = " mr-lg-auto ml-lg-auto ";
}

$text_1 = 'REX_VALUE[id=1 output="html"]';
$show_text_2 = "REX_VALUE[2]" == 'true' ? TRUE : FALSE;
$text_2 = 'REX_VALUE[id=3 output="html"]';

print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
if ($text_1) {
	print '<div class="wysiwyg_content">';
	print d2u_addon_frontend_helper::prepareEditorField($text_1);
	print '</div>';
}
if ($show_text_2 && $text_2) {
	$id = rand();
	print '<div class="wysiwyg_content">';
	print '<button id="button_'. $id .'" class="text-toggler angle-down" onclick="toggle_text_'. $id .'()">'. \Sprog\Wildcard::get('d2u_helper_modules_show_more') .'</button>';
	print '<div id="second_text_'. $id .'" class="hide-text">';
	print d2u_addon_frontend_helper::prepareEditorField($text_2);	
	print '</div>';
	print '</div>';

	print '<script>';
	print 'function toggle_text_'. $id .'() {'. PHP_EOL;
		print '$("#second_text_'. $id .'").slideToggle();'. PHP_EOL;
		print 'if($("#button_'. $id .'").hasClass("angle-down")) {';
			print '$("#button_'. $id .'").fadeOut(500, function() { $(this).html("'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_less')) .'").removeClass("angle-down").addClass("angle-up").fadeIn(500); });';
		print '}'. PHP_EOL;
		print 'else {';
			print '$("#button_'. $id .'").fadeOut(500, function() { $(this).html("'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_more')) .'").removeClass("angle-up").addClass("angle-down").fadeIn(500); });';
		print '}'. PHP_EOL;
	print '}'. PHP_EOL;
	print '</script>';
}
print '</div>';