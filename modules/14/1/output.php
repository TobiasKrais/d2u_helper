<div class="col-12 col-lg-8">
<?php
$request = rex_request('search', 'string', false);
$limit = 'REX_VALUE[1]' !== '' ? (int) 'REX_VALUE[1]' : 10; /** @phpstan-ignore-line */
$media_manager_type = 'REX_VALUE[3]' !== '' ? 'REX_VALUE[3]' : 'rex_mediapool_preview'; /** @phpstan-ignore-line */
$start = rex_request('start', 'int', 0);

// Get placeholder wildcard tags
$sprog = rex_addon::get('sprog');
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');
?>

<section class="search_it-search">
	<a name="search-field"></a>
<?php
    if (rex_addon::get('yform_spam_protection')->isAvailable()) {
        $yform = new rex_yform();

        $form_data = 'text|search||||{"placeholder":"'. \Sprog\Wildcard::get('d2u_helper_module_14_enter_search_term') .'"}
				html|button||<button class="search_it_yform_button" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img"><path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path></svg></button>
				spam_protection|honeypot|Bitte nicht ausfüllen|'. $tag_open .'d2u_helper_module_14_validate_spam_detected'. $tag_close .'|0'. PHP_EOL;
        $yform->setFormData(trim($form_data));

        $yform->setObjectparams('csrf_protection', false);
        $yform->setObjectparams('Error-occured', \Sprog\Wildcard::get('d2u_helper_module_form_validate_title'));
        $yform->setObjectparams('form_action', rex_getUrl());
        $yform->setObjectparams('form_anchor', 'search-field');
        $yform->setObjectparams('form_name', 'd2u_helper_module_14_1_'. rand(1, 100));
        $yform->setObjectparams('form_showformafterupdate', true);
        $yform->setObjectparams('real_field_names', true);
        $yform->setObjectparams('submit_btn_show', false);

        echo $yform->getForm();

        if (rex_plugin::get('search_it', 'autocomplete')->isAvailable()) {
            echo '<script>'. PHP_EOL;
            echo 'jQuery(document).ready(function() {'. PHP_EOL;
            echo 'jQuery(function() {'. PHP_EOL;
            echo 'jQuery(\'input[name="search"]\').suggest("index.php?rex-api-call=search_it_autocomplete_getSimilarWords&rnd=" + Math.random(),'. PHP_EOL;
            echo '{ onSelect: function(event, ui) { $("#formular").submit(); return false; }'. PHP_EOL;
            echo '});'. PHP_EOL;
            echo '});'. PHP_EOL;
            echo '});'. PHP_EOL;
            echo '</script>'. PHP_EOL;
        }
    } else {
?>
		<form class="search_it-form" id="search_it-form1" action="<?= rex_getUrl() ?>#search-results" method="get">
			<div class="search_it-flex">
				<?= '<input type="text" id="search_it_search" name="search" value="'. ($request !== '' ? rex_escape($request) : '') .'" placeholder="'. $tag_open .'d2u_helper_module_14_enter_search_term'. $tag_close .'" autofocus />';
                ?>
				<button class="search_it-button" type="submit">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img"><path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path></svg>
				</button>
			</div>
		</form>
<?php
    }
?>
</section>

<?php
if (((rex_addon::get('yform_spam_protection')->isAvailable() && 0 === count($yform->getObjectparams('warning'))) || !rex_addon::get('yform_spam_protection')->isAvailable()) && $request !== '') { // Wenn ein Suchbegriff eingegeben wurde
    $server = rtrim(rex::getServer(), '/');

    echo '<section class="search_it-hits">';

    // Init search and execute (only search articles and urls in current language)
    $search_it = new search_it(rex_clang::getCurrentId());
    $search_it->setLimit($start, $limit);
    $search_it->setOrder(["field(texttype, 'url', 'article', 'file')" => 'ASC'], true);
    $result = $search_it->search($request !== false ? $request : '');

    echo '<a name="search-results"></a>';
    echo '<h2 class="search_it-headline">'. $tag_open .'d2u_helper_module_14_search_results'. $tag_close .'</h2>';
    if ((int) $result['count'] > 0) {
        // Pagination
        $pagination = '';
        if ($result['count'] > $limit) {
            $pagination = '<ul class="pagination">';
            for ($i = 0; ($i * $limit) < $result['count']; ++$i) {
                if (($i * $limit) === $start) {
                    $pagination .= '<li class="current">'. ($i + 1) .'</li>';
                } else {
                    $pagination .= '<li><a href="'. rex_getUrl(rex_article::getCurrentId(), rex_clang::getCurrentId(), ['search' => $request, 'start' => $i * $limit]) .'">'. ($i + 1) .'</a></li>';
                }
            }
            $pagination .= '</ul><br>';
        }
        echo $pagination;

        echo '<ul class="search_it-results">';
        foreach ($result['hits'] as $hit) {
            if ('article' === $hit['type']) {
                // article hits
                $article_hit = rex_article::get($hit['fid']);
                // Check article permission if YCom is used
                if (false === rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($article_hit))) {
                    // get yrewrite article domain
                    $hit_server = $server;
                    if (rex_addon::get('yrewrite')->isAvailable()) {
                        $hit_domain = rex_yrewrite::getDomainByArticleId($hit['fid'], $hit['clang']);
                        $hit_server = rtrim($hit_domain->getUrl(), '/');
                    }

                    $hit_link = !str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . $article_hit->getUrl(['search_highlighter' => $request]) : $article_hit->getUrl(['search_highlighter' => $request]);
                    echo '<li class="search_it-result search_it-article">';
                    echo '<span class="search_it-title"><a href="'. $hit_link .'" title="'. $article_hit->getName() .'">'. $article_hit->getName() .'</a></span><br>';
                    echo '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>';
                    echo '<span class="search_it-url"><a href="'. $hit_link .'" title="'. $article_hit->getName() .'">'. urldecode(!str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . $article_hit->getUrl() : $article_hit->getUrl()) .'</a></span>';
                    echo '</li>';
                }
            } elseif ('url' === $hit['type']) {
                // url hits
                $url_sql = rex_sql::factory();
                $url_sql->setQuery('SELECT * FROM '. rex::getTablePrefix() . \Url\UrlManagerSql::TABLE_NAME .' WHERE url_hash = "'. $hit['fid'] .'"');

                // Check in case URL IDs changed
                if ($url_sql->getRows() > 0) {
                    $article_hit = rex_article::get((int) $url_sql->getValue('article_id'));
                    // Check article permission if YCom is used
                    if (false === rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($article_hit))) {
                        $url_info = json_decode((string) $url_sql->getValue('seo'), true);
                        $url_profile = \Url\Profile::get((int) $url_sql->getValue('profile_id'));

                        // get yrewrite article domain
                        $hit_server = $server;
                        if (rex_addon::get('yrewrite')->isAvailable()) {
                            $hit_domain = rex_yrewrite::getDomainByArticleId($url_sql->getValue('article_id'), $url_sql->getValue('clang_id'));
                            $hit_server = rtrim($hit_domain->getUrl(), '/');
                        }

                        $hit_link_unproved = rex_getUrl((int) $url_sql->getValue('article_id'), (int) $url_sql->getValue('clang_id'), [$url_profile->getNamespace() => $url_sql->getValue('data_id'), 'search_highlighter' => $request]);
                        $hit_link = (!str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . $hit_link_unproved : $hit_link_unproved);
                        echo '<li class="search_it-result search_it-article">';
                        echo '<span class="search_it-title"><a href="'. $hit_link .'" title="'. $url_info['title'] .'">'. $url_info['title'] .'</a></span><br>';
                        $image = $url_info['image'] !== '' ? '<span class="search_it-previewimage"><img src="'. $hit_server .'/index.php?rex_media_type='. $media_manager_type .'&rex_media_file='. $url_info['image'] .'"></span>' : '';
                        echo $image . '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>';
                        echo '<span class="search_it-url"><a href="'. $hit_link .'" title="'. $url_info['title'] .'">'. urldecode(!str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . rex_getUrl((int) $url_sql->getValue('article_id'), (int) $url_sql->getValue('clang_id'), [$url_profile->getNamespace() => $url_sql->getValue('data_id')]) : rex_getUrl((int) $url_sql->getValue('article_id'), (int) $url_sql->getValue('clang_id'), [$url_profile->getNamespace() => $url_sql->getValue('data_id')])) .'</a></span>';
                        echo '</li>';
                    }
                }
            } elseif ('file' === $hit['type']) {
                // media hits
                $media = rex_media::get(pathinfo($hit['filename'], PATHINFO_BASENAME));
                if (is_object($media)) {
                    $has_permission = true;
                    if (rex_plugin::get('ycom', 'media_auth')->isAvailable()) {
                        $has_permission = rex_ycom_media_auth::checkPerm(rex_media_manager::create('', pathinfo($hit['filename'], PATHINFO_BASENAME)));
                    }
                    if ($has_permission) {
                        $hit_link = $server . rex_url::media($media->getFileName());
                        echo '<li class="search_it-result search_it-image search_it-flex">';
                        echo '<span class="search_it-title"><a href="'. $hit_link .'" title="'. $media->getTitle() .'">'. ('pdf' === pathinfo($hit['filename'], PATHINFO_EXTENSION) ? '<span class="icon pdf"></span>' : '');
                        echo '&nbsp;&nbsp;'. $media->getTitle() .'</a></span><br>';
                        $image = 'image' === substr($media->getType(), 0, 5) ? '<span class="search_it-previewimage"><img src="index.php?rex_media_type='. $media_manager_type .'&rex_media_file='. $media->getFileName() .'"></span>' : '';
                        echo $image . ($hit['highlightedtext'] ? '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>' : '');
                        echo '<span class="search_it-url"><a href="'. $hit_link .'" title="'. $media->getTitle() .'">'. $hit_link .'</a></span>';
                        echo '</li>';
                    }
                }
            } elseif ('db_column' === $hit['type']) {
                // picture hits
                if ($hit['table'] === rex::getTablePrefix() .'media' && isset($hit['values']['filetype']) && 'image' === substr($hit['values']['filetype'], 0, 5)) {
                    $media = rex_media::get($hit['values']['filename']);
                    if (is_object($media)) {
                        $has_permission = false;
                        if (rex_plugin::get('ycom', 'media_auth')->isAvailable()) {
                            $has_permission = rex_ycom_media_auth::checkPerm(rex_media_manager::create('', $media->getFileName()));
                        }
                        if ($has_permission) {
                            $hit_link = $server . rex_url::media($media->getFileName());
                            echo '<li class="search_it-result search_it-image search_it-flex">';
                            echo '<span class="search_it-title"><a href="'. $hit_link .'" title="'. $media->getTitle() .'">'. ($media->getTitle() !== '' ? $media->getTitle() : $media->getFileName()) .'</a></span><br>';
                            $image = 'image' === substr($media->getType(), 0, 5) ? '<span class="search_it-previewimage"><img src="'. $server .'/index.php?rex_media_type='. $media_manager_type .'&rex_media_file='. $media->getFileName() .'"></span>' : '';
                            echo $image . ($hit['highlightedtext'] ? '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>' : '');
                            echo '<span class="search_it-url"><a href="'. $hit_link .'" title="'. $media->getTitle() .'">'. $hit_link .'</a></span>';
                            echo '</li>';
                        }
                    }
                }
                // other tables
            }

                // other hit types

        }
        echo '</ul><br>';

        // Pagination
        echo $pagination;
    } elseif ($result['count'] > 0) {
        echo '<p class="search_it-zero">'. $tag_open .'d2u_helper_module_14_search_results_none'. $tag_close .'</p>';

        $activate_similarity_search = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */
        // Similarity search
        $search_it_sim = new search_it(rex_clang::getCurrentId());
        $search_it_sim->setLimit(0, 1);
        if ($activate_similarity_search && rex_config::get('search_it', 'similarwordsmode', 0) > 0 && $result['simwordsnewsearch'] !== '') { /** @phpstan-ignore-line */
            $simwords_out = '<p>'. $tag_open .'d2u_helper_module_14_search_similarity'. $tag_close .':<strong><ul>';
            $sim_counter = 0;
            foreach (explode(' ', trim($result['simwordsnewsearch'])) as $new_search_word) {
                $result_simwords = $search_it_sim->search(trim($new_search_word));
                if ($result_simwords['count'] > 0) {
                    $simwords_out .= '<li><a href="'. rex_getUrl(rex_article::getCurrentId(), rex_clang::getCurrentId(), ['search' => $new_search_word]) .'">'. $new_search_word .'</a></li>';
                    ++$sim_counter;
                    if ($sim_counter >= 10) {
                        break;
                    }
                }
            }
            $simwords_out .= '</ul></strong></p>';
            if ($sim_counter > 0) {
                echo $simwords_out;
            }
        }
    }
    echo '</section>';
}
?>
</div>