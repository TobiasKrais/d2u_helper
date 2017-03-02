<?php
/**
 * Version 1.0
 * Add the following lines to your stylesheet:
 * .youtubeWrapper {
 *	position: relative;
 *	padding-bottom: 56.25%;
 *	padding-top: 25px;
 *	height: 0;
 * }
 * .youtubeWrapper iframe {
 *	position: absolute;
 *	top: 0;
 *	left: 0;
 *	width: 100%;
 *	height: 100%;
 * }
 */
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
		echo '<iframe width="1600" height="900" src="https://www.youtube.com/embed/'. $matches[1] .'" frameborder="0" webkitAllowFullScreen moziallowfullscreen allowfullscreen></iframe>';
		?>
	</div>
</div>