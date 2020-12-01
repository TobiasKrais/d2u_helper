function loadYoutubeVideo(youtube_url, player_id) {
	document.getElementById('player-' + player_id).src = youtube_url;
	g = document.getElementById('youtubeWrapper-' + player_id);
	g = g.getElementsByTagName('div')[0];
	g = g.outerHTML='';
}