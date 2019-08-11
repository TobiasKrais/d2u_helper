<?php
/**
 * Offers helper functions for language issues
 */
class d2u_helper_lang_helper extends \D2U_Helper\ALangHelper {
	/**
	 * @var string[] Array with english replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_english = [
		'd2u_helper_module_11_city' => 'City',
		'd2u_helper_module_11_contact_request' => 'Contact request',
		'd2u_helper_module_11_contact_request_intro' => 'The following values were entered in the request form:',
		'd2u_helper_module_11_email' => 'E-mail address',
		'd2u_helper_module_11_message' => 'Message',
		'd2u_helper_module_11_name' => 'Name',
		'd2u_helper_module_11_phone' => 'Phone',
		'd2u_helper_module_11_privacy_policy' => 'I accept the <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Privacy Policy</a>. My data will not be disclosed to third parties. I know about the right of deletion of my data. I can request deletion at any time from the contact details provided in the <a href="+++LINK_IMPRESS+++" target="_blank">impress</a>.',
		'd2u_helper_module_11_required' => 'Required',
		'd2u_helper_module_11_send' => 'Send',
		'd2u_helper_module_11_street' => 'Street',
		'd2u_helper_module_11_thanks' => 'Thank you for request. we will answer as soon as possible.',
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
		'd2u_helper_module_11_city' => 'Ort',
		'd2u_helper_module_11_contact_request' => 'Kontaktanfrage',
		'd2u_helper_module_11_contact_request_intro' => 'In das Anfrage-Formular wurden folgende Werte eingetragen',
		'd2u_helper_module_11_email' => 'E-Mail Adresse',
		'd2u_helper_module_11_message' => 'Nachricht',
		'd2u_helper_module_11_name' => 'Name',
		'd2u_helper_module_11_phone' => 'Telefon',
		'd2u_helper_module_11_privacy_policy' => 'Ich akzeptiere die <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Datenschutzerklärung</a>. Meine Daten werden nicht an Dritte weitergegeben. Ich habe das Recht auf Löschung meiner Daten. Diese kann ich jederzeit unter den im <a href="+++LINK_IMPRESS+++" target="_blank">Impressum</a> angegebenen Kontaktdaten verlangen.',
		'd2u_helper_module_11_required' => 'Pflichtfelder',
		'd2u_helper_module_11_send' => 'Abschicken',
		'd2u_helper_module_11_street' => 'Straße',
		'd2u_helper_module_11_thanks' => 'Danke für Ihr Nachricht. Wir werden uns schnellst möglich um Ihr Anliegen kümmern.',
		'd2u_helper_module_11_validate_message' => 'Bitte geben Sie eine Nachricht ein.',
		'd2u_helper_module_11_validate_email' => 'Bitte geben Sie eine gültige E-Mailadresse ein.',
		'd2u_helper_module_11_validate_name' => 'Um Sie korrekt ansprechen zu können, geben Sie bitte Ihren vollständigen Namen an.',
		'd2u_helper_module_11_validate_phone' => 'Bitte geben Sie Ihre Telefonnummer an.',
		'd2u_helper_module_11_validate_privacy_policy' => 'Der Datenschutzerklärung muss zugestimmt werden.',
		'd2u_helper_module_11_validate_spambots' => 'Spamverdacht: Sie haben das Formular so schnell ausgefüllt, wie es nur ein Spambot tun kann. Bitte lassen Sie sich beim ausfüllen der Felder etwas mehr Zeit.',
		'd2u_helper_module_11_validate_title' => 'Fehler beim Senden:',
		'd2u_helper_module_11_zip' => 'Postleitzahl',
	];
	
	/**
	 * @var string[] Array with russian replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_russian = [
		'd2u_helper_module_11_city' => 'Город',
		'd2u_helper_module_11_contact_request' => 'Запрос контакта',
		'd2u_helper_module_11_contact_request_intro' => 'В форму запроса были введены следующие данные:',
		'd2u_helper_module_11_email' => 'Электронная почта',
		'd2u_helper_module_11_message' => 'Примечание',
		'd2u_helper_module_11_name' => 'Фамилия',
		'd2u_helper_module_11_phone' => 'Телефон',
		'd2u_helper_module_11_privacy_policy' => 'Я принимаю <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">политику конфиденциальности</a>. Мои данные не будут раскрыты третьим лицам. Я имею право удалить свои данные. Я могу запросить их в любое время из контактной информации, указанной в <a href="+++LINK_IMPRESS+++" target="_blank">разделе</a>',
		'd2u_helper_module_11_required' => 'поля, обязательные для заполнения',
		'd2u_helper_module_11_send' => 'Отправить',
		'd2u_helper_module_11_street' => 'Адрес',
		'd2u_helper_module_11_thanks' => 'Благодарим за Ваш запрос. Наши специалисты ответят Вам в ближайшее время.',
		'd2u_helper_module_11_validate_email' => 'Пожалуйста, укажите правильный адрес электронной почты.',
		'd2u_helper_module_11_validate_message' => 'Введите сообщение',
		'd2u_helper_module_11_validate_name' => 'Введите свое полное имя',
		'd2u_helper_module_11_validate_phone' => 'Пожалуйста, введите Ваш номер телефона.',
		'd2u_helper_module_11_validate_privacy_policy' => 'Политика конфиденциальности должна быть одобрена',
		'd2u_helper_module_11_validate_spambots' => 'Мы зарегистрировали подозрительный трафик, исходящий из Вашей сети. Пожалуйста, заполните форму ещё раз и подтвердите, что Вы не спам-бот.',
		'd2u_helper_module_11_validate_title' => 'Ошибка при отправке:',
		'd2u_helper_module_11_zip' => 'Индекс',
	];
	
	/**
	 * @var string[] Array with chinese replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_chinese = [
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
		return new self();
	}
	
	/**
	 * Installs the replacement table for this addon.
	 */
	public function install() {
		foreach($this->replacements_english as $key => $value) {
			foreach (rex_clang::getAllIds() as $clang_id) {
				$lang_replacement = rex_config::get('d2u_helper', 'lang_replacement_'. $clang_id, '');

				// Load values for input
				if($lang_replacement === 'german' && isset($this->replacements_german) && isset($this->replacements_german[$key])) {
					$value = $this->replacements_german[$key];
				}
				else if($lang_replacement === 'chinese' && isset($this->replacements_chinese) && isset($this->replacements_chinese[$key])) {
					$value = $this->replacements_chinese[$key];
				}
				else if($lang_replacement === 'french' && isset($this->replacements_french) && isset($this->replacements_french[$key])) {
					$value = $this->replacements_french[$key];
				}
				else if($lang_replacement === 'russian' && isset($this->replacements_russian) && isset($this->replacements_russian[$key])) {
					$value = $this->replacements_russian[$key];
				}
				else { 
					$value = $this->replacements_english[$key];
				}

				$overwrite = rex_config::get('d2u_helper', 'lang_wildcard_overwrite', FALSE) === "true" ? TRUE : FALSE;
				parent::saveValue($key, $value, $clang_id, $overwrite);
			}
		}
	}
}