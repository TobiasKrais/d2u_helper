<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
	print d2u_addon_frontend_helper::getMetaTags();
	if(file_exists(rex_path::media('favicon.ico'))) {
		print '<link rel="apple-touch-icon" href="'. rex_url::media('favicon.ico') .'">';
		print '<link rel="icon" href="'. rex_url::media('favicon.ico') .'">';
	}
	if(rex_addon::get('consent_manager')->isAvailable()) {
		print 'REX_CONSENT_MANAGER[]';
	}
?>
<link rel="stylesheet" href="/index.php?template_id=04-1&amp;d2u_helper=template.css">