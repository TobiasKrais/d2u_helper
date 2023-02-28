<?php

$clang_id = 'REX_VALUE[1]' === '' ? rex_clang::getStartId() : (int) 'REX_VALUE[1]'; /** @phpstan-ignore-line */

$art_lang = new rex_article_content(rex_article::getCurrentId(), $clang_id);
echo $art_lang->getArticle();
