<?php
$clang_id = "REX_VALUE[1]" == "" ? rex_clang::getStartId() : "REX_VALUE[1]";

$art_lang = new rex_article_content($this->getValue('article_id'), $clang_id);
echo $art_lang->getArticle();
?>