function loadYoutubeVideo(youtube_id, youtube_url) {
	iframe = document.getElementById('player-' + youtube_id);
	if (iframe) {
		iframe.allow = 'autoplay; encrypted-media';
		iframe.allowFullscreen = true;
		iframe.src = youtube_url;
	}
	document.getElementById('youtube-play-button-' + youtube_id).outerHTML = '';
	document.getElementById('youtube-gdpr-hint-' + youtube_id).outerHTML = '';
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