<?php
$article_id_search = (int) rex_config::get('d2u_helper', 'article_id_search', 0);
if (rex_addon::get('search_it')->isAvailable() && $article_id_search > 0 && $article_id_search !== rex_article::getCurrentId()) {
    if (rex_addon::get('yform_spam_protection')->isAvailable() && $this->getVar('showSearchField', false)) {
        // Show YForm search field
        echo '<div id="fragment-search">';
        $yform = new rex_yform();

        $form_data = 'text|search||||
				html|button||<button class="search_it_yform_button" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img"><path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path></svg></button>
				spam_protection|honeypot|Bitte nicht ausfüllen|'.  \Sprog\Wildcard::get('d2u_helper_module_14_validate_spam_detected') .'|0'. PHP_EOL;
        $yform->setFormData(trim($form_data));

        $yform->setObjectparams('csrf_protection', false);
        $yform->setObjectparams('Error-occured', \Sprog\Wildcard::get('d2u_helper_module_form_validate_title'));
        $yform->setObjectparams('form_action', rex_getUrl($article_id_search));
        $yform->setObjectparams('form_anchor', 'search-field');
        $yform->setObjectparams('form_name', 'd2u_helper_search_box_'. rand(1, 100));
        $yform->setObjectparams('form_showformafterupdate', true);
        $yform->setObjectparams('hide_field_warning_messages', true);
        $yform->setObjectparams('hide_top_warning_messages', true);
        $yform->setObjectparams('real_field_names', true);
        $yform->setObjectparams('submit_btn_show', false);

        echo $yform->getForm();

        if (rex_plugin::get('search_it', 'autocomplete')->isAvailable()) {
            echo '<script>';
            echo 'jQuery(document).ready(function() {';
            echo 'jQuery(function() {';
            echo 'jQuery(\'input[name="search"]\').suggest("index.php?rex-api-call=search_it_autocomplete_getSimilarWords&rnd=" + Math.random(),';
            echo '{ onSelect: function(event, ui) { $("#formular").submit(); return false; }';
            echo '});';
            echo '});';
            echo '});';
            echo '</script>';
        }

        echo '</div>';
    } else {
        ?>
		<form method="get" action="<?= rex_getUrl($article_id_search) ?>#search-field" class="search_it-formfragment">
			<button id="search_icon" type="submit">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img">
					<path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path>
				</svg>
			</button>
		</form>
		<?php
    }
}
