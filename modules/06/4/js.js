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