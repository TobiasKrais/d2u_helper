<div class="col-12 col-lg-8">
<?php
$request = rex_request('search', 'string', false);
$limit = 'REX_VALUE[1]' !== '' ? (int) 'REX_VALUE[1]' : 10; /** @phpstan-ignore-line */
$media_manager_type = 'REX_VALUE[3]' !== '' ? 'REX_VALUE[3]' : 'rex_mediapool_preview'; /** @phpstan-ignore-line */
$start = rex_request('start', 'int', 0);

?>

<section class="search_it-search">
	<a name="search-field"></a>
<?php
    if (rex_addon::get('yform_spam_protection')->isAvailable()) {
        $yform = new rex_yform();

        $form_data = 'text|search||||{"placeholder":"'. \Sprog\Wildcard::get('d2u_helper_module_14_enter_search_term') .'"}
				html|button||<button class="search_it_yform_button" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img"><path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path></svg></button>
				spam_protection|honeypot|Bitte nicht ausfüllen|'. \Sprog\Wildcard::get('d2u_helper_module_14_validate_spam_detected') .'|0'. PHP_EOL;
        $yform->setFormData(trim($form_data));

        $yform->setObjectparams('csrf_protection', false);
        $yform->setObjectparams('Error-occured', \Sprog\Wildcard::get('d2u_helper_module_form_validate_title'));
        $yform->setObjectparams('form_action', rex_getUrl());
        $yform->setObjectparams('form_anchor', 'search-field');
        $yform->setObjectparams('form_name', 'd2u_helper_search_box');
        $yform->setObjectparams('form_showformafterupdate', true);
        $yform->setObjectparams('real_field_names', true);
        $yform->setObjectparams('submit_btn_show', false);

        echo $yform->getForm();

        echo '<script>'. PHP_EOL;
        echo 'jQuery(document).ready(function() {'. PHP_EOL;
        echo 'jQuery(function() {'. PHP_EOL;
        echo 'jQuery(\'input[name="search"]\').suggest("index.php?rex-api-call=search_it_autocomplete&rnd=" + Math.random(),'. PHP_EOL;
        echo '{ onSelect: function(event, ui) { $("#formular").submit(); return false; }'. PHP_EOL;
        echo '});'. PHP_EOL;
        echo '});'. PHP_EOL;
        echo '});'. PHP_EOL;
        echo '</script>'. PHP_EOL;
    } else {
?>
		<form class="search_it-form" id="search_it-form1" action="<?= rex_getUrl() ?>#search-results" method="get">
			<div class="search_it-flex">
				<?= '<input type="text" id="search_it_search" name="search" value="'. ('' !== $request ? rex_escape($request) : '') .'" placeholder="'. \Sprog\Wildcard::get('d2u_helper_module_14_enter_search_term') .'" autofocus />';
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
if (((rex_addon::get('yform_spam_protection')->isAvailable() && 0 === count($yform->getObjectparams('warning'))) || !rex_addon::get('yform_spam_protection')->isAvailable()) && '' !== $request) { // Wenn ein Suchbegriff eingegeben wurde
    $server = rtrim(rex::getServer(), '/');
    $search_it_class = class_exists('FriendsOfRedaxo\\SearchIt\\SearchIt') ? 'FriendsOfRedaxo\\SearchIt\\SearchIt' : 'search_it';

    echo '<section class="search_it-hits">';

    // Init search and execute (only search articles and urls in current language)
    $search_it = new $search_it_class(rex_clang::getCurrentId());
    $search_it->setLimit($start, $limit);
    $search_it->setOrder(["field(texttype, 'url', 'article', 'file')" => 'ASC'], true);
    $result = $search_it->search(false !== $request ? $request : '');

    echo '<a name="search-results"></a>';
    echo '<h2 class="search_it-headline">'. \Sprog\Wildcard::get('d2u_helper_module_14_search_results') .'</h2>';
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
                if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($article_hit)) {
                    // get yrewrite article domain
                    $hit_server = $server;
                    if (rex_addon::get('yrewrite')->isAvailable()) {
                        $hit_domain = rex_yrewrite::getDomainByArticleId($hit['fid'], $hit['clang']);
                        $hit_server = rtrim($hit_domain->getUrl(), '/');
                    }

                    $hit_link = !str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . $article_hit->getUrl(['search_highlighter' => $request]) : $article_hit->getUrl(['search_highlighter' => $request]);
                    $hit_url_plain = !str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . $article_hit->getUrl() : $article_hit->getUrl();
                    $article_name = (string) $article_hit->getName();
                    echo '<li class="search_it-result search_it-article">';
                    echo '<span class="search_it-title"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($article_name, 'html_attr') .'">'. rex_escape($article_name) .'</a></span><br>';
                    // highlightedtext stammt aus search_it und enthaelt zulaessiges <mark>-Markup
                    echo '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>';
                    echo '<span class="search_it-url"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($article_name, 'html_attr') .'">'. rex_escape(urldecode($hit_url_plain)) .'</a></span>';
                    echo '</li>';
                }
            } elseif ('url' === $hit['type']) {
                // url hits
                $url_sql = rex_sql::factory();
                $url_sql->setQuery('SELECT * FROM '. rex::getTablePrefix() . \Url\UrlManagerSql::TABLE_NAME .' WHERE url_hash = :hash', ['hash' => (string) $hit['fid']]);

                // Check in case URL IDs changed
                if ($url_sql->getRows() > 0) {
                    $article_hit = rex_article::get((int) $url_sql->getValue('article_id'));
                    // Check article permission if YCom is used
                    if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($article_hit)) {
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
                        if (is_array($url_info)) {
                            $url_title = (string) ($url_info['title'] ?? '');
                            $url_image = (string) ($url_info['image'] ?? '');
                            $url_plain_target = rex_getUrl((int) $url_sql->getValue('article_id'), (int) $url_sql->getValue('clang_id'), [$url_profile->getNamespace() => $url_sql->getValue('data_id')]);
                            $url_plain = !str_contains($article_hit->getUrl(), $hit_server) ? $hit_server . $url_plain_target : $url_plain_target;
                            echo '<li class="search_it-result search_it-article">';
                            echo '<span class="search_it-title"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($url_title, 'html_attr') .'">'. rex_escape($url_title) .'</a></span><br>';
                            $image = '' !== $url_image ? '<span class="search_it-previewimage"><img src="'. rex_escape($hit_server .'/index.php?rex_media_type='. $media_manager_type .'&rex_media_file='. $url_image, 'html_attr') .'"></span>' : '';
                            // highlightedtext stammt aus search_it und enthaelt zulaessiges <mark>-Markup
                            echo $image . '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>';
                            echo '<span class="search_it-url"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($url_title, 'html_attr') .'">'. rex_escape(urldecode($url_plain)) .'</a></span>';
                            echo '</li>';
                        }
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
                        $media_title = (string) $media->getTitle();
                        $media_filename = (string) $media->getFileName();
                        echo '<li class="search_it-result search_it-image search_it-flex">';
                        echo '<span class="search_it-title"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($media_title, 'html_attr') .'">'. ('pdf' === pathinfo($hit['filename'], PATHINFO_EXTENSION) ? '<span class="icon pdf"></span>' : '');
                        echo '&nbsp;&nbsp;'. rex_escape($media_title) .'</a></span><br>';
                        $image = 'image' === substr($media->getType(), 0, 5) ? '<span class="search_it-previewimage"><img src="'. rex_escape('index.php?rex_media_type='. $media_manager_type .'&rex_media_file='. $media_filename, 'html_attr') .'"></span>' : '';
                        // highlightedtext stammt aus search_it und enthaelt zulaessiges <mark>-Markup
                        echo $image . ($hit['highlightedtext'] ? '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>' : '');
                        echo '<span class="search_it-url"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($media_title, 'html_attr') .'">'. rex_escape($hit_link) .'</a></span>';
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
                            $media_title = (string) $media->getTitle();
                            $media_filename = (string) $media->getFileName();
                            $title_or_name = '' !== $media_title ? $media_title : $media_filename;
                            echo '<li class="search_it-result search_it-image search_it-flex">';
                            echo '<span class="search_it-title"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($media_title, 'html_attr') .'">'. rex_escape($title_or_name) .'</a></span><br>';
                            $image = 'image' === substr($media->getType(), 0, 5) ? '<span class="search_it-previewimage"><img src="'. rex_escape($server .'/index.php?rex_media_type='. $media_manager_type .'&rex_media_file='. $media_filename, 'html_attr') .'"></span>' : '';
                            // highlightedtext stammt aus search_it und enthaelt zulaessiges <mark>-Markup
                            echo $image . ($hit['highlightedtext'] ? '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>' : '');
                            echo '<span class="search_it-url"><a href="'. rex_escape($hit_link, 'html_attr') .'" title="'. rex_escape($media_title, 'html_attr') .'">'. rex_escape($hit_link) .'</a></span>';
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
        echo '<p class="search_it-zero">'. \Sprog\Wildcard::get('d2u_helper_module_14_search_results_none') .'</p>';

        $activate_similarity_search = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */
        // Similarity search
        $search_it_sim = new $search_it_class(rex_clang::getCurrentId());
        $search_it_sim->setLimit(0, 1);
        if ($activate_similarity_search && rex_config::get('search_it', 'similarwordsmode', 0) > 0 && '' !== $result['simwordsnewsearch']) { /** @phpstan-ignore-line */
            $simwords_out = '<p>'. \Sprog\Wildcard::get('d2u_helper_module_14_search_similarity') .':<strong><ul>';
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