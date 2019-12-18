<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
?>
<div class="col-sm-12 col-md-<?php echo $cols; ?>">
	<div class="youtubeWrapper">
		<?php
			preg_match(
				'/[\\?\\&]v=([^\\?\\&]+)/',
				'REX_VALUE[1]',
				$matches
			);
		echo '<iframe width="1600" height="900" src="https://www.youtube-nocookie.com/embed/'. $matches[1] .'" frameborder="0" webkitAllowFullScreen moziallowfullscreen allowfullscreen></iframe>';
		?>
	</div>
</div>