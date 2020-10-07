<?php
	$article_id_search = rex_config::get('d2u_helper', 'article_id_search', 0);
	if(rex_addon::get('search_it')->isAvailable() && $article_id_search > 0) {
?>
<form method="get" action="<?php print rex_getUrl($article_id_search); ?>#search-field" class="search_it-formfragment">
	<?php
		// Show search field
		if($this->getVar('showSearchField', false)) {
			print '<div id="fragment-search">';
			print '<input type="text" name="search" value="'. ($request ? rex_escape($request) : '') .'" id="search_it_searchfragment" />';
		}
	?>

	<button id="search_icon" type="submit">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img">
			<path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path>
		</svg>
	</button>
	<?php
		// Show search field
		if($this->getVar('showSearchField', false)) {
			print '</div>';
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(function() {
				jQuery("#search_it_searchfragment").suggest("index.php?rex-api-call=search_it_autocomplete_getSimilarWords&rnd=" + Math.random(),
					{
						onSelect: function(event, ui) { $('.search_it-formfragment').submit(); return false;
					}
				});
			});
		});
	</script>
	<?php
		}
	?>
</form>
<?php
	}