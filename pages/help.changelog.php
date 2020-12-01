<div class="panel panel-edit">
	<header class="panel-heading"><div class="panel-title">D2U Helper Changelog</div></header>
	<div class="panel-body">
		<p>1.8.1-DEV</p>
		<ul>
			<li>...</li>
		</ul>
		<p>1.8.0</p>
		<ul>
			<li>ACHTUNG: wenn Templates aus diesem Addon verwendet werden, müssen nach dem Update die Einstellungen überarbeitet werden. 
				Sofern das verwendete Template einen Footer mit Links hat, kann nun der Stil in den Einstellungen ausgewählt werden.</li>
			<li>Templates: alle Templates auf Mehrsprachigkeit erweitert. Neben der Navigation wird ein Sprachwähler angeboten.</li>
			<li>Templates: alle Templates um ein Icon für die Seitensuche erweitert, wenn das Addon search_it installiert und in den Einstellungen der Artikel mit der Suche hinterlegt ist.</li>
			<li>Templates Bugfix: beim Aufheben einer Verknüpfung eines Templates wurde der Template Key nicht gelöscht.</li>
			<li>Templates: Einstellungen für Templates Footer vereinheitlicht. Folgende Einstellungen wurden umbenannt:<br>
				template_02_1_footer_text => footer_text<br>
				template_04_1_footer_logo => footer_logo<br>
				template_04_2_facebook_icon => footer_facebook_icon<br>
				template_04_2_facebook_link => footer_facebook_link<br>
				template_05_1_footer_text => footer_text</li>
			<li>Modul 01-2 "Texteditor mit Bild und Fettschrift" Bildtitel kann optional angezeigt werden.</li>
			<li>Modul 03-1 "Bild" Bildtitel kann optional angezeigt werden.</li>
			<li>Modul 03-2 "Bildergalerie Ekko Lightbox" ermöglicht nun auch nur 2 Bilder und 1 Bild pro Zeile. Bilder werden nun auch lazy geladen.</li>
			<li>Modul 06-1 "YouTube Video einbinden": im Browser werden Daten von Youtube erst nach Zustimmung heruntergeladen. Falls nach dem Upate des Moduls keine Vorschaubilder des Videos gezeigt weden muss die von YRewirte zur Verfügung gestellt .htaccess Datei aktualisiert werden.</li>
			<li>Neues Modul 06-3 "Video mit Plyr einbinden" Modul zur Einbindung von Videos mit dem Plyr Addon.</li>
			<li>Neues Modul 06-4 "Videoliste mit Plyr einbinden" Modul zur Einbindung von MP4 Videolistens mit dem Plyr Addon.</li>
			<li>Modul 11-1 "YForm Kontaktformular": wenn Addon yform_spam_protection installiert ist, wird es verwendet.</li>
			<li>Modul 14-1 "Search It Suchmodul": Bugfix - Dokumente aus dem Medienpool wurden nicht angezeigt, wenn kein YCom installiert war.<br>
				Außerdem: Autofokus des Suchfeldes und Sprunganker zu den Suchergebnissen.<br>
				Und: Wenn das Addon yform_spam_protection installiert ist, wird es zur Spamabwehr genutzt.<br>
				Und: es werden maximal 10 ähnliche Begriffe ausgegeben.</li>
			<li>Neues Modul "15-1 Kategorie mit Liste der Unterkategorien".</li>
			<li>Module 01-2, 03-1, 03-2, 05-1, 06-3, 06-4, 10-1, 10-2 und 10-3: Bugfix Typeneinschränkungen der Eingabefelder hatte nicht funktioniert.</li>
			<li>Alle Module mit Medienfeldern: Fehler bei der Typebeschränkung von Medienfeldern behoben.</li>
			<li>Notices beim Speichern der Einstellungen entfernt.</li>
			<li>Menüs die das Addon zur Verfügung stellt prüft nun die angezeigten Artikel auf YCom Benutzerrechte und zeigt sie nur an, wenn die Rechte vorhanden sind.</li>
			<li>Bootstrap 4.5.3 Upgrade.</li>
		</ul>
		<p>1.7.0</p>
		<ul>
			<li>TinyMCE 5 als Lieblingseditor hinzugefügt.</li>
			<li>Benötigt Redaxo >= 5.10, da die neue Klasse rex_version und das neue key Feld in der Modultabelle verwendet wird.</li>
			<li>Hilfsklassen für URL 2 Addon auf aktuelle Git Version angepasst.</li>
			<li>d2u_addon_frontend_helper::getBreadcrumbs() gibt für die Startseite kein Breadcrumb mehr zurück.</li>
			<li>Update Ekko Lightbox to version <a href="https://github.com/ashleydw/lightbox/releases/tag/v5.4.0-rc2">5.4.0-rc2</a>.</li>
			<li>Bootstrap 4.5.0 Upgrade.</li>
			<li>Popper Upgrade auf Version 1.16.0.</li>
			<li>JQuery 3.5.1 Upgrade - ab dieser Version liefert das Addon keine eigene jQuery mehr mit aus, sondern bindet die schon mit Redaxo ausgelieferte Version ein.</li>
			<li>Spanische Frontend Übersetzungen für Modulausgabe hinzugefügt.</li>
			<li>Wenn Google Analytics in den Einstellungen aktiviert ist wird der Google Code nicht ausgegeben, wenn search_it die Seite zur Indexierung aufruft.</li>
			<li>Prüft vor dem Löschen eines Bildes ob es in einem Redaxo Template verwendet wird.</li>
			<li>Template Installation Empfehlung für email_obfuscator auf emailobfuscator Addon geändert.</li>
			<li>Modul 02-1 "Überschrift". Überschriften werden mit einer eigenen Klasse ihres Grades versehen (z.B. mit der Klasse "h1" oder "h2".</li>
			<li>Modul 03-1 "Bild" gibt Bild nur noch aus, wenn es auch im Medienpool vorhanden ist.</li>
			<li>Modul 04-1 "Google Maps" PHP Warnung in Moduleingab behoben.</li>
			<li>Modul 05-1 "Artikelweiterleitung" beherrscht nun auch Weiterleitungen zu Kategorien des D2U Veranstaltungen Addons.</li>
			<li>Modul 06-1 "YouTube Video einbinden" auf youtube-nocookie.com umgestellt.</li>
			<li>Modul 11-1 "YForm Kontaktformular": Übersetzungsfehler korrigiert und an YForm 3.4 angepasst.</li>
			<li>Modul 12-1 "YFeed Stream Galerie" auf das Addon Feeds aktualisiert. Neuer Name: "Feeds Stream Galerie".</li>
			<li>Modul 13-1 "Lauftext": Anzahl Sekunden in der der Lauftext durchlaufen soll kann eingestellt werden.</li>
			<li>Modul 14-1 "Search It Suchmodul" hinzugefügt.</li>
			<li>Bei Slider Templates 04-1 bis 04-3 ragt der Sliderhintergrund bei wenig Seiteninhalt nicht mehr über den Footer hinaus.</li>
			<li>Template "04-1 Header Slider Template with Slogan" bindet Cookie Gedöns Dialog ein, wenn das Addon installiert ist.</li>
			<li>Template "04-1 Header Slider Template with Slogan" verwendet Dropdown Menü als Sprachwähler, wenn mehr als 2 Sprachen online sind.</li>
			<li>Template "04-2 Header Slider Template" Notice entfernt. Außerdem kann ein eigenes Logo für den Footer festgelegt werden, das auf schmalen Bildschirmen unterhalb der Footer Links dargestellt wird.</li>
		</ul>
		<p>1.6.1</p>
		<ul>
			<li><b>ACHTUNG</b>: YRewrite Schema für russische und chinesische URLs entfernt. Statt dessen bitte das Addon <b>"YRewrite Scheme"</b> verwenden. Dort in den Einstellungen als Suffix "/" und als Schema "Standard" verwenden um die gleichen URLs zu generieren.</li>
			<li>Bootstrap 4.4.1 Upgrade.</li>
			<li>Backend: Hilfe Tab ist jetzt rechts ausgerichtet.</li>
			<li>Performance Verbesserungen wenn ein D2U Addon den Cache des URL Addons neu generiert hat.</li>
			<li>Template "01-1 Side Picture Template" Ansicht auf kleinen Bildschirmen optimiert.</li>
			<li>Modul 11-1 "YForm Kontaktformular": Fehler bei Installation behoben, wenn YForm Addon nicht aktiviert war.</li>
			<li>Modul 11-1 "YForm Kontaktformular": Honeypot als zusätzliche Spamschutzmaßnahme hinzugefügt.</li>
			<li>Modul 10-3 "Box mit Downloads": die Breite kann nun personalisiert werden.</li>
			<li>Modul 03-2 "Ekkolightbox Galerie" hat jetzt einen Abstand ober- und unterhalb der Bildervorschau, was vor allem für die mobile Ansicht wichtig war.</li>
		</ul>
		<p>1.6.0</p>
		<ul>
			<li>WiredMinds eMetrics Tracking Option (siehe Einstellungen) auf aktuellen WiredMinds LeadLab Tracking Account Code aktualisiert.</li>
			<li>Rewrite Schema für URL Encoded URLs verbessert (ACHTUNG: URL Kodierte URLs werden nur noch in Kleinbuchstaben ausgegeben).</li>
			<li>Modul 11-1 "YForm Kontaktformular (DSGVO kompatibel)" auf YForm E-Mailtemplate umgestellt, damit "Antworten an" Feld benutzt werden kann.</li>
			<li>Modul 11-1 "YForm Kontaktformular (DSGVO kompatibel)" kann nun die Breite bestimmt werden.</li>
			<li>JQuery Update 3.4.1.</li>
			<li>Smartmenus Version 1.1.0 als Navigation zur Verfügung gestellt (<a href="https://www.smartmenus.org/">https://www.smartmenus.org/</a>).</li>
			<li>Templates mit Resonsive Multilevel Menu auf Smartmenu umgestellt (Ausnahme: Immo Template), da Google Probleme mit der Nutzerfreundlichkeit feststellte.</li>
			<li>Template 02-1, 04-1, 04-2 und 04-3 Detailverbesserungen.</li>
			<li>Template 06-1 "Paper Sheet Template" hinzugefügt.</li>
			<li>Doku Templates mit Beispielseiten versehen und eine FAQ hinzugefügt.</li>
		</ul>
		<p>1.5.8</p>
		<ul>
			<li>Template 04-3 "Header Slider Template with Slogan" hinzugefügt.</li>
			<li>Template 00-1, 04-1, 04-2 und 04-3 bei Verwendung des D2U Veranstaltungen Addon: Anzahl Kurse wird im Warenkorb angezeigt.</li>
			<li>Templates mit URL Addon 2.x kompatibel gemacht.</li>
			<li>Methoden fürs Frontend zur Verfügung gestellt um vom URL Addon Versionsunabhängig den Namespace und die Dataset ID zu ermitteln.</li>
			<li>Modul "02-2 Überschrift mit Klapptext": Formate Überschrift um "Fett" und "Absatz" ergänzt.</li>
			<li>Bugfix Modul "10-3 Box mit Download": Anpassungen an media_auth Plugin von YCom.</li>
			<li>Bugfix Modul "05-1 Artikelweiterleitung": Fehler bei Verwendung des D2U Immo Addons behoben.</li>
		</ul>
		<p>1.5.7</p>
		<ul>
			<li>Verbesserung Templates: Mobile Navi wurde von Google manchmals als nicht mobiloptimiert bemängelt.</li>
			<li>Verbesserung Templates: Bildhöhe per default auf "auto" geändert.</li>
			<li>Verbesserung Templates: YRewrite Multidomain support.</li>
			<li>Bugfix Modul "02-2 Überschrift mit Klapptext": JQuery Effekt geändert, damit es auch im Safari funktioniert.</li>
			<li>Bugfix Modul "05-1 Artikelweiterleitung": nach Klick auf Link im Backend wurde beim editieren die Standardsprache editiert.</li>
			<li>Bugfix Modul "05-2 Artikel aus anderer Sprache übernehmen": aktuelle Sprache steht nicht mehr in der Auswahlliste zur Verfügung.</li>
			<li>Bugfix Template "05-1 Double Logo Template".</li>
			<li>Popper auf Version 1.14.7 aktualisiert.</li>
			<li>Bootstrap auf Version 4.3.1 aktualisiert.</li>
			<li>Im optionalen YRewrite Schema werden nun HTML Tags im zu normalisierenden Text entfernt.</li>
		</ul>
		<p>1.5.6</p>
		<ul>
			<li>Möglicher Fehler in boot.php beim Senden von CSS Daeien entfernt.</li>
			<li>Modul 11-1 "YForm Kontaktformular" an YForm 3 angepasst.</li>
			<li>Template 05-1 "Double Logo Template" hinzugefügt.</li>
		</ul>
		<p>1.5.5</p>
		<ul>
			<li>JS Methode zum Ausblenden von Sprachübersetzungen in kommenden Versionen von D2U Addons hinzugefügt.</li>
			<li>Readme um Hinweis zum Impressum in Templates ergänzt.</li>
			<li>Template 03-2 "Immo Window Advertising Template" an D2U Immo 1.0.8 angepasst.</li>
		</ul>
		<p>1.5.4</p>
		<ul>
			<li>Popper auf Version 1.14.6 aktualisiert.</li>
			<li>Bootstrap auf Version 4.2.1 aktualisiert.</li>
			<li>Methode D2UModule::isModuleIDInstalled() hinzugefügt.</li>
			<li>Modul 04-1 "Google Maps" Geocoding im Modul hinzugefügt.</li>
			<li>Template D2U ID 02-2 in 04-2 geändert.</li>
			<li>Template 04-1 "Header Slider Template with Slogan" hinzugefügt.</li>
		</ul>
		<p>1.5.3</p>
		<ul>
			<li>Bugfix: Deaktiviertes Addon zu deinstallieren führte zu fatal error.</li>
			<li>d2u_mobile_navi und d2u_mobile_navi_slicknav Klassen für die Navigation beinhalten jetzt das title Attribut bei Links.</li>
			<li>Bugfix d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu(): Menü konnte auf großen Bildschirmen trotz Einstellung nicht ausgeblendet werden.</li>
			<li>Alle Module: Backend Eingabefelder Style Redaxo konform gestaltet und unnötige Felder ausgeblendet.</li>
			<li>Modul 13-1 "Lauftext" hinzugefügt.</li>
			<li>Modul 11-2 "YForm Kontaktformular": Einrückung des Textes bei Checkboxen verbessert.</li>
			<li>Modul 04-1 "Google Maps" Geocoding Link erneuert.</li>
			<li>Modul 03-2 "Ekkolightbox Galerie" hat jetzt Option wie viele Bilder pro Reihe dargestellt werden sollen.</li>
			<li>Modul 02-2 "Überschrift mit Klapptext" hinzugefügt.</li>
			<li>Modul 01-2 "Texteditor mit Bild und Fettschrift" kann Bild nun auch 6 Spalten breit einbinden.</li>
		</ul>
		<p>1.5.2</p>
		<ul>
			<li>Bugfix: Deaktiviertes Addon zu deinstallieren führte zu fatal error.</li>
			<li>In den Einstellungen gibt es jetzt eine Option, eigene Übersetzungen in SProg dauerhaft zu erhalten.</li>
		</ul>
		<p>1.5.1</p>
		<ul>
			<li>Usability Modul 11-1 verbessert.</li>
			<li>Sliderbilder, die im Template 02-2 verwendet werden können nun nicht mehr aus dem Medienpool gelöscht werden.</li>
			<li>Template 02-2 zeigt bei installiertem D2U Veranstaltungen Addon einen Warenkorb neben den Breadcrumbs. Bei diesen wurde ein Fehler behoben.</li>
			<li>Breite des Logobereiches in Template 02-1 auf die ganze Breite erweitert. Einschränkungen müssen im Custom CSS hinterlegt werden.</li>
			<li>Eingabe der Module 02-1 und 06-2 optisch verbessert.</li>
			<li>Bugfix d2u_addon_frontend_helper::getAlternateURLs()</li>
			<li>Bugfix d2u_addon_frontend_helper::getBreadcrumbs() nun auch mit D2U Jobs Unterstützung</li>
			<li>Pflichtfelder im Backend werden nur noch markiert, wenn Eingabe fehlt oder inkorrekt ist.</li>
		</ul>
		<p>1.5.0</p>
		<ul>
			<li>Einige von anderen abhängige Einstellungen werden ausgeblendet wenn sie nicht relevant sind.</li>
			<li>Modul "12-1 YFeed Stream Galerie" hinzugefügt. Voraussetzung ist aber dieser Pull Request: https://github.com/yakamara/redaxo_yfeed/pull/68</li>
			<li>Modul "06-1 YouTube Video einbinden" fügt jetzt einen 15px Abstand unterhalb des Videos ein und kann nun 8 Spalten breit sein.</li>
			<li>Wenn in den Einstellungen die Option zum Automatischen hinzufügen von Bootstrap und JQuery aktiviert ist, gab es bisher keine Möglichkeit einzelne Templates auszunehmen. Das ist jetzt möglich, wenn im body Tag die Klasse 'prevent_d2u_helper_styles' hinzugefügt wird.</li>
			<li>Modul "01-1 Texteditor" Jetzt auch 9 von 12 Spalten auswählbar.</li>
			<li>Modul "01-2 Texteditor mit Bild und Fettschrift" bindet Bilder rechts und unterhalb vom Text ein. Außerdem können Bild und Überschrift nun verlinkt werden.</li>
			<li>Modul "03-1 Bild" bindet Bilder jetzt auch im Original ein.</li>
			<li>Übersetzungshilfe in Plugin ausgelagert.</li>
			<li>Lieblingseditor unterstützt nun CKEditor 5 mitsamt einzelnen Profilen und Profile des CKEditor (ab kommender Version 4.9.3).</li>
			<li>Slicknav als weitere Navigation hinzugefügt (Version 1.0.10, https://github.com/ComputerWolf/SlickNav).</li>
			<li>JQuery und Bootstrap 4 können jetzt getrennt voneinander eingebunden werden.</li>
		</ul>
		<p>1.4.7</p>
		<ul>
			<li>Bootstrap 4.1.3 upgrade.</li>
			<li>Template 00-1 bis 02-2: Meta Tags vereinheitlicht - mehr D2U Addons untersützt.</li>
			<li>Template 00-1 bis 02-2: Breadcrumbs um D2U Kurse Addon ergänzt und Ausblenden von Breadcrumbs auf der Startseite.</li>
			<li>Bugfix: Textarea Attributkonflikt Required und UseWYSIWYG: Required wird nicht mehr gesetzt wenn UseWYSIWYG gefordert ist.</li>
			<li>Bugfix: backend helper für textarea Feld ersetzt " durch '.</li>
			<li>Verhindert das Löschen von Impressum und Datenschutzerklärung solange sie in den Addoneinstellungen angegeben sind.</li>
			<li>Module 05-1 Artikelweiterleitung: Option zur Weiterleitung zu Datei im Medienpool hinzugefügt.</li>
			<li>Template 00-1: Warenkorbsymbol hinzugefügt wenn D2U Veranstaltungen Addon installiert ist.</li>
			<li>Template 00-1: Darstellung der Sprachauswahl auf kleinen Bildschirmen verbessert.</li>
			<li>Template 00-1: Pfeil bei Verwendung des Maschinenaddons statt Grafik durch CSS ersetzt.</li>
			<li>Template 02-2: Pfeil bei Verwendung des Maschinenaddons statt Grafik durch CSS ersetzt.</li>
			<li>Template 02-2: Facebook Link öffnet jetzt in neuem Fenster.</li>
		</ul>
		<p>1.4.6</p>
		<ul>
			<li>Neue Einstellung für Im Multilevel Menü: im Untermenü beim ersten Punkt der Artikelname angezeigen lassen anstatt den Kategorienamen zu wiederholen.</li>
			<li>Dem Mutltilevel Menu wurden bei Hovereffekten für die Darstellung Transitions hinzugefügt.</li>
			<li>Anpassungen an Redaxo 5.6 (Hintergrund: https://github.com/redaxo/redaxo/pull/1827)</li>
			<li>Modul 11-1 eine Zeitsperre als Spamschutz hinzugefügt.</li>
			<li>Module Autoupdate Fehler behoben.</li>
		</ul>
		<p>1.4.5</p>
		<ul>
			<li>Modul 11-1 Kontaktformular: Hinweis zum Datenschutz jetzt optional.</li>
			<li>Modul 05-1 Artikelweiterleitung: Bugfix wenn Artikel auf den weitergeleitet werden soll gelöscht wurde.</li>
			<li>Übersetzung für Modul 11-1 in Französisch, Russisch und Chinesisch hinzugefügt.</li>
			<li>Google Fonts aus Templates entfernt (nachladen von Fonts ohne vorherige Zustimmung wurde abgemahnt).</li>
		</ul>
		<p>1.4.4</p>
		<ul>
			<li>Anpassungen an D2U News Version >= 1.1.0 (Namespace hinzugefügt).</li>
			<li>Modul 11-1 "YForm Kontaktformular (DSGVO kompatibel)" hinzugefügt.</li>
			<li>Bootstrap 4.1.1 upgrade.</li>
		</ul>
		<p>1.4.3</p>
		<ul>
			<li>Übersetzungen für Name und Kategorie aus anderen Addons in dieses Addon verschoben.</li>
			<li>+++LINK_PRIVACY_POLICY+++ wird durch den in den Einstellungen hinterlegten Artikellink per Outputfilter ersetzt.</li>
			<li>+++LINK_IMPRESS+++ wird durch den in den Einstellungen hinterlegten Artikellink per Outputfilter ersetzt.</li>
		</ul>
		<p>1.4.2</p>
		<ul>
			<li>Impressum Link zu Einstellungen hinzugefügt.</li>
		</ul>
		<p>1.4.1</p>
		<ul>
			<li>CSS aus den Templates in die Module verlagert.</li>
			<li>Anpassungen EU DSGVO für andere D2U Addon. Bitte Einstellungen anpassen.</li>
			<li>Tempaltes: Navigation markiert aktuell ausgewähltes Element.</li>
			<li>Bugfix: Falsche Fehlermeldung bei Templateinstallation entfernt.</li>
			<li>Templates: Farbe Links der Überschrift angeglichen.</li>
			<li>Backend: Farbwähler werden besser dargestellt.</li>
			<li>Upgrade Bootstrap 4.1.0.</li>
		</ul>
		<p>1.4.0</p>
		<ul>
			<li>Modul und JS Cache aktiviert.</li>
			<li>Wenn Modul CSS nachgeladen werden soll, wird es nicht nur für
				Module des D2U Helper Addons nachgeladen, sondern von allen D2U Addons.</li>
			<li>Bei Modulinstallation wird in der Zuordnungsliste nur noch Module
				angezeigt, die keinem Modul - auch Zuordnungen aus anderen D2U Addons - zugeordnet sind.</li>
			<li>Paarung von installierten Modulen / Templates kann wieder aufgehoben werden.</li>
			<li>Bugfix: Lieblingseditor nun auch wählbar wenn nur eine Sprache vorhanden ist.</li>
			<li>Module 01-x jetzt mit Lieblingseditor.</li>
			<li>WiredMinds eMetrics Trackingcode kann automatisiert hinzugefügt werden.</li>
		</ul>
		<p>1.3.2</p>
		<ul>
			<li>Automatisches Modulupdate für D2U Addon Module wird ab kommender Version wieder funktionieren.</li>
			<li>Navigation beinhaltet nun auch Maschinen und Kategorien aus D2U Maschinen Addon.</li>
			<li>Header Slider Template hinzugefügt</li>
			<li>JQuery Update 3.3.1.</li>
			<li>Auswahl des Lieblings WYSIWYG Editors.</li>
			<li>Template 03-1 Fehler beim Drucken an Bootstrap 4.0.0 angepasst.</li>
			<li>Template 01-1 Sprachwahl berücksichtigt jetzt auch Kategorien des D2U Maschinen Addons.</li>
			<li>Favicons werden in Templates nur eingebunden wenn sie im Medienpool existieren.</li>
		</ul>
		<p>1.3.1</p>
		<ul>
			<li>Template 00-1 Bugfix falls Maschinen Addon verwendet wird.</li>
			<li>Modul 02-1 (Überschrift) verbessert.</li>
			<li>Modul 03-2 (Ekko Lightbox) verbessert.</li>
			<li>Für Template 02-1 kann die Navigation in den Einstellungen auch oberhalb des Headerbildes gesetzt werden.</li>
			<li>Update bootstrap 4.</li>
			<li>Update Ekko Lightbox to version 5.3.</li>
			<li>Backendsprache Englisch hinzugefügt.</li>
			<li>Module 10-3 Box mit Downloads prüft Dokumentenrechte, falls ycom/media_auth installiert ist.</li>
		</ul>
		<p>1.3.0</p>
		<ul>
			<li>Übersetzungshilfe für Inhalte von D2U Addons hinzugefügt.</li>
			<li>Schwerer Fehler in Backend Helper behoben, wenn versteckte Felder Arrays übermitteln sollen.</li>
			<li>Option für URL Encoding als Schema Option für YRewrite hinzugefügt.</li>
		</ul>
		<p>1.2.4</p>
		<ul>
			<li>Default Lang Einstellung D2U Addon übergreifend gemacht.</li>
			<li>Bugfix wenn Standardsprache gelöscht wird.</li>
		</ul>
		<p>1.2.3</p>
		<ul>
			<li>Bessere Anpassungen Templates an URL Addon Version 1.0.0beta5.</li>
			<li>Mehrsprachige Templates: Icon in Spracheinstellungen konfigurierbar.</li>
		</ul>
		<p>1.2.2</p>
		<ul>
			<li>Update bootstrap 4 beta.</li>
			<li>Update Ekko Lightbox to version 5.2.</li>
		</ul>
		<p>1.2.1</p>
		<ul>
			<li>Modul "Artikelweiterleitung" kann nun zu beliebigen externen URLs und zu Maschinen,
				Branchen und Gebrauchtmaschinen aus dem D2U Maschinen Addon weiterleiten.
				Auch zu Immobilien aus dem D2U Immo Addon.</li>
			<li>Backendfelder durch eine ID ansprechbar um sie ausblenden zu können.</li>
		</ul>
		<p>1.2</p>
		<ul>
			<li>Module "Box mit Überschrift und Bild" hinzugefügt.</li>
			<li>Bugfix: Hintergrundfarbe bei Box wird wieder angewandt.</li>
			<li>Template: "Side Picture" und "Header Pic" Template hinzugefügt.</li>
			<li>Template: "Feed Generator" Template zum generieren von RSS, ATOM und anderen Feeds hinzugefügt.</li>
			<li>Templates: Breadcrumbs in Einstellungen ausblendbar.</li>
			<li>Google Analytics automatisch hinzufügbar.</li>
			<li>Verbesserter Installationsmodus für Templates und Module.</li>
			<li>Bugfix: wenn bei einer neuen Installation noch keine Artikel angelegt wurden.</li>
			<li>Bugfix: Ekko Lightbox bei mehrfach eingesetztem Modul wurde Lightbox mehrfach geöffnet.</li>
			<li>Ekko Lightbox hat jetzt vordefinierten Image Type d2u_helper_gallery_thumb und d2u_helper_gallery_detail.</li>
			<li>Update JQuery to 3.2.1.</li>
			<li>Für vorgefertige Templates können jetzt eigene CSS Dateien eingebunden werden.</li>
		</ul>
		<p>1.1.2</p>
		<ul>
			<li>Methode zum aktualisieren von URL Schemata hinzugefügt.</li>
			<li>Update Bootstrap to alpha-6.</li>
			<li>Update Ekko Lightbox to 5.1.1.</li>
			<li>Update Module und Template während Addonupdate bei aktiviertem Autoupdate.</li>
		</ul>
		<p>1.1.1</p>
		<ul>
			<li>Farbbug bei weißem Menühintergrund behoben</li>
		</ul>
		<p>1.1.0</p>
		<ul>
			<li>Templates hinzugefügt</li>
			<li>Farbeinstellungen erweitert</li>
			<li>Responisive MultiLevel Menü hinzugefügt</li>
		</ul>
		<p>1.0.2</p>
		<ul>
			<li>Bugfix: automatisches Modul Update während Addon Update sollte nun funktionieren.</li>
		</ul>
		<p>1.0.1</p>
		<ul>
			<li>Bugfix: Google Maps Modul</li>
		</ul>
		<p>1.0.0</p>
		<ul>
			<li>Module mit Autoupdate hinzugefügt</li>
		</ul>
	</div>
</div>