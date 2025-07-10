function loadYoutubeVideo(youtube_id, youtube_url) {
	document.getElementById('player-' + youtube_id).src = youtube_url;
	document.getElementById('youtube-play-button-' + youtube_id).outerHTML = '';
	document.getElementById('youtube-gdpr-hint-' + youtube_id).outerHTML = '';
	// Entferne auch den mobilen GDPR-Hint
	var mobileHint = document.getElementById('youtube-gdpr-hint-mobile-' + youtube_id);
	if (mobileHint) {
		mobileHint.outerHTML = '';
	}
	// remove overlay
	document.getElementById('youtube-click-overlay-' + youtube_id).outerHTML = '';
}

document.addEventListener("DOMContentLoaded", function() {
	// get all ements with class 'youtube-click-overlay'
	document.querySelectorAll('.youtube-click-overlay').forEach(function(element) {
		// add click event listener to each element
		element.addEventListener('click', function(e) {
			e.preventDefault();
			// get the youtube_id from the data attribute
			var youtube_id = this.getAttribute('data-youtube-id');
			var youtube_url = this.getAttribute('data-youtube-url');
			// call the loadYoutubeVideo function with the url and id
			loadYoutubeVideo(youtube_id, youtube_url);
		});
	});
});