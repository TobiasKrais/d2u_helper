<?php

$footer_type = rex_config::get('d2u_helper', 'footer_type', '');

$fragment = new rex_fragment();
if ('bs5_box' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_box.php');
} elseif ('bs5_box_logo' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_box_logo.php');
} elseif ('bs5_links_address_contact_logo' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_links_address_contact_logo.php');
} elseif ('bs5_simple_contact_links' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_simple_contact_links.php');
} elseif ('bs5_links_logo_address' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_links_logo_address.php');
} elseif ('bs5_links_text' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_links_text.php');
} elseif ('bs5_text' === $footer_type) {
    echo $fragment->parse('d2u_template_bs5_footer_text.php');
}
