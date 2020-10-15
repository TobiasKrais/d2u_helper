<?php
$footer_type = rex_config::get('d2u_helper', 'footer_type', '');

$fragment = new rex_fragment();
if ($footer_type === 'box') {
	echo $fragment->parse('d2u_template_footer_box.php');
}
else if ($footer_type === 'box_logo') {
	echo $fragment->parse('d2u_template_footer_box_logo.php');
}
else if ($footer_type === 'links_address_contact_logo') {
	echo $fragment->parse('d2u_template_footer_links_address_contact_logo.php');
}
else if ($footer_type === 'links_logo_address') {
	echo $fragment->parse('d2u_template_footer_links_logo_address.php');
}
else if ($footer_type === 'links_text') {
	echo $fragment->parse('d2u_template_footer_links_text.php');
}
else if ($footer_type === 'text') {
	echo $fragment->parse('d2u_template_footer_text.php');
}