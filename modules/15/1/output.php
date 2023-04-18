<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$max_number_subcategories = 'REX_VALUE[1]' === 'true' ? 100 : ('REX_VALUE[1]' === '' ? 1 : (int) 'REX_VALUE[1]'); /** @phpstan-ignore-line */
$article = (int) 'REX_LINK[1]' > 0 ? rex_article::get((int) 'REX_LINK[1]') : rex_article::getCurrent(); /** @phpstan-ignore-line */

if (!function_exists('getSubcategories')) {
    /**
     * Print subcategories for a category.
     * @param int $level_number number of level to be able to count level depth
     * @param int $max_number_subcategories maximum level depth
     */
    function getSubcategories(rex_category $category, $level_number, $max_number_subcategories): void
    {
        ++$level_number;
        echo '<ul class="sub-categories-list">';
        foreach ($category->getChildren(true) as $sub_category) {
            $subcategory_start_article = $sub_category->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($subcategory_start_article)) {
                echo '<li><a href="'. $sub_category->getUrl() .'" title="'. $sub_category->getName() .'">'. $sub_category->getName() .'</a>';
                if ($level_number < $max_number_subcategories && count($sub_category->getChildren(true)) > 0) {
                    getSubcategories($sub_category, $level_number, $max_number_subcategories);
                }
                echo '</li>';
            }
        }
        echo '</ul>';
    }
}

// Module OUTPUT
if ($article instanceof rex_article) {
    $top_category = $article->getCategory();
    $sub_categories = $top_category instanceof rex_category ? $top_category->getChildren(true) : rex_category::getRootCategories(true);
    if (count($sub_categories) > 0) {
        $level_number = 1;
        echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
        echo '<h1>'. ($top_category instanceof rex_category ? $top_category->getName() : rex::getServerName()) .'</h1>';
        echo '<ul class="categories-list">';
        foreach ($sub_categories as $sub_category) {
            $subcategory_start_article = $sub_category->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($subcategory_start_article)) {
                echo '<li><a href="'. $sub_category->getUrl() .'" title="'. $sub_category->getName() .'">'. $sub_category->getName() .'</a>';
                if ($level_number < $max_number_subcategories && count($sub_category->getChildren(true)) > 0) { /** @phpstan-ignore-line */
                    getSubcategories($sub_category, $level_number, $max_number_subcategories);
                }
                echo '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
    }
}
