<?php
$cols = "REX_VALUE[20]";
if($cols == "") {
	$cols = 8;
}

preg_match(
	'/[\\?\\&]v=([^\\?\\&]+)/',
	'REX_VALUE[1]',
	$matches
);
$youtube_id = $matches[1];
$youtube_url = 'https://www.youtube-nocookie.com/embed/'. $youtube_id .'?autoplay=1';
$youtube_previewimage_url = 'https://img.youtube.com/vi/'. $youtube_id .'/hqdefault.jpg';
$previewimage_target_filename = 'youtube-'. $youtube_id .'.jpg';

// Copy preview image
if(!is_dir(rex_path::addonCache('d2u_helper')) || !file_exists(rex_path::addonCache('d2u_helper', $previewimage_target_filename))) {
	if(!is_dir(rex_path::addonCache('d2u_helper'))) {
		mkdir(rex_path::addonCache('d2u_helper'), 0755, TRUE);
	}
	copy($youtube_previewimage_url, rex_path::addonCache('d2u_helper', $previewimage_target_filename));
}
?>

<div class="col-sm-12 col-md-<?php echo $cols; ?>">
	<div id="youtubeWrapper-<?= $youtube_id; ?>" class="youtubeWrapper">
		<div class="youtube-gdpr-hint">
			<p><?php echo \Sprog\Wildcard::get('d2u_helper_module_06_gdpr_hint'); ?></p>
			<button type="button" id="play-<?= $youtube_id; ?>" ><svg aria-hidden="true" focusable="false" viewBox="0 0 18 18"><path d="M15.562 8.1L3.87.225c-.818-.562-1.87 0-1.87.9v15.75c0 .9 1.052 1.462 1.87.9L15.563 9.9c.584-.45.584-1.35 0-1.8z"></path></svg></button>
		</div>
		<iframe width="1600" height="900" src="" id="player-<?= $youtube_id ?>" frameborder="0" webkitAllowFullScreen moziallowfullscreen allowfullscreen
				style="background: url(<?php echo rex_media_manager::getUrl('d2u_helper_module_06-1_preview', $previewimage_target_filename); ?>) center; background-size: cover;"></iframe>
	</div>
</div>
  
<script>
	document.getElementById('play-<?= $youtube_id; ?>').addEventListener('click', function() {
		loadYoutubeVideo('<?=  $youtube_url; ?>', '<?= $youtube_id; ?>') ;
	});
</script>