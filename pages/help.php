<?php
$chapter = rex_request('chapter', 'string');

$chapterpages = array (
	'' => array(rex_i18n::msg('d2u_helper_help_chapter_readme'), 'pages/help/readme.php'),
	'update' => array(rex_i18n::msg('d2u_helper_help_chapter_update'), 'pages/help/update.php'),
	'changelog' => array(rex_i18n::msg('d2u_helper_help_chapter_changelog'), 'pages/help/changelog.php'),
);

// build chapter navigation
$chapternav = '';

foreach ($chapterpages as $chapterparam => $chapterprops) {
	if ($chapterprops[0] != '') {
		if ($chapter != $chapterparam) {
			$chapternav .= ' | <a href="'. rex_url::currentBackendPage(array('chapter' => $chapterparam)) .'">'. $chapterprops[0] .'</a>';
		} else {
			$chapternav .= ' | <a class="active" href="'. rex_url::currentBackendPage(array('chapter' => $chapterparam)) .'">'. $chapterprops[0] .'</a>';
		}
	}
}
?>
<style type="text/css">
	.panel-title a.active {
		text-decoration: underline;
	}
</style>
<div class="panel panel-edit">
	<header class="panel-heading"><div class="panel-title"><?php print ltrim($chapternav, " | "); ?></div></header>
	<div class="panel-body">
		<?php include(rex_path::addon("d2u_helper", $chapterpages[$chapter][1])); ?>
	</div>
</div>