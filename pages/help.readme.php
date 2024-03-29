<div class="panel panel-edit">
	<header class="panel-heading"><div class="panel-title">Allgemeine Hinweise</div></header>
	<div class="panel-body">
		<p>Den Code des Projektes findest du auf GitHub. Und zwar hier: <a href="https://github.com/TobiasKrais/d2u_helper" target="_blank">
			https://github.com/TobiasKrais/d2u_helper</a>.</p>
		<p></p>
	</div>
	
	<header class="panel-heading"><div class="panel-title">Ersetzungen</div></header>
	<div class="panel-body">
		<p>Das Addon nimmt 2 Ersetzungen vor:</p>
		<ul>
			<li>+++LINK_PRIVACY_POLICY+++ wird mit dem Link zur Datenschutzerklärung ersetzt.</li>
			<li>+++LINK_IMPRESS+++ wird mit dem Link zum Impressum ersetzt.</li>
		</ul>
	</div>
	<header class="panel-heading"><div class="panel-title">Smartmenu Menu in eigenes Template einbinden</div></header>
	<div class="panel-body">
		<p>Beispielseite für Menü:
			<a href="https://www.design-to-use.de/" target="_blank">https://www.design-to-use.de/</a>.</p>
		<p>Beispiel PHP Code:</p>
		<div class="code">
		<?php
            highlight_string("<div class='navi'>
	<?php
		if(rex_addon::get('d2u_helper')->isAvailable()) {
			\TobiasKrais\D2UHelper\FrontendNavigationSmartmenu::getMenu();
		}
	?>
</div>");
        ?>
		</div>
	</div>

	<header class="panel-heading"><div class="panel-title">Responsive MultiLevel Menu in eigenes Template einbinden</div></header>
	<div class="panel-body">
		<p>Beispiel PHP Code:</p>
		<div class="code">
		<?php
            highlight_string("<div class='navi'>
	<?php
		if(rex_addon::get('d2u_helper')->isAvailable()) {
			\TobiasKrais\D2UHelper\FrontendNavigationResponsiveMultiLevel::getResponsiveMultiLevelMobileMenu(); // Mobiles Menü
			\TobiasKrais\D2UHelper\FrontendNavigationResponsiveMultiLevel::getResponsiveMultiLevelDesktopMenu(); // Desktop Menü
		}
	?>
</div>");
        ?>
		</div>
	</div>

	<header class="panel-heading"><div class="panel-title">SlickNav Menu in eigenes Template einbinden</div></header>
	<div class="panel-body">
		<p>Projektseite mit Beispielen und Dokumentation:
			<a href="https://www.slicknav.io/" target="_blank">https://www.slicknav.io/</a>.</p>
		<p>Beispiel PHP Code:</p>
		<div class="code">
		<?php
            highlight_string("<div class='navi'>
	<?php
		if(rex_addon::get('d2u_helper')->isAvailable()) {
			\TobiasKrais\D2UHelper\FrontendNavigationSlickNav::getMobileMenu(); // Mobiles Menü
		}
	?>
	<navi>
		<div id='navi_desktop'></div>
		<script>
			$(document).ready(function() {
				$('#slicknav-mobile-menu').slicknav({
					label: '', // Menu label
					prependTo: '#navi_desktop'
				});
			});
		</script>
	</navi>
</div>");
        ?>
		</div>
		<p>Beispiel PHP und  HTML Code wenn Menü automatisch gemäß den Einstellungen dieses
			Addons abhängig von der Bildschirmbreite aufgeklappt oder zugeklappt werden soll:</p>
		<div class="code">
		<?php
            highlight_string("<div class='navi'>
	<div id='device-size-detector'>
		<div id='xs' class='d-block d-sm-none'></div>
		<div id='sm' class='d-none d-sm-block d-md-none'></div>
		<div id='md' class='d-none d-md-block d-lg-none'></div>
		<div id='lg' class='d-none d-lg-block d-xl-none'></div>
		<div id='xl' class='d-none d-xl-block'></div>
	</div>
	<?php
		\$show_screen_size = \"screen == 'lg' ||  screen == 'xl'\";
		if(rex_addon::get('d2u_helper')->isAvailable()) {
			\TobiasKrais\D2UHelper\FrontendNavigationSlickNav::getMobileMenu();

			\$include_menu_show = rex_config::get('d2u_helper', 'include_menu_show', 'md');
			if(\$include_menu_show == 'xs') {
				\$show_screen_size = \"screen == 'sm' || screen == 'md' || screen == 'lg' ||  screen == 'xl'\";
			}
			else if(\$include_menu_show == 'sm') {
				\$show_screen_size = \"screen == 'md' || screen == 'lg' ||  screen == 'xl'\";
			}
			else if(\$include_menu_show == 'md') {
				\$show_screen_size = \"screen == 'lg' ||  screen == 'xl'\";
			}
			else if(\$include_menu_show == 'lg') {
				\$show_screen_size = \"screen == 'xl'\";
			}
			else {
				\$show_screen_size = 'false';
			}
		}
	?>
	<navi>
		<div id='navi_desktop'></div>
		<script>
			$(document).ready(function() {
				$('#slicknav-mobile-menu').slicknav({
					label: '', // Label menu
					prependTo: '#navi_desktop'
				});

				function getBootstrapDeviceSize() {
					return $('#device-size-detector').find('div:visible').first().attr('id');
				}

				function checkMenu() {
					var screen = getBootstrapDeviceSize();
					if(<?php print \$show_screen_size; ?>) {
						$('#slicknav-mobile-menu').slicknav('open');
					} else {
						$('#slicknav-mobile-menu').slicknav('close');
					}
				}
				checkMenu();
				$(window).on('resize', checkMenu);
			});
		</script>
	</navi>
</div>");
        ?>
		</div>
	</div>
</div>