<?php
$cols_sm = "REX_VALUE[20]";
if($cols_sm == "") {
	$cols_sm = 12;
}
$cols_md = "REX_VALUE[19]";
if($cols_md == "") {
	$cols_md = 12;
}
$cols_lg = "REX_VALUE[18]";
if($cols_lg == "") {
	$cols_lg = 8;
}
$offset_lg_cols = intval("REX_VALUE[17]");
$offset_lg = "";
if($offset_lg_cols > 0) {
	$offset_lg = " mr-lg-auto ml-lg-auto ";
}

$show_subcategories = "REX_VALUE[1]" == 'true' || "REX_VALUE[1]" == '' ? TRUE : FALSE;
$article = "REX_LINK[1]" > 0 ? rex_article::get("REX_LINK[1]") : rex_article::getCurrent();


if(!function_exists('getSubcategories')) {
	/**
	 * Print subcategories for a category
	 * @param rex_category $category
	 */
	function getSubcategories(rex_category $category) {
		print '<ul class="sub-categories-list">';
		foreach($category->getChildren(true) as $sub_category) {
			print '<li><a href="'. $sub_category->getUrl() .'" title="'. $sub_category->getName() .'">'. $sub_category->getName() .'</a>';
			if(count($sub_category->getChildren(true)) > 0) {
				getSubcategories($sub_category);
			}
			print '</li>';
		}
		print '</ul>';
	}
}

// Module OUTPUT
if($article instanceof rex_article) {
	$top_category = $article->getCategory();
	$sub_categories = $top_category instanceof rex_category ? $article->getCategory()->getChildren(true) : rex_category::getRootCategories(true);
	if(count($sub_categories) > 0) {
		print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
		print '<h1>'. ($top_category ? $top_category->getName() : rex::getServerName()) .'</h1>';
		print '<ul class="categories-list">';
		foreach($sub_categories as $sub_category) {
			print '<li><a href="'. $sub_category->getUrl() .'" title="'. $sub_category->getName() .'">'. $sub_category->getName() .'</a>';
			if($show_subcategories && count($sub_category->getChildren(true)) > 0) {
				getSubcategories($sub_category);
			}
			print '</li>';
		}
		print '</ul>';
		print '</div>';
	}
}