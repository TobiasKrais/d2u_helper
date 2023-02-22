<?php

$d2u_helper = rex_addon::get('d2u_helper');
if ($d2u_helper->hasConfig('template_print_header_pic')) {
    $d2u_helper->removeConfig('template_print_header_pic');
}
if ($d2u_helper->hasConfig('template_print_footer_pic')) {
    $d2u_helper->removeConfig('template_print_footer_pic');
}
