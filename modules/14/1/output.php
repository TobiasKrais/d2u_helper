<?php
$article_id = rex_article::getCurrentId();
$request = rex_request('search', 'string', false);
$limit = "REX_VALUE[1]" ?: 10;
$start = rex_request('start', 'int', 0);
?>

<section class="search_it-search">
	<form class="search_it-form" id="search_it-form1" action="<?php echo rex_getUrl($article_id, rex_clang::getCurrentId()); ?>" method="get">
		<div class="search_it-flex">
			<input type="hidden" name="article_id" value="<?php echo $article_id; ?>" />
			<input type="hidden" name="clang" value="<?php echo rex_clang::getCurrentId(); ?>" />
			<input type="text" name="search" value="<?php if($request) { echo rex_escape($request); } ?>" placeholder="{{ d2u_helper_module_14_enter_search_term }}" />
			<button class="search_it-button" type="submit">
				<img src="<?php print rex_url::addonAssets('d2u_helper', 'icon_search.svg'); ?>">
			</button>
		</div>
	</form>
</section>

<?php
if($request) { // Wenn ein Suchbegriff eingegeben wurde
	$server = rtrim(rex::getServer(), "/");
	
	print '<section class="search_it-hits">';
	
	// Init search and execute
    $search_it = new search_it();
	$search_it->setLimit($start, $limit);
    $result = $search_it->search($request);

	echo '<h2 class="search_it-headline">{{ d2u_helper_module_14_search_results }}</h2>';
	if($result['count']) {
 		// Pagination
		$pagination = "";
		if($result['count'] > $limit) {
			$self = rex_article::get($article_id);
			$pagination = '<ul class="pagination">';
			for($i = 0; ($i * $limit) < $result['count']; $i++){
				if(($i * $limit) == $start){
					$pagination .= '<li class="current">'. ($i + 1) .'</li>';
				}
				else {
					$pagination .= '<li><a href="'.$self->getUrl(array('search' => $request, 'start' => $i * $limit)).'">'. ($i + 1) .'</a></li>';
				}
			}
			$pagination .= '</ul>';
		}
		echo $pagination;
		
		echo '<ul class="search_it-results">';                           
        foreach($result['hits'] as $hit) {

            if($hit['type'] == 'article') {
				// article hits
                $article = rex_article::get($hit['fid']);
				
				// get article domain
				$hit_server = $server;
				if(rex_addon::get('yrewrite')->isAvailable()) {
					$hit_domain =  rex_yrewrite::getDomainByArticleId($hit['fid'], $hit['clang']);
					$hit_server = rtrim($hit_domain->getUrl(), "/");
				}
				
				$hit_link = $hit_server . rex_getUrl($hit['fid'], $hit['clang'], array('search_highlighter' => $request));
                echo '<li class="search_it-result search_it-article">';
				echo '<span class="search_it-title">';
                echo '<a href="'. $hit_link .'" title="'. $article->getName() .'">'. $article->getName() .'</a>';
				echo '</span><br>';
				echo '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>';
				echo '<span class="search_it-url"><a href="'. $hit_link .'" title="'. $article->getName() .'">'.$hit_server.rex_getUrl($hit['fid'], $hit['clang']).'</a></span>';
				echo '</li>';
            }
            else if($hit['type'] == 'file') {
				// media hits
				$media = rex_media::get(pathinfo($hit['filename'], PATHINFO_BASENAME));
				if(is_object($media)) { 
					$hit_link = $server . rex_url::media($media->getFileName());
					echo '<li class="search_it-result search_it-image search_it-flex">';
					echo '<span class="search_it-title">';
					echo '<a href="'. $hit_link .'" title="'. $media->getTitle() .'">';
					echo '<span class="icon '. ($filetype == 'pdf' ? 'pdf' : 'file') .'"></span>';
					echo '&nbsp;&nbsp;'. $media->getTitle() .'</a>';
					echo '</span><br>';
					echo '<span class="search_it-teaser">'. $hit['highlightedtext'] .'</span><br>';
					echo '<span class="search_it-url"><a href="'. $hit_link .'" title="'. $media->getTitle() .'">'. $hit_link .'</a></span>';
					echo '</li>'; 
				}
			}
			else {
                // other hit types
            }
        }
        echo '</ul>';

		// Pagination
		echo $pagination;	
    }
	else if(!$result['count']) {
		echo '<p class="search_it-zero">{{ d2u_helper_module_14_search_results_none }}</p>';
	}
	print "</section>";
}