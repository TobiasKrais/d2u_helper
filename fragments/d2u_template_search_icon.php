<?php
$article_id_search = rex_config::get('d2u_helper', 'article_id_search', 0);
if(rex_addon::get('search_it')->isAvailable() && $article_id_search > 0 && $article_id_search != rex_article::getCurrentId()) {
	if(rex_addon::get('yform_spam_protection')->isAvailable()  && $this->getVar('showSearchField', false)) {
		// Show YForm search field
		print '<div id="fragment-search">';
		$yform = new rex_yform();

		$form_data = 'text|search||||
				html|button||<button class="search_it_yform_button" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img"><path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path></svg></button>
				spam_protection|honeypot|Bitte nicht ausfüllen|'.  \Sprog\Wildcard::get('d2u_helper_module_14_validate_spam_detected') .'|0'. PHP_EOL;					
		$yform->setFormData(trim($form_data));

		$yform->setObjectparams("submit_btn_show", false);
		$yform->setObjectparams("form_action", rex_getUrl($article_id_search));
		$yform->setObjectparams("form_anchor", "search-field");
		$yform->setObjectparams("Error-occured", \Sprog\Wildcard::get('d2u_helper_module_11_validate_title'));
		$yform->setObjectparams("real_field_names", TRUE);
		$yform->setObjectparams("form_showformafterupdate", true);
		$yform->setObjectparams("hide_top_warning_messages", true);
		$yform->setObjectparams("hide_field_warning_messages", true);

		echo $yform->getForm();

		if(rex_plugin::get('search_it', 'autocomplete')->isAvailable()) {
			print '<script>';
			print 'jQuery(document).ready(function() {';
			print 'jQuery(function() {';
			print 'jQuery(\'input[name="search"]\').suggest("index.php?rex-api-call=search_it_autocomplete_getSimilarWords&rnd=" + Math.random(),';
			print '{ onSelect: function(event, ui) { $("#formular").submit(); return false; }';
			print '});';
			print '});';
			print '});';
			print '</script>';
		}

		print '</div>';
	}
	else {
		?>
		<form method="get" action="<?php print rex_getUrl($article_id_search); ?>#search-field" class="search_it-formfragment">
			<button id="search_icon" type="submit">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img">
					<path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path>
				</svg>
			</button>
		</form>
		<?php
	}
}