<?php
/**
 * Offers helper functions for language issues
 */
class d2u_helper_lang_helper {
	/**
	 * @var string[] Array with english replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_english = [
		'd2u_helper_module_11_captcha' => 'To prevent abuse, please enter captcha.',
		'd2u_helper_module_11_city' => 'City',
		'd2u_helper_module_11_contact_request' => 'Contact request',
		'd2u_helper_module_11_contact_request_intro' => 'The following values were entered in the request form:',
		'd2u_helper_module_11_email' => 'E-mail address',
		'd2u_helper_module_11_message' => 'Message',
		'd2u_helper_module_11_name' => 'Name',
		'd2u_helper_module_11_phone' => 'Phone',
		'd2u_helper_module_11_privacy_policy' => 'I accept and agree to the <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Privacy Policy</a>. My data will not be disclosed to third parties. I know about the right of deletion of my data. I can request deletion at any time from the contact details provided in the <a href="+++LINK_IMPRESS+++" target="_blank">imprint</a>.',
		'd2u_helper_module_11_required' => 'Required',
		'd2u_helper_module_11_send' => 'Send',
		'd2u_helper_module_11_street' => 'Street',
		'd2u_helper_module_11_thanks' => 'Thank you for request. we will answer as soon as possible.',
		'd2u_helper_module_11_validate_captcha' => 'The Captcha was not read correctly.',
		'd2u_helper_module_11_validate_message' => 'Please enter a message.',
		'd2u_helper_module_11_validate_email' => 'Please enter a valid email address.',
		'd2u_helper_module_11_validate_name' => 'Please enter your full name.',
		'd2u_helper_module_11_validate_phone' => 'Please enter your phone number.',
		'd2u_helper_module_11_validate_privacy_policy' => 'It\'s necessary to accept the privacy policy.',
		'd2u_helper_module_11_validate_spambots' => 'Too fast - it seems you are a spambot. Please take your time to fill out all all fields.',
		'd2u_helper_module_11_validate_title' => 'Failure sending message:',
		'd2u_helper_module_11_zip' => 'ZIP Code',
	];
	/**
	 * @var string[] Array with german replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_german = [
		'd2u_helper_module_11_captcha' => 'Um Missbrauch vorzubeugen bitten wir Sie das Captcha einzugeben.',
		'd2u_helper_module_11_city' => 'Ort',
		'd2u_helper_module_11_contact_request' => 'Kontaktanfrage',
		'd2u_helper_module_11_contact_request_intro' => 'In das Anfrage-Formular wurden folgende Werte wurden eingetragen',
		'd2u_helper_module_11_email' => 'E-Mail Adresse',
		'd2u_helper_module_11_message' => 'Nachricht',
		'd2u_helper_module_11_name' => 'Name',
		'd2u_helper_module_11_phone' => 'Telefon',
		'd2u_helper_module_11_privacy_policy' => 'Ich akzeptiere die <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Datenschutzerklärung</a> und bin damit einverstanden. Meine Daten werden nicht an Dritte weitergegeben. Ich habe das Recht auf Löschung meiner Daten. Diese kann ich jederzeit unter den im <a href="+++LINK_IMPRESS+++" target="_blank">Impressum</a> angegebenen Kontaktdaten verlangen.',
		'd2u_helper_module_11_required' => 'Pflichtfelder',
		'd2u_helper_module_11_send' => 'Abschicken',
		'd2u_helper_module_11_street' => 'Straße',
		'd2u_helper_module_11_thanks' => 'Danke für Ihr Nachricht. Wir werden uns schnellst möglich um Ihr Anliegen kümmern.',
		'd2u_helper_module_11_validate_captcha' => 'Bitte geben Sie erneut das Captcha ein.',
		'd2u_helper_module_11_validate_message' => 'Bitte geben Sie eine Nachricht ein.',
		'd2u_helper_module_11_validate_email' => 'Bitte geben Sie eine gültige E-Mailadresse ein.',
		'd2u_helper_module_11_validate_name' => 'Um Sie korrekt ansprechen zu können, geben Sie bitte Ihren vollständigen Namen an.',
		'd2u_helper_module_11_validate_phone' => 'Bitte geben Sie Ihre Telefonnummer an.',
		'd2u_helper_module_11_validate_privacy_policy' => 'Der Datenschutzerklärung muss zugestimmt werden.',
		'd2u_helper_module_11_validate_spambots' => 'Sie haben das Formular so schnell ausgefüllt, wie es nur ein Spambot tun kann. Bitte lassen Sie sich beim ausfüllen der Felder etwas mehr Zeit.',
		'd2u_helper_module_11_validate_title' => 'Fehler beim Senden:',
		'd2u_helper_module_11_zip' => 'Postleitzahl',
	];
	
	/**
	 * Factory method.
	 * @return d2u_helper_lang_helper Object
	 */
	public static function factory() {
		return new d2u_helper_lang_helper();
	}
	
	/**
	 * Installs the replacement table for this addon.
	 */
	public function install() {
		$d2u_helper = rex_addon::get('d2u_helper');
		
		foreach($this->replacements_english as $key => $value) {
			$addWildcard = rex_sql::factory();

			foreach (rex_clang::getAllIds() as $clang_id) {
				// Load values for input
				if($d2u_helper->hasConfig('lang_replacement_'. $clang_id) && $d2u_helper->getConfig('lang_replacement_'. $clang_id) == 'german') {
					$value = $this->replacements_german[$key];
				}
				else { 
					$value = $this->replacements_english[$key];
				}

				if(rex_addon::get('sprog')->isAvailable()) {
					$select_pid_query = "SELECT pid FROM ". rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."' AND clang_id = ". $clang_id;
					$select_pid_sql = rex_sql::factory();
					$select_pid_sql->setQuery($select_pid_query);
					if($select_pid_sql->getRows() > 0) {
						// Update
						$query = "UPDATE ". rex::getTablePrefix() ."sprog_wildcard SET "
							."`replace` = '". addslashes($value) ."', "
							."updatedate = '". rex_sql::datetime() ."', "
							."updateuser = '". rex::getUser()->getValue('login') ."' "
							."WHERE pid = ". $select_pid_sql->getValue('pid');
						$sql = rex_sql::factory();
						$sql->setQuery($query);						
					}
					else {
						$id = 1;
						// Before inserting: id (not pid) must be same in all langs
						$select_id_query = "SELECT id FROM ". rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."' AND id > 0";
						$select_id_sql = rex_sql::factory();
						$select_id_sql->setQuery($select_id_query);
						if($select_id_sql->getRows() > 0) {
							$id = $select_id_sql->getValue('id');
						}
						else {
							$select_id_query = "SELECT MAX(id) + 1 AS max_id FROM ". rex::getTablePrefix() ."sprog_wildcard";
							$select_id_sql = rex_sql::factory();
							$select_id_sql->setQuery($select_id_query);
							if($select_id_sql->getValue('max_id') != NULL) {
								$id = $select_id_sql->getValue('max_id');
							}
						}
						// Save
						$query = "INSERT INTO ". rex::getTablePrefix() ."sprog_wildcard SET "
							."id = ". $id .", "
							."clang_id = ". $clang_id .", "
							."wildcard = '". $key ."', "
							."`replace` = '". addslashes($value) ."', "
							."createdate = '". rex_sql::datetime() ."', "
							."createuser = '". rex::getUser()->getValue('login') ."', "
							."updatedate = '". rex_sql::datetime() ."', "
							."updateuser = '". rex::getUser()->getValue('login') ."'";
						$sql = rex_sql::factory();
						$sql->setQuery($query);
					}
				}
			}
		}
	}

	/**
	 * Uninstalls the replacement table for this addon.
	 * @param int $clang_id Redaxo language ID, if 0, replacements of all languages
	 * will be deleted. Otherwise only one specified language will be deleted.
	 */
	public function uninstall($clang_id = 0) {
		foreach($this->replacements_english as $key => $value) {
			if(rex_addon::get('sprog')->isAvailable()) {
				// Delete 
				$query = "DELETE FROM ". rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."'";
				if($clang_id > 0) {
					$query .= " AND clang_id = ". $clang_id;
				}
				$select = rex_sql::factory();
				$select->setQuery($query);
			}
		}
	}
}