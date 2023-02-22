<?php
$d2u_module_manager = new D2UModuleManager(D2UModuleManager::getModules());

// D2UModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if ('' !== $d2u_module_id) {
    $d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
}

// D2UModuleManager show list
$d2u_module_manager->showManagerList();
?>
<h2>FAQ</h2>
<p>Alle Module von D2U mit HTML Ausgabe verwenden Bootstrap 4. Für ein optimales
	Ergebnis sollte in den Einstellungen die Einbindung der aktuellen
	Bootstrap / JQuery Dateien aktiviert werden. Ebenfalls sollte die CSS / JS
	der installierten Module automatisch eingebunden werden. Einige Module
	funktionieren auch ohne Bootstrap, andere - wie Ekko Lightbox - nicht.</p>
<ul>
	<li><b>Warum funktioniert mein Modul nicht korrekt?</b><br>
		Beispiel: wenn man beim YouTube Modul auf Play drückt, wird das Viedo nicht geladen.<br>
		Dann wurde das JavaScript oder Stylesheet nicht korrekt geladen. Hierzu
			muss in den Einstellungen die Option "CSS / JS der installierten Module
			automatisch einbinden" aktiviert werden oder das sie CSS / JS Dateiinhalte
			manuell nachgeladen werden. Wo findet man die CSS / JS Dateien? Im
			Dateisystem im Redaxo Addon Order unter module/. Dann die Modulnummer.
			Die CSS Datei trägt den Namen style.css und die JS Datei js.js.
	</li>
	<li><b>Wie kann der in den Modulen verwendete Editor geändert werden?</b><br>
		Um den Lieblingseditor für die Module und und die D2U Addons
			einzustellen einfach in den Einstellungen dieses Addons den gewünschten
			Editor auswählen. Unterstützt werden TinyMCE 4 und 5 (nur Standard Profil),
			CKEditor (alle Profile ab Version 4.9.3), Redactor 2 und MarkItUp
			(nur Standard Profile). Wichtig: Während die Editoren beliebig
			gewechselt werden können, sind MarkItUp Profile sind nicht kompatibel
			zu den anderen Editoren. Ist MarkItUp ein mal gewählt, sollte er
			gewählt bleiben.
	</li>
<ul>