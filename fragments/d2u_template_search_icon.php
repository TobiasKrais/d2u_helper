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

        $yform->setObjectparams('Error-occured', \Sprog\Wildcard::get('d2u_helper_module_form_validate_title'));
        $yform->setObjectparams('form_action', rex_getUrl($article_id_search));
        $yform->setObjectparams('form_anchor', 'search-field');
        $yform->setObjectparams('form_name', 'd2u_helper_search_box');
        $yform->setObjectparams('form_showformafterupdate', true);
        $yform->setObjectparams('hide_field_warning_messages', true);
        $yform->setObjectparams('hide_top_warning_messages', true);
        $yform->setObjectparams('real_field_names', true);
        $yform->setObjectparams('submit_btn_show', false);

        echo $yform->getForm();

        echo '<script>';
        echo '(function() {';
        echo '  var searchInput = document.querySelector(\'input[name="search"]\');';
        echo '  if (!searchInput) return;';
        echo '  var suggestBox = document.createElement("ul");';
        echo '  suggestBox.className = "search-it-suggest";';
        echo '  suggestBox.style.display = "none";';
        echo '  searchInput.parentNode.style.position = "relative";';
        echo '  searchInput.parentNode.appendChild(suggestBox);';
        echo '  var debounceTimer;';
        echo '  searchInput.addEventListener("input", function() {';
        echo '    clearTimeout(debounceTimer);';
        echo '    var val = searchInput.value;';
        echo '    if (val.length < 2) { suggestBox.style.display = "none"; return; }';
        echo '    debounceTimer = setTimeout(function() {';
        echo '      fetch("index.php?rex-api-call=search_it_autocomplete_getSimilarWords&rnd=" + Math.random() + "&term=" + encodeURIComponent(val))';
        echo '        .then(function(r) { return r.json(); })';
        echo '        .then(function(data) {';
        echo '          suggestBox.innerHTML = "";';
        echo '          if (data.length === 0) { suggestBox.style.display = "none"; return; }';
        echo '          data.forEach(function(item) {';
        echo '            var li = document.createElement("li");';
        echo '            li.textContent = item.label || item;';
        echo '            li.addEventListener("click", function() {';
        echo '              searchInput.value = item.label || item;';
        echo '              suggestBox.style.display = "none";';
        echo '              searchInput.closest("form").submit();';
        echo '            });';
        echo '            suggestBox.appendChild(li);';
        echo '          });';
        echo '          suggestBox.style.display = "block";';
        echo '        });';
        echo '    }, 300);';
        echo '  });';
        echo '  document.addEventListener("click", function(e) {';
        echo '    if (!searchInput.parentNode.contains(e.target)) suggestBox.style.display = "none";';
        echo '  });';
        echo '})();';
        echo '</script>';

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
