<?php

$footer_type = rex_config::get('d2u_helper', 'footer_type', '');

$fragment = new rex_fragment();
if ('box' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_box.php');
} elseif ('box_logo' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_box_logo.php');
} elseif ('links_address_contact_logo' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_links_address_contact_logo.php');
} elseif ('simple_contact_links' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_simple_contact_links.php');
} elseif ('links_logo_address' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_links_logo_address.php');
} elseif ('links_text' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_links_text.php');
} elseif ('text' === $footer_type) {
    echo $fragment->parse('d2u_template_footer_text.php');
} elseif (str_starts_with($footer_type, 'bs5_')) {
    echo $fragment->parse('d2u_template_bs5_footer.php');
}
