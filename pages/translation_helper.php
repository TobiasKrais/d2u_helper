<?php

// Set Session
if ('' === rex_session('d2u_helper_translation')) {
    $default_settings = ['clang_id' => rex_clang::getStartId(), 'filter' => 'update'];
    rex_request::setSession('d2u_helper_translation', $default_settings);
}

// Save form in session
if ('save' === filter_input(INPUT_POST, 'btn_save')) {
    $settings = rex_post('settings', 'array', []);
    rex_request::setSession('d2u_helper_translation', $settings);
}

?>

<h2><?= rex_i18n::msg('d2u_helper_meta_translations') ?></h2>
<p><?= rex_i18n::msg('d2u_helper_translations_description') ?></p>

<?php
if (1 === count(rex_clang::getAll())) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_translations_none'));
} else {
    $source_clang_id = (int) rex_config::get('d2u_helper', 'default_lang');
    $target_clang_id = is_array(rex_session('d2u_helper_translation')) && array_key_exists('clang_id', rex_session('d2u_helper_translation')) ? (int) rex_session('d2u_helper_translation')['clang_id'] : rex_clang::getStartId();
    $filter_type = is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) ? (string) rex_session('d2u_helper_translation')['filter'] : 'update';
?>
	<form action="<?= rex_url::currentBackendPage() ?>" method="post">
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_helper_translations_filter') ?></div></header>
			<div class="panel-body">
				<?php
                    // Language selection
                    $lang_options = [];
                    if (count(rex_clang::getAll()) > 1) {
                        foreach (rex_clang::getAll() as $rex_clang) {
                            if ($source_clang_id !== $rex_clang->getId() && rex::getUser() instanceof rex_user &&
                                    (\rex::getUser()->isAdmin() || \rex::getUser()->getComplexPerm('clang') instanceof rex_clang_perm && \rex::getUser()->getComplexPerm('clang')->hasPerm($rex_clang->getId()))) {
                                $lang_options[$rex_clang->getId()] = $rex_clang->getName();
                            }
                        }
                    }
                    if (!in_array($target_clang_id, array_keys($lang_options))) {
                        $target_clang_id = array_keys($lang_options)[0];
                    }
                    \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_translations_language', 'settings[clang_id]', $lang_options, [$target_clang_id]);

                    $filter_options = [
                        'update' => rex_i18n::msg('d2u_helper_translations_filter_update'),
                        'missing' => rex_i18n::msg('d2u_helper_translations_filter_missing'),
                    ];
                    \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_translations_filter_select', 'settings[filter]', $filter_options, [$filter_type]);
                ?>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="save"><?= rex_i18n::msg('d2u_helper_translations_apply') ?></button>
					</div>
				</div>
			</footer>
		</div>
	</form>
<?php

    if (rex_addon::get('d2u_news')->isAvailable()) {
        $news = \D2U_News\News::getTranslationHelperObjects($target_clang_id, $filter_type);
        $news_categories = \D2U_News\Category::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_news') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?= rex_i18n::msg('d2u_helper_categories') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($news_categories) > 0) {
                        echo '<ul>';
                        foreach ($news_categories as $current_news_category) {
                            if ('' === $current_news_category->name) {
                                $current_news_category = new \D2U_News\Category($current_news_category->category_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_news/news', ['entry_id' => $current_news_category->category_id, 'func' => 'edit']) .'">'. $current_news_category->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-newspaper-o"></i></small> <?= rex_i18n::msg('d2u_news_news_title') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($news) > 0) {
                        echo '<ul>';
                        foreach ($news as $current_news) {
                            if ('' === $current_news->name) {
                                $current_news = new \D2U_News\News($current_news->news_id, $source_clang_id);
                                if ('' === $current_news->name) {
                                    foreach (rex_clang::getAllIds() as $clang_id) {
                                        $current_news = new \D2U_News\News($current_news->news_id, $clang_id);
                                        if ('' === $current_news->name) {
                                            break;
                                        }
                                    }
                                }
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_news/news', ['entry_id' => $current_news->news_id, 'func' => 'edit']) .'">'. $current_news->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                if (rex_plugin::get('d2u_news', 'news_types')->isAvailable()) {
                    $news_types = \D2U_News\Type::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-file-text-o"></i></small> <?= rex_i18n::msg('d2u_news_types') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($news_types) > 0) {
                        echo '<ul>';
                        foreach ($news_types as $news_type) {
                            if ('' === $news_type->name) {
                                $news_type = new \D2U_News\Type($news_type->type_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_news/news_types', ['entry_id' => $news_type->type_id, 'func' => 'edit']) .'">'. $news_type->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
            ?>
		</div>
	</div>
<?php
    }

    /**
     * Extension point for translation list.
     * @param array $subject List of addons and their pages with translation status
     * @param array $params Parameters
     * @param int $params['source_clang_id'] Source clang id
     * @param int $params['target_clang_id'] Target clang id
     * @param string $params['filter_type'] Filter type
     * @return array List of addons and their pages with translation status. Example:
     * [
     *      [
     *          'addon_name' => 'addon name',
     *          'pages' => [
     *              [
     *                  'title' => 'addon page title',
     *                  'icon' => 'FontAwesome page icon',
     *                  'html' => 'ul html code containing links to the backend pages with the translations'
     *              ]
     *      ]
     * ]
     */
    $translation_list = rex_extension::registerPoint(new rex_extension_point(name: 'D2U_HELPER_TRANSLATION_LIST', params: ['source_clang_id' => $source_clang_id, 'target_clang_id' => $target_clang_id, 'filter_type' => $filter_type]));

    if (is_array($translation_list) && count($translation_list) > 0) {
        foreach ($translation_list as $translation_list_item) {
            if (count($translation_list_item['pages']) > 0) {
                echo '<div class="panel panel-edit">';
                echo '<header class="panel-heading"><div class="panel-title">'. $translation_list_item['addon_name'] .'</div></header>';
                echo '<div class="panel-body">';
                foreach ($translation_list_item['pages'] as $page) {
                    echo '<fieldset>';
                    echo '<legend><small><i class="rex-icon '. $page['icon'] .'"></i></small> '.$page['title'] .'</legend>';
                    echo '<div class="panel-body-wrapper slide">'. $page['html'] . '</div>';
                    echo '</fieldset>';
                }
                echo '</div>';
                echo '</div>';
            }
        }
    }
    else {
        echo 'update' === $filter_type ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
    }

    echo \TobiasKrais\D2UHelper\BackendHelper::getCSS();
    echo \TobiasKrais\D2UHelper\BackendHelper::getJS();
    echo \TobiasKrais\D2UHelper\BackendHelper::getJSOpenAll();
}