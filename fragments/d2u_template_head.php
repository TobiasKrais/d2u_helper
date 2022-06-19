	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		print d2u_addon_frontend_helper::getMetaTags();
		if(file_exists(rex_path::media('favicon.ico'))) {
			print '	<link rel="apple-touch-icon" href="'. rex_url::media('favicon.ico') .'">'. PHP_EOL;
			print '	<link rel="icon" href="'. rex_url::media('favicon.ico') .'">'. PHP_EOL;
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print consent_manager_frontend::getFragment(false, 'consent_manager_box_cssjs.php');
		}
	?>