	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
        echo TobiasKrais\D2UHelper\FrontendHelper::getMetaTags();
        if (file_exists(rex_path::media('favicon.ico'))) {
            echo '	<link rel="apple-touch-icon" href="'. rex_url::media('favicon.ico') .'">'. PHP_EOL;
            echo '	<link rel="icon" href="'. rex_url::media('favicon.ico') .'">'. PHP_EOL;
        }
        $consent_manager_addon = rex_addon::get('consent_manager');
        if ($consent_manager_addon->isAvailable()) {
            if(rex_version::compare('4.0.0', $consent_manager_addon->getVersion(), '<=')) {
                // Consent Manager 4.x
                echo consent_manager_frontend::getFragment(false, false, 'consent_manager_box_cssjs.php');
//                echo '<link rel="stylesheet" href="'. template_asset_url('theme/css/meincss.min.css') .'">';
            }
        }
        // FontAwesome
        echo '<link href="'. rex_url::addonAssets('d2u_helper', 'FontAwesome/css/all.min.css') .'" rel="stylesheet" type="text/css" media="all">'. PHP_EOL;