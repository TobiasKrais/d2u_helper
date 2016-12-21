<?php
/**
 * Version 1.0
 */
$article_id = "REX_LINK[1]";

if($article_id > 0) {
	if(rex::isBackend()) {
		echo "Weiterleitung zu Artikel <a href='". rex_url::backendPage('content/edit', array('article_id'=>$article_id)) ."'>"
		. rex_article::get($article_id, $clang_id)->getValue('name') ." (Artikel ID ". $article_id .")</a>";
	}
	else {
		header('Location: '. rex_getUrl($article_id));
		header("Status: 301");
   		exit();
  	}
}
?>
