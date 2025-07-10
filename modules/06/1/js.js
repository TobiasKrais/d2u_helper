function loadYoutubeVideo(youtube_id) {
	document.getElementById('player-' + youtube_id).src = 'https://www.youtube-nocookie.com/embed/' + youtube_id + '?autoplay=1';
	document.getElementById('youtube-play-button-' + youtube_id).outerHTML = '';
	document.getElementById('youtube-gdpr-hint-' + youtube_id).outerHTML = '';
}

document.addEventListener("DOMContentLoaded", function() {
	// get all ements with class 'youtube-click-overlay'
	document.querySelectorAll('.youtube-click-overlay').forEach(function(element) {
		// add click event listener to each element
		element.addEventListener('click', function(e) {
			e.preventDefault();
			// get the youtube_id from the data attribute
			var youtube_id = this.getAttribute('data-youtube-id');
			// call the loadYoutubeVideo function with the url and id
			loadYoutubeVideo(youtube_id);
		});
	});
});