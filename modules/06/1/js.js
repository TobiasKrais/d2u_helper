function loadYoutubeVideo(youtube_url, player_id) {
	document.getElementById('player-' + player_id).src = youtube_url;
	document.getElementById('youtube-play-button-' + player_id).outerHTML='';
	document.getElementById('youtube-gdpr-hint-' + player_id).outerHTML='';
}