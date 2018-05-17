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
		'd2u_helper_module_11_privacy_policy' => 'I accept the <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Privacy Policy</a>. My data will not be disclosed to third parties. I know about the right of deletion of my data. I can request deletion at any time from the contact details provided in the <a href="+++LINK_IMPRESS+++" target="_blank">imprint</a>.',
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
	 * @var string[] Array with french replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_french = [
		'd2u_helper_module_11_captcha' => 'Pour prévenir les abus, nous vous demandons de saisir le captcha suivante.',
		'd2u_helper_module_11_city' => 'Ville',
		'd2u_helper_module_11_contact_request' => 'Demande de contact',
		'd2u_helper_module_11_contact_request_intro' => 'Les valeurs suivantes ont été entrées dans le formulaire de demande',
		'd2u_helper_module_11_email' => 'E-Mail',
		'd2u_helper_module_11_message' => 'Remarques',
		'd2u_helper_module_11_name' => 'Nom',
		'd2u_helper_module_11_phone' => 'Téléphone',
		'd2u_helper_module_11_privacy_policy' => 'J\'accepte la <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">politique de confidentialité</a>. Mes données ne seront pas divulguées à des tiers. J\'ai le droit de supprimer mes données. Je peux les demander à tout moment à partir des coordonnées fournies dans l\'<a href="+++LINK_IMPRESS+++" target="_blank">empreinte</a>.',
		'd2u_helper_module_11_required' => 'Obligatoire',
		'd2u_helper_module_11_send' => 'Envoyer',
		'd2u_helper_module_11_street' => 'Adresse',
		'd2u_helper_module_11_thanks' => 'Merci pour votre message. Nous prendrons soin de votre demande dès que possible.',
		'd2u_helper_module_11_validate_captcha' => 'Le Captcha n\'a pas été lu correctement.',
		'd2u_helper_module_11_validate_message' => 'S\'il vous plaît entrer un message.',
		'd2u_helper_module_11_validate_email' => 'Veuillez indiquer votre adresse email.',
		'd2u_helper_module_11_validate_name' => 'Veuillez indiquer votre nom',
		'd2u_helper_module_11_validate_phone' => 'Veuillez indiquer votre numero téléphone.',
		'd2u_helper_module_11_validate_privacy_policy' => 'La politique de confidentialité doit être approuvée.',
		'd2u_helper_module_11_validate_spambots' => 'Vous avez rempli le formulaire aussi vite que seul un spambot peut. S\'il vous plaît, accordez-vous un peu plus de temps pour remplir les champs.',
		'd2u_helper_module_11_validate_title' => 'Erreur lors de l\'envoi:',
		'd2u_helper_module_11_zip' => 'Code postal',
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
		'd2u_helper_module_11_privacy_policy' => 'Ich akzeptiere die <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Datenschutzerklärung</a>. Meine Daten werden nicht an Dritte weitergegeben. Ich habe das Recht auf Löschung meiner Daten. Diese kann ich jederzeit unter den im <a href="+++LINK_IMPRESS+++" target="_blank">Impressum</a> angegebenen Kontaktdaten verlangen.',
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
	 * @var string[] Array with russian replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_russian = [
		'd2u_helper_module_11_captcha' => 'Для предотвращения злоупотреблений, мы просим Вас ввести следующую капчу.',
		'd2u_helper_module_11_city' => 'Город',
		'd2u_helper_module_11_contact_request' => 'Контакт запрос',
		'd2u_helper_module_11_contact_request_intro' => 'В форму запроса были введены следующие значения:',
		'd2u_helper_module_11_email' => 'Электронная почта',
		'd2u_helper_module_11_message' => 'Примечание',
		'd2u_helper_module_11_name' => 'Фамилия',
		'd2u_helper_module_11_phone' => 'Телефон',
		'd2u_helper_module_11_privacy_policy' => 'Я принимаю <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">политику конфиденциальности</a>. Мои данные не будут раскрыты третьим лицам. Я имею право удалить свои данные. Я могу запросить их в любое время из контактной информации, указанной в <a href="+++LINK_IMPRESS+++" target="_blank">отпечатке</a>.',
		'd2u_helper_module_11_required' => 'обязательное',
		'd2u_helper_module_11_send' => 'Отправить',
		'd2u_helper_module_11_street' => 'Адрес',
		'd2u_helper_module_11_thanks' => 'Большое спасибо за Ваш запрос. Мы обработаем его, как можно быстрее.',
		'd2u_helper_module_11_validate_captcha' => 'Captcha неправильно читается.',
		'd2u_helper_module_11_validate_message' => 'Введите сообщение',
		'd2u_helper_module_11_validate_email' => 'Пожалуйста, укажите правильный адрес электронной почты.',
		'd2u_helper_module_11_validate_name' => 'Введите свое полное имя',
		'd2u_helper_module_11_validate_phone' => 'Пожалуйста, введите Ваш номер телефона.',
		'd2u_helper_module_11_validate_privacy_policy' => 'Политика конфиденциальности должна быть одобрена',
		'd2u_helper_module_11_validate_spambots' => 'Вы заполнили форму так быстро, как только спамбот. Пожалуйста, позвольте немного больше времени при заполнении полей.',
		'd2u_helper_module_11_validate_title' => 'Ошибка при отправке:',
		'd2u_helper_module_11_zip' => 'Почтовый код',
	];
	
	/**
	 * @var string[] Array with chinese replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_chinese = [
		'd2u_helper_module_11_captcha' => '为避免滥用网站，请您输入以下验证码。',
		'd2u_helper_module_11_city' => '所在地',
		'd2u_helper_module_11_contact_request' => '联系方式查询',
		'd2u_helper_module_11_contact_request_intro' => '以下值已在请求表格中输入',
		'd2u_helper_module_11_email' => '电子邮箱地址',
		'd2u_helper_module_11_message' => '信息',
		'd2u_helper_module_11_name' => '名称',
		'd2u_helper_module_11_phone' => '电话号码',
		'd2u_helper_module_11_privacy_policy' => '我接受<a href="+++LINK_PRIVACY_POLICY+++" target="_blank">隐私政策</a>。 我的数据不会透露给第三方。 我有权删除我的数据。 我可以随时通过<a href="+++LINK_IMPRESS+++" target="_blank">版本说明</a>中提供的联系信息申请这些信息。',
		'd2u_helper_module_11_required' => '强制性',
		'd2u_helper_module_11_send' => '发送',
		'd2u_helper_module_11_street' => '街头',
		'd2u_helper_module_11_thanks' => '感谢您的留言。 我们会尽快处理您的请求。',
		'd2u_helper_module_11_validate_captcha' => '验证码错误。',
		'd2u_helper_module_11_validate_message' => '请输入消息。',
		'd2u_helper_module_11_validate_email' => '请输入您的电子邮箱地址。',
		'd2u_helper_module_11_validate_name' => '请输入您的全名。',
		'd2u_helper_module_11_validate_phone' => ' 	请输入您的电话号码。',
		'd2u_helper_module_11_validate_privacy_policy' => '隐私政策必须获得批准',
		'd2u_helper_module_11_validate_spambots' => '您填写表格的速度只有spambot可以。 填写字段时请多留点时间。',
		'd2u_helper_module_11_validate_title' => '发送时出错:',
		'd2u_helper_module_11_zip' => '邮政编码',
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
				else if($d2u_helper->hasConfig('lang_replacement_'. $clang_id) && $d2u_helper->getConfig('lang_replacement_'. $clang_id) == 'chinese') {
					$value = $this->replacements_chinese[$key];
				}
				else if($d2u_helper->hasConfig('lang_replacement_'. $clang_id) && $d2u_helper->getConfig('lang_replacement_'. $clang_id) == 'french') {
					$value = $this->replacements_french[$key];
				}
				else if($d2u_helper->hasConfig('lang_replacement_'. $clang_id) && $d2u_helper->getConfig('lang_replacement_'. $clang_id) == 'russian') {
					$value = $this->replacements_russian[$key];
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