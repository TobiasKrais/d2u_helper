<?php
$clangs = rex_clang::getAll(TRUE);
if(count($clangs) > 1) {
?>

<div class="modal fade" id="lang_chooser_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul id="langchooser">
					<?php
						$alternate_urls = d2u_addon_frontend_helper::getAlternateURLs();
						foreach($clangs as $rex_clang) {
							$link = isset($alternate_urls[$rex_clang->getId()]) ? $alternate_urls[$rex_clang->getId()] : rex_getUrl(rex_article::getSiteStartArticleId(), $rex_clang->getId());
							print '<li><a href="'. $link . ($rex_clang->getId() == rex_clang::getStartId() && rex_addon::get('yrewrite')->isAvailable() && rex_article::getCurrentId() == rex_yrewrite::getCurrentDomain()->getStartId() ? '?clang='. $rex_clang->getId() : '') .'">'
									.'<img class="lang-chooser-flag" src="'. rex_url::media($rex_clang->getValue('clang_icon')) .'" loading="lazy" alt="'. $rex_clang->getName() .'">'
									.'<span class="lang-text">'. $rex_clang->getName() .'</span></a></li>';
						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php
}