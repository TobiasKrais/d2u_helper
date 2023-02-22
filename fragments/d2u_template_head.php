	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
        echo d2u_addon_frontend_helper::getMetaTags();
        if (file_exists(rex_path::media('favicon.ico'))) {
            echo '	<link rel="apple-touch-icon" href="'. rex_url::media('favicon.ico') .'">'. PHP_EOL;
            echo '	<link rel="icon" href="'. rex_url::media('favicon.ico') .'">'. PHP_EOL;
        }
        if (rex_addon::get('consent_manager')->isAvailable()) {
            echo consent_manager_frontend::getFragment(false, 'consent_manager_box_cssjs.php');
        }
    ?>