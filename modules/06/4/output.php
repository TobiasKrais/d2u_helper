<?php
$cols_sm = "REX_VALUE[20]";
if($cols_sm == "") {
	$cols_sm = 12;
}
$cols_md = "REX_VALUE[19]";
if($cols_md == "") {
	$cols_md = 12;
}
$cols_lg = "REX_VALUE[18]";
if($cols_lg == "") {
	$cols_lg = 8;
}
$offset_lg_cols = intval("REX_VALUE[17]");
$offset_lg = "";
if($offset_lg_cols > 0) {
	$offset_lg = " mr-lg-auto ml-lg-auto ";
}

$media_filenames = preg_grep('/^\s*$/s', explode(",", REX_MEDIALIST[1]), PREG_GREP_INVERT);

print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
print '<div class="plyr-container">';
print '<div id="player">';
$plyr_media = rex_plyr::outputMedia($media_filenames[0], 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen' /*, '/media/cover/REX_MEDIA[2]'*/);
print $plyr_media;
print '</div>';
print '</div>';
print '</div>';

if(!function_exists('loadJsPlyr')) {
	function loadJsPlyr() {
		print '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
	}
	loadJsPlyr();
}
?>
<script>
var addbuttons = true;

/* PLAYLIST  */
var myPlaylist = [
<?php
	$first_element = true;
	foreach ($media_filenames as $media_filename) {
		$media = rex_media::get($media_filename);
		if ($media instanceof rex_media) {
			if ($first_element) {
				$first_element = false;
			} else {
				print ',';
			}

			print '{' . PHP_EOL;
			print 'type: "video/mp4",' . PHP_EOL;
			print 'title: "' . $media->getTitle() . '",' . PHP_EOL;
			print 'sources: [{ ' . PHP_EOL;
			print 'src: "' . $media->getUrl() . '",' . PHP_EOL;
			print 'type: "video/mp4"' . PHP_EOL;
			print '}],' . PHP_EOL;
			print 'src: "' . $media->getUrl() . '",' . PHP_EOL;
			//		poster: "https://img.youtube.com/vi/nfs8NYg7yQM/hqdefault.jpg"
			print '}' . PHP_EOL;
		}
	}
?>
	];

	var target = ".rex-plyr";
	var players;

	$(document).ready(function () {
		players = Plyr.setup(target, {
			youtube: { 
				noCookie: true
			},
			iconUrl: 'assets/addons/plyr/vendor/plyr/dist/plyr.svg',
			blankVideo: 'assets/addons/plyr/vendor/plyr/dist/blank.mp4'
		});
		loadPlaylist(target, myPlaylist);
	});

	function loadPlaylist(target, myPlaylist) {
		$("li.pls-playing").removeClass("pls-playing");
		$(".plyr-playlist-wrapper").remove();

		PlyrPlaylist(".plyr-playlist", myPlaylist);

		function PlyrPlaylist(target, myPlaylist) {
			$('<div class="plyr-playlist-wrapper"><ul class="plyr-playlist"></ul></div>').insertAfter("#player");

			var playingclass = "";
			var items = [];
			$.each(myPlaylist, function (id, val) {
				if (0 === id) {
					playingclass = "pls-playing";
				}
				else {
					playingclass = "";
				}

				items.push(
						'<li class="' + playingclass + '"><a href="#" data-type="' + val.sources[0].type + '" data-video-id="' + val.sources[0].src + '">' +
						(val.poster ? '<img class="plyr-miniposter" src="' + val.poster + '"> ' : '') +
						val.title + (val.author ? " - " + val.author : "") + "</a></li> ");

			});
			$(target).html(items.join(""));

			setTimeout(function () {}, 600);
		}

		$(document).on("click", "ul.plyr-playlist li a", function (event) {
			event.preventDefault();

			$("li.pls-playing").removeClass("pls-playing");
			$(this)
					.parent()
					.addClass("pls-playing");

			var video_id = $(this).data("video-id");
			// var video_type = $(this).data("type");
			// var video_title = $(this).text();

			players[0].media.setAttribute('src', video_id);
			players[0].play();

			$(".plyr-playlist").scrollTo(".pls-playing", 300);
		});
	}

	/****** GC ScrollTo **********/
	jQuery.fn.scrollTo = function (elem, speed) {
		jQuery(this).animate(
				{
					scrollTop:
							jQuery(this).scrollTop() -
							jQuery(this).offset().top +
							jQuery(elem).offset().top
				},
				speed === undefined ? 1000 : speed
		);
		return this;
	};
</script>