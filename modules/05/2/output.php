<?php
$sprache = "REX_VALUE[1]" == "" ? 1 : "REX_VALUE[1]";

$art_lang = new rex_article_content($this->getValue('article_id'), $sprache);
echo $art_lang->getArticle();
?>