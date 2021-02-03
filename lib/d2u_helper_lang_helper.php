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
		'd2u_helper_consent_manager_cookiegroup_necessary' => 'Required',
		'd2u_helper_consent_manager_cookiegroup_necessary_description' => 'Necessary cookies enable basic functions and are necessary for the website to function properly.',
		'd2u_helper_consent_manager_cookiegroup_statistics' => 'Statistics',
		'd2u_helper_consent_manager_cookiegroup_statistics_description' => 'Statistics cookies collect information anonymously. This information helps us to understand how our visitors use our website.',
		'd2u_helper_consent_manager_cookies_day' => 'day',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_ga' => 'Stores an anonymous ID for every visitor to the website. Using the ID, page views can be assigned to a visitor.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gat' => 'Prevents data from being transferred to the Analytics server in too rapid a sequence.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gid' => 'Stores an anonymous ID for every visitor to the website. Using the ID, page views can be assigned to a visitor.',
		'd2u_helper_consent_manager_cookies_googleanalytics_name' => 'Google Analytics',
		'd2u_helper_consent_manager_cookies_cm_cookie' => 'Saves your selection regarding cookies.',
		'd2u_helper_consent_manager_cookies_cm_name' => 'Privacy cookie',
		'd2u_helper_consent_manager_cookies_website' => 'this website',
		'd2u_helper_consent_manager_cookies_year' => 'year',
		'd2u_helper_consent_manager_cookies_years' => 'years',
		'd2u_helper_consent_manager_template_edit_cookiesettings' => 'Edit cookie settings',
		'd2u_helper_consent_manager_text_button_accept' => 'Confirm selection',
		'd2u_helper_consent_manager_text_button_select_all' => 'Select all',
		'd2u_helper_consent_manager_text_description' => 'We use cookies to offer you an optimal website experience. These include cookies that are necessary for the operation of the site and for the control of our commercial corporate goals, as well as those that are only used for anonymous statistical purposes, for comfort settings or to display personalized content. You can decide which categories you want to allow. Please note that based on your settings, not all functions of the site may be available.',
		'd2u_helper_consent_manager_text_headline' => 'Cookie management',
		'd2u_helper_consent_manager_text_lifetime' => 'Period of validity',
		'd2u_helper_consent_manager_text_link_privacy' => 'Privacy policy',
		'd2u_helper_consent_manager_text_provider' => 'Provider',
		'd2u_helper_consent_manager_text_toggle_details' => 'Show / hide details',
		'd2u_helper_module_06_gdpr_hint' => 'Data protection notice: if you let this video play, it will be retrieved from Youtube. By doing so, you acknowledge the <a href="https://policies.google.com/privacy" target="_blank"> Google terms of use and data protection declaration</a>.',
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
		'd2u_helper_module_11_validate_email' => 'Please enter a valid email address.',
		'd2u_helper_module_11_validate_message' => 'Please enter a message.',
		'd2u_helper_module_11_validate_name' => 'Please enter your full name.',
		'd2u_helper_module_11_validate_phone' => 'Please enter your phone number.',
		'd2u_helper_module_11_validate_privacy_policy' => 'It\'s necessary to accept the privacy policy.',
		'd2u_helper_module_11_validate_spambots' => 'Too fast - it seems you are a spambot. Please take your time to fill out all all fields or call us.',
		'd2u_helper_module_11_validate_spam_detected' => 'Your request was recognized as spam and deleted. Please try again in a few minutes or contact us personally.',
		'd2u_helper_module_11_validate_title' => 'Failure sending message:',
		'd2u_helper_module_11_zip' => 'ZIP Code',
		'd2u_helper_module_14_enter_search_term' => 'Enter a search term',
		'd2u_helper_module_14_search_results' => 'Search results',
		'd2u_helper_module_14_search_results_none' => 'No search results found.',
		'd2u_helper_module_14_search_similarity' => 'Similar search with hits',
		'd2u_helper_module_14_search_template_address' => 'Address',
		'd2u_helper_module_14_search_template_ceo' => 'Managing director',
		'd2u_helper_module_14_search_template_contact' => 'Contact',
		'd2u_helper_module_14_search_template_links' => 'Links',
		'd2u_helper_module_14_validate_spam_detected' => 'Your request was recognized as spam and rejected. You were either too fast or there were too many requests from your IP address. Please take a few seconds to fill out the form or try again later.',
	];
	
	/**
	 * @var string[] Array with french replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_french = [
		'd2u_helper_consent_manager_cookiegroup_necessary' => 'Nécessaire',
		'd2u_helper_consent_manager_cookiegroup_necessary_description' => 'Des cookies nécessaires activent les fonctions de base et sont indispensables au bon fonctionnement du site Web.',
		'd2u_helper_consent_manager_cookiegroup_statistics' => 'Statistique',
		'd2u_helper_consent_manager_cookiegroup_statistics_description' => 'Les cookies de statistique collectent anonymement des informations qui nous permettent d´étudier l´utilisation de notre site web',
		'd2u_helper_consent_manager_cookies_day' => 'Jour',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_ga' => 'Stocke un identifiant anonyme pour chaque visiteur du site Web, ce qui permet d´attribuer les pages visitées aus visiteurs respectifs.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gat' => 'Empêche le transfert de données vers le serveur Analytics dans une séquence trop rapide.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gid' => 'Stocke un identifiant anonyme pour chaque visiteur du site Web, ce qui permet d´attribuer les pages visitées aus visiteurs respectifs.',
		'd2u_helper_consent_manager_cookies_googleanalytics_name' => 'Analytique de Google',
		'd2u_helper_consent_manager_cookies_cm_cookie' => 'Enregistre vos préférences de cookies.',
		'd2u_helper_consent_manager_cookies_cm_name' => 'Cookie de confidentialité',
		'd2u_helper_consent_manager_cookies_website' => 'ce site',
		'd2u_helper_consent_manager_cookies_year' => 'Année',
		'd2u_helper_consent_manager_cookies_years' => 'Années',
		'd2u_helper_consent_manager_template_edit_cookiesettings' => 'Modifier les paramètres des cookies',
		'd2u_helper_consent_manager_text_button_accept' => 'confirmer la sélection',
		'd2u_helper_consent_manager_text_button_select_all' => 'Tout sélectionner',
		'd2u_helper_consent_manager_text_description' => "Nous utilisons des cookies pour vous offrir une expérience Web optimale. Ceux-ci incluent les cookies nécessaires au fonctionnement du site et au contrôle de nos objectifs commerciaux, ainsi que ceux qui ne sont utilisés qu'à des fins statistiques anonymes, pour des paramètres de confort ou pour afficher un contenu personnalisé. Vous pouvez décider des catégories que vous souhaitez autoriser. Veuillez noter qu'en fonction de vos paramètres, toutes les fonctions du site peuvent ne pas être disponibles.",
		'd2u_helper_consent_manager_text_headline' => 'Gestion des cookies',
		'd2u_helper_consent_manager_text_lifetime' => "Durée d´utilisation",
		'd2u_helper_consent_manager_text_link_privacy' => 'Déclaration de confidentialité sur la protection de données',
		'd2u_helper_consent_manager_text_provider' => 'Prestataire',
		'd2u_helper_consent_manager_text_toggle_details' => 'Afficher / masquer les détails',
		'd2u_helper_module_06_gdpr_hint' => 'Avis de protection des données: si vous lisez cette vidéo, elle sera sera accessible par YouTube. Vous acceptez les <a href="https://policies.google.com/privacy" target="_blank"> conditions d\'utilisation et la politique de confidalité de Google.</a>.',
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
		'd2u_helper_module_11_validate_email' => 'Veuillez indiquer votre adresse email.',
		'd2u_helper_module_11_validate_message' => 'S\'il vous plaît entrer un message.',
		'd2u_helper_module_11_validate_name' => 'Veuillez indiquer votre nom',
		'd2u_helper_module_11_validate_phone' => 'Veuillez indiquer votre numero téléphone.',
		'd2u_helper_module_11_validate_privacy_policy' => 'La politique de confidentialité doit être approuvée.',
		'd2u_helper_module_11_validate_spambots' => 'Vous avez rempli le formulaire aussi vite que seul un spambot peut. S\'il vous plaît, accordez-vous un peu plus de temps pour remplir les champs.',
		'd2u_helper_module_11_validate_spam_detected' => 'Votre demande a été reconnue comme spam et supprimée. Veuillez réessayer dans quelques minutes ou nous contacter personnellement.',
		'd2u_helper_module_11_validate_title' => 'Erreur lors de l\'envoi:',
		'd2u_helper_module_11_zip' => 'Code postal',
		'd2u_helper_module_14_enter_search_term' => 'Entrez le terme de recherche',
		'd2u_helper_module_14_search_results' => 'Résultats de recherche',
		'd2u_helper_module_14_search_results_none' => "Aucun résultat de recherche n'a été trouvé.",
		'd2u_helper_module_14_search_similarity' => 'Recherche similaire avec résultats',
		'd2u_helper_module_14_search_template_address' => 'Adresse',
		'd2u_helper_module_14_search_template_ceo' => 'Directeurs généraux',
		'd2u_helper_module_14_search_template_contact' => 'Contact',
		'd2u_helper_module_14_search_template_links' => 'Liens',
		'd2u_helper_module_14_validate_spam_detected' => 'Votre demande a été reconnue comme spam et rejetée. Vous étiez soit trop rapide, soit il y avait trop de demandes de votre adresse IP. Veuillez prendre quelques secondes pour remplir le formulaire et réessayer plus tard.',
	];
	
	/**
	 * @var string[] Array with german replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_german = [
		'd2u_helper_consent_manager_cookiegroup_necessary' => 'Notwendig',
		'd2u_helper_consent_manager_cookiegroup_necessary_description' => 'Notwendige Cookies ermöglichen grundlegende Funktionen und sind für die einwandfreie Funktion der Website erforderlich.',
		'd2u_helper_consent_manager_cookiegroup_statistics' => 'Statistik',
		'd2u_helper_consent_manager_cookiegroup_statistics_description' => 'Statistik Cookies erfassen Informationen anonym. Diese Informationen helfen uns zu verstehen, wie unsere Besucher unsere Website nutzen.',
		'd2u_helper_consent_manager_cookies_day' => 'Tag',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_ga' => 'Speichert für jeden Besucher der Website eine anonyme ID. Anhand der ID können Seitenaufrufe einem Besucher zugeordnet werden.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gat' => 'Verhindert, dass in zu schneller Folge Daten an den Analytics Server übertragen werden.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gid' => 'Speichert für jeden Besucher der Website eine anonyme ID. Anhand der ID können Seitenaufrufe einem Besucher zugeordnet werden.',
		'd2u_helper_consent_manager_cookies_googleanalytics_name' => 'Google Analytics',
		'd2u_helper_consent_manager_cookies_cm_cookie' => 'Speichert Ihre Auswahl bzgl. der Cookies.',
		'd2u_helper_consent_manager_cookies_cm_name' => 'Datenschutz Cookie',
		'd2u_helper_consent_manager_cookies_website' => 'diese Webseite',
		'd2u_helper_consent_manager_cookies_year' => 'Jahr',
		'd2u_helper_consent_manager_cookies_years' => 'Jahre',
		'd2u_helper_consent_manager_template_edit_cookiesettings' => 'Cookie Einstellungen bearbeiten',
		'd2u_helper_consent_manager_text_button_accept' => 'Auswahl bestätigen',
		'd2u_helper_consent_manager_text_button_select_all' => 'Alle auswählen',
		'd2u_helper_consent_manager_text_description' => 'Wir verwenden Cookies, um Ihnen ein optimales Webseiten-Erlebnis zu bieten. Dazu zählen Cookies, die für den Betrieb der Seite und für die Steuerung unserer kommerziellen Unternehmensziele notwendig sind, sowie solche, die lediglich zu anonymen Statistikzwecken, für Komforteinstellungen oder zur Anzeige personalisierter Inhalte genutzt werden. Sie können selbst entscheiden, welche Kategorien Sie zulassen möchten. Bitte beachten Sie, dass auf Basis Ihrer Einstellungen womöglich nicht mehr alle Funktionalitäten der Seite zur Verfügung stehen.',
		'd2u_helper_consent_manager_text_headline' => 'Cookie-Verwaltung',
		'd2u_helper_consent_manager_text_lifetime' => 'Laufzeit',
		'd2u_helper_consent_manager_text_link_privacy' => 'Datenschutzerklärung',
		'd2u_helper_consent_manager_text_provider' => 'Anbieter',
		'd2u_helper_consent_manager_text_toggle_details' => 'Details anzeigen/ausblenden',
		'd2u_helper_module_06_gdpr_hint' => 'Datenschutz Hinweis: wenn Sie dieses Video abspielen lassen, wird es von Youtube abgerufen. Sie anerkennen damit die <a href="https://policies.google.com/privacy" target="_blank">Google Nutzungsbedingungen und Datenschutzerklärung</a>.',
		'd2u_helper_module_11_city' => 'Ort',
		'd2u_helper_module_11_contact_request' => 'Kontaktanfrage',
		'd2u_helper_module_11_contact_request_intro' => 'In das Anfrage-Formular wurden folgende Werte eingetragen',
		'd2u_helper_module_11_email' => 'E-Mail-Adresse',
		'd2u_helper_module_11_message' => 'Nachricht',
		'd2u_helper_module_11_name' => 'Name',
		'd2u_helper_module_11_phone' => 'Telefon',
		'd2u_helper_module_11_privacy_policy' => 'Ich akzeptiere die <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Datenschutzerklärung</a>. Meine Daten werden nicht an Dritte weitergegeben. Ich habe das Recht auf Löschung meiner Daten. Diese kann ich jederzeit unter den im <a href="+++LINK_IMPRESS+++" target="_blank">Impressum</a> angegebenen Kontaktdaten verlangen.',
		'd2u_helper_module_11_required' => 'Pflichtfelder',
		'd2u_helper_module_11_send' => 'Abschicken',
		'd2u_helper_module_11_street' => 'Straße',
		'd2u_helper_module_11_thanks' => 'Danke für Ihr Nachricht. Wir werden uns schnellst möglich um Ihr Anliegen kümmern.',
		'd2u_helper_module_11_validate_email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
		'd2u_helper_module_11_validate_message' => 'Bitte geben Sie eine Nachricht ein.',
		'd2u_helper_module_11_validate_name' => 'Um Sie korrekt ansprechen zu können, geben Sie bitte Ihren vollständigen Namen an.',
		'd2u_helper_module_11_validate_phone' => 'Bitte geben Sie Ihre Telefonnummer an.',
		'd2u_helper_module_11_validate_privacy_policy' => 'Der Datenschutzerklärung muss zugestimmt werden.',
		'd2u_helper_module_11_validate_spambots' => 'Spamverdacht: Sie haben das Formular so schnell ausgefüllt, wie es nur ein Spambot tun kann. Bitte lassen Sie sich beim ausfüllen der Felder etwas mehr Zeit.',
		'd2u_helper_module_11_validate_spam_detected' => 'Ihre Anfrage wurde als Spam eingestuft und verworfen. Sie waren entweder zu schnell oder es wurden zu viele Anfragen von Ihrer IP-Adresse registriert. Bitte versuchen Sie es später oder wenden Sie sich persönlich an uns.',
		'd2u_helper_module_11_validate_title' => 'Fehler beim Senden:',
		'd2u_helper_module_11_zip' => 'Postleitzahl',
		'd2u_helper_module_14_enter_search_term' => 'Suchbegriff eingeben',
		'd2u_helper_module_14_search_results' => 'Suchergebnisse',
		'd2u_helper_module_14_search_results_none' => 'Es wurden keine Suchergebnisse gefunden.',
		'd2u_helper_module_14_search_similarity' => 'Ähnliche Suche mit Treffern',
		'd2u_helper_module_14_search_template_address' => 'Adresse',
		'd2u_helper_module_14_search_template_ceo' => 'Geschäftsführung',
		'd2u_helper_module_14_search_template_contact' => 'Kontakt',
		'd2u_helper_module_14_search_template_links' => 'Links',
		'd2u_helper_module_14_validate_spam_detected' => 'Ihre Anfrage wurde als Spam eingestuft und verworfen. Sie waren entweder zu schnell oder es wurden zu viele Anfragen von Ihrer IP-Adresse registriert. Bitte lassen Sie sich beim Ausfüllen des Formulars ein paar Sekunden Zeit oder versuchen Sie es später erneut.',
	];
	
	/**
	 * @var string[] Array with spanish replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_spanish = [
		'd2u_helper_consent_manager_cookiegroup_necessary' => 'Necesario',
		'd2u_helper_consent_manager_cookiegroup_necessary_description' => 'Las cookies necesarias habilitan funciones básicas y son necesarias para que el sitio web funcione correctamente.',
		'd2u_helper_consent_manager_cookiegroup_statistics' => 'Estadísticas',
		'd2u_helper_consent_manager_cookiegroup_statistics_description' => 'Las cookies de estadísticas recopilan información de forma anónima. Esta información nos ayuda a comprender cómo nuestros visitantes usan nuestro sitio web.',
		'd2u_helper_consent_manager_cookies_day' => 'día',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_ga' => 'Almacena una identificación anónima para cada visitante del sitio web. Usando la ID, se pueden asignar vistas de página a un visitante.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gat' => 'Impide que los datos se transfieran al servidor de Analytics en una secuencia demasiado rápida.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gid' => 'Almacena una identificación anónima para cada visitante del sitio web. Usando la ID, se pueden asignar vistas de página a un visitante.',
		'd2u_helper_consent_manager_cookies_googleanalytics_name' => 'Google Analytics',
		'd2u_helper_consent_manager_cookies_cm_cookie' => 'Guarda su selección relativa a cookies.',
		'd2u_helper_consent_manager_cookies_cm_name' => 'Ccookie de privacidad',
		'd2u_helper_consent_manager_cookies_website' => 'este website',
		'd2u_helper_consent_manager_cookies_year' => 'año',
		'd2u_helper_consent_manager_cookies_years' => 'años',
		'd2u_helper_consent_manager_template_edit_cookiesettings' => 'Editar configuración de cookies',
		'd2u_helper_consent_manager_text_button_accept' => 'Confirmar selección',
		'd2u_helper_consent_manager_text_button_select_all' => 'Seleccionar todo',
		'd2u_helper_consent_manager_text_description' => 'Utilizamos cookies para ofrecerle una experiencia óptima en el sitio web. Estas incluyen cookies que son necesarias para el funcionamiento del sitio y para el control de nuestros objetivos corporativos comerciales, así como aquellas que solo se utilizan con fines estadísticos anónimos, para configuraciones de comodidad o para mostrar contenido personalizado. Puedes decidir qué categorías quieres permitir. Tenga en cuenta que, según su configuración, no todas las funciones de la web pueden estar disponibles.',
		'd2u_helper_consent_manager_text_headline' => 'Gestión de cookies',
		'd2u_helper_consent_manager_text_lifetime' => 'Periodo de validez',
		'd2u_helper_consent_manager_text_link_privacy' => 'Política de privacidad',
		'd2u_helper_consent_manager_text_provider' => 'Suministrador',
		'd2u_helper_consent_manager_text_toggle_details' => 'Mostrar / ocultar detalles',
		'd2u_helper_module_06_gdpr_hint' => 'Aviso de protección de datos: si reproduce este video, YouTube accederá a él. Acepta las <a href="https://policies.google.com/privacy" target="_blank">condiciones de uso y la política de privacidad de Google</a>.',
		'd2u_helper_module_11_city' => 'Ciudad',
		'd2u_helper_module_11_contact_request' => 'Solicitud de contacto',
		'd2u_helper_module_11_contact_request_intro' => 'Se introducen los siguientes valores en el formulario de solicitud',
		'd2u_helper_module_11_email' => 'Dirección e-mail',
		'd2u_helper_module_11_message' => 'Mensaje',
		'd2u_helper_module_11_name' => 'Nombre',
		'd2u_helper_module_11_phone' => 'Teléfono',
		'd2u_helper_module_11_privacy_policy' => 'Tomo nota de <a href="+++LINK_PRIVACY_POLICY+++" target="_blank">Privacy Policy</a>. Mis datos no se proporcionarán a terceras personas. Conozco los derechos de protección de mis datos. Puedo solicitar el borrado en cualquier momento desde los detalles de contado aportados en <a href="+++LINK_IMPRESS+++" target="_blank">impress</a>.',
		'd2u_helper_module_11_required' => 'Necesario',
		'd2u_helper_module_11_send' => 'Enviar',
		'd2u_helper_module_11_street' => 'Calle',
		'd2u_helper_module_11_thanks' => 'Gracias por su solicitud, le contestaremos lo antes posible.',
		'd2u_helper_module_11_validate_email' => 'Por favor, introduzca una dirección email válida.',
		'd2u_helper_module_11_validate_message' => 'Por favor, introduzca un mensaje.',
		'd2u_helper_module_11_validate_name' => 'Por favor, introduzca su nombre completo.',
		'd2u_helper_module_11_validate_phone' => 'Por favor, introduzca su número de teléfono.',
		'd2u_helper_module_11_validate_privacy_policy' => 'Se necesita aprobar la política de privacidad.',
		'd2u_helper_module_11_validate_spambots' => 'Demasiado rápido - pareciera que es un robot. Por favor, tome su tiempo para rellenar todos los datos.',
		'd2u_helper_module_11_validate_spam_detected' => 'Su solicitud fue reconocida como spam y eliminada. Inténtelo de nuevo en unos minutos o contáctenos personalmente.',
		'd2u_helper_module_11_validate_title' => 'Fallo al enviar el mensaje:',
		'd2u_helper_module_11_zip' => 'Código Postal',
		'd2u_helper_module_14_enter_search_term' => 'Introduzca artículo de búsqueda',
		'd2u_helper_module_14_search_results' => 'Resultado de búsqueda',
		'd2u_helper_module_14_search_results_none' => 'Sin resultado de búsqueda',
		'd2u_helper_module_14_search_similarity' => 'Búsqueda similar',
		'd2u_helper_module_14_search_template_address' => 'Alocución',
		'd2u_helper_module_14_search_template_ceo' => 'Directores generales',
		'd2u_helper_module_14_search_template_contact' => 'Contacto',
		'd2u_helper_module_14_search_template_links' => 'Enlaces de internet',
		'd2u_helper_module_14_validate_spam_detected' => 'Su solicitud fue reconocida como spam y rechazada. O fue demasiado rápido o hubo demasiadas solicitudes de su dirección IP. Tómese unos segundos para completar el formulario y vuelva a intentarlo más tarde.',
	];
	
	/**
	 * @var string[] Array with russian replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_russian = [
		'd2u_helper_consent_manager_cookiegroup_necessary' => 'Обязательные',
		'd2u_helper_consent_manager_cookiegroup_necessary_description' => 'Обязательные файлы cookies необходимы в технических целях для корректного функционирования нашего сайта.',
		'd2u_helper_consent_manager_cookiegroup_statistics' => 'Статистические',
		'd2u_helper_consent_manager_cookiegroup_statistics_description' => 'Статистические файлы cookies  используется для хранения анонимной информации о пользователе. Получаемая информация используется для сбора данных о том, как посетители используют наш веб-сайт.',
		'd2u_helper_consent_manager_cookies_day' => 'день',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_ga' => 'Сохраняет анонимный ID для каждого посетителя веб-сайта. ID позволяет сохранять Ваши предпочтения на нашем веб-сайте.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gat' => 'Предотвращение слишком быстрой передачи данных на сервер Analytics.',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gid' => 'Сохраняет анонимный ID для каждого посетителя веб-сайта. ID позволяет сохранять Ваши предпочтения на нашем веб-сайте.',
		'd2u_helper_consent_manager_cookies_googleanalytics_name' => 'Google Analytics',
		'd2u_helper_consent_manager_cookies_cm_cookie' => 'Сохраните выбранные Вами настройки файлов cookies',
		'd2u_helper_consent_manager_cookies_cm_name' => 'Конфиденциальность файлов cookies',
		'd2u_helper_consent_manager_cookies_website' => 'этот веб-сайт',
		'd2u_helper_consent_manager_cookies_year' => 'год',
		'd2u_helper_consent_manager_cookies_years' => 'года',
		'd2u_helper_consent_manager_template_edit_cookiesettings' => 'Редактировать настройки файлов cookies',
		'd2u_helper_consent_manager_text_button_accept' => 'подтвердить выбор',
		'd2u_helper_consent_manager_text_button_select_all' => 'Выделить все',
		'd2u_helper_consent_manager_text_description' => 'Мы используем файлы cookies для того, чтобы предоставить Вам больше возможностей при использовании сайта. Файлы cookies — это небольшие файлы, которые загружаются на ваш компьютер/устройство и помогают обеспечить нормальную и безопасную работу веб-сайта, а также позволяют собирать информацию о продуктах, которыми интересуются посетители сайта, в том числе для контроля наших коммерческих корпоративных целей, которые используются только в целях статистики, с сохранением полной анонимности собираемой информации, и для обеспечения удобства настроек или для отображения персонализированного контента. Вы сможете выбрать типы файлов cookies, которые Вы принимаете. Обратите внимание, что в зависимости от ваших настроек могут быть доступны не все функции сайта.',
		'd2u_helper_consent_manager_text_headline' => 'Управление файлами cookies',
		'd2u_helper_consent_manager_text_lifetime' => 'Срок действия:',
		'd2u_helper_consent_manager_text_link_privacy' => 'Политика конфиденциальности',
		'd2u_helper_consent_manager_text_provider' => 'Провайдер:',
		'd2u_helper_consent_manager_text_toggle_details' => 'Показать/ скрыть сведения',
		'd2u_helper_module_06_gdpr_hint' => 'Уведомление о защите данных: если вы воспроизведете это видео, YouTube получит доступ к нему. <a href="https://policies.google.com/privacy" target="_blank">Вы принимаете условия использования и политику конфиденциальности Google</a>.',
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
		'd2u_helper_module_11_validate_spam_detected' => 'Ваше сообщение было классифицировано как спам, потому что невидимые поля были заполнены.',
		'd2u_helper_module_11_validate_title' => 'Ошибка при отправке:',
		'd2u_helper_module_11_zip' => 'Индекс',
		'd2u_helper_module_14_enter_search_term' => 'Введите поисковый запрос',
		'd2u_helper_module_14_search_results' => 'Результаты поиска',
		'd2u_helper_module_14_search_results_none' => 'Результаты поиска не найдены.',
		'd2u_helper_module_14_search_similarity' => 'Подобный поиск с хитами',
		'd2u_helper_module_14_search_template_address' => 'Aдрес',
		'd2u_helper_module_14_search_template_ceo' => 'Управляющие директора',
		'd2u_helper_module_14_search_template_contact' => 'Связаться с нами',
		'd2u_helper_module_14_search_template_links' => 'Интернет-ссылки',
		'd2u_helper_module_14_validate_spam_detected' => 'Ваш запрос был признан спамом и отклонен. Либо вы действовали слишком быстро, либо с вашего IP-адреса поступило слишком много запросов. Пожалуйста, уделите несколько секунд, чтобы заполнить форму, и повторите попытку позже.',
	];
	
	/**
	 * @var string[] Array with chinese replacements. Key is the wildcard,
	 * value the replacement. 
	 */
	protected $replacements_chinese = [
		'd2u_helper_consent_manager_cookiegroup_necessary' => '必须',
		'd2u_helper_consent_manager_cookiegroup_necessary_description' => '必要的cookie启用基本功能，并且是网站正常运行所必需的。',
		'd2u_helper_consent_manager_cookiegroup_statistics' => '统计',
		'd2u_helper_consent_manager_cookiegroup_statistics_description' => '统计cookies匿名收集信息。这些信息有助于我们了解访客如何使用我们的网站。',
		'd2u_helper_consent_manager_cookies_day' => '日',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_ga' => '为网站的每个访问者存储一个匿名ID。使用ID，可以将页面视图分配给访问者。',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gat' => '防止数据以太快的顺序传输到分析服务器。',
		'd2u_helper_consent_manager_cookies_googleanalytics_cookie_gid' => '为网站的每个访问者存储一个匿名ID。使用ID，可以将页面视图分配给访问者。',
		'd2u_helper_consent_manager_cookies_googleanalytics_name' => '谷歌分析',
		'd2u_helper_consent_manager_cookies_cm_cookie' => '保存您对cookies的选择。',
		'd2u_helper_consent_manager_cookies_cm_name' => '隐私cookie',
		'd2u_helper_consent_manager_cookies_website' => '网页',
		'd2u_helper_consent_manager_cookies_year' => '年',
		'd2u_helper_consent_manager_cookies_years' => '年',
		'd2u_helper_consent_manager_template_edit_cookiesettings' => '编辑cookie设置',
		'd2u_helper_consent_manager_text_button_accept' => '确认选择',
		'd2u_helper_consent_manager_text_button_select_all' => '全选',
		'd2u_helper_consent_manager_text_description' => '我们使用cookies为您提供最佳的网站体验。其中包括网站运营和把控商业公司目标所必需的cookie，以及仅用于匿名统计、舒适设置或显示个性化内容的cookie。您可以决定要允许哪些类别。请注意，根据您的设置，并非网站的所有功能都可用。',
		'd2u_helper_consent_manager_text_headline' => 'Cookie管理',
		'd2u_helper_consent_manager_text_lifetime' => '有效期',
		'd2u_helper_consent_manager_text_link_privacy' => '隐私政策',
		'd2u_helper_consent_manager_text_provider' => '供应者',
		'd2u_helper_consent_manager_text_toggle_details' => '显示 / 隐藏细节',
		'd2u_helper_module_06_gdpr_hint' => '数据保护声明：如果您播放此视频，YouTube将会对其进行访问。<a href="https://policies.google.com/privacy" target="_blank">您接受Google使用条款和隐私权政策。</a>.',
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
		'd2u_helper_module_11_street' => '街道',
		'd2u_helper_module_11_thanks' => '感谢您的留言。 我们会尽快处理您的请求。',
		'd2u_helper_module_11_validate_email' => '请输入您的电子邮箱地址。',
		'd2u_helper_module_11_validate_message' => '请输入消息。',
		'd2u_helper_module_11_validate_name' => '请输入您的全名。',
		'd2u_helper_module_11_validate_phone' => ' 	请输入您的电话号码。',
		'd2u_helper_module_11_validate_privacy_policy' => '隐私政策必须获得批准',
		'd2u_helper_module_11_validate_spambots' => '您填写表格的速度只有spambot可以。 填写字段时请多留点时间。',
		'd2u_helper_module_11_validate_spam_detected' => '您的邮件被归类为垃圾邮件，因为未填写可见字段。',
		'd2u_helper_module_11_validate_title' => '发送时出错:',
		'd2u_helper_module_11_zip' => '邮政编码',
		'd2u_helper_module_14_enter_search_term' => '输入搜索词',
		'd2u_helper_module_14_search_results' => '搜索结果',
		'd2u_helper_module_14_search_results_none' => '找不到搜索结果。',
		'd2u_helper_module_14_search_similarity' => '与搜寻相似的搜寻',
		'd2u_helper_module_14_search_template_address' => '地址',
		'd2u_helper_module_14_search_template_ceo' => '常务董事',
		'd2u_helper_module_14_search_template_contact' => '联系',
		'd2u_helper_module_14_search_template_links' => '互联网链接',
		'd2u_helper_module_14_validate_spam_detected' => '您的请求被确认为垃圾邮件，但被拒绝了。 您的速度太快，或者来自IP地址的请求太多。 请花几秒钟填写表格，然后重试。',
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
				else if($lang_replacement === 'spanish' && isset($this->replacements_spanish) && isset($this->replacements_spanish[$key])) {
					$value = $this->replacements_spanish[$key];
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