<?php
/**
 * Class managing modules published by www.design-to-use.de
 *
 * @author Tobias Krais
 */
class D2UModuleManager {
	/**
	 * Folder where modules can be found.
	 */
	const MODULE_FOLDER = 'modules/';

	/**
	 * @var D2UModule[] Array with D2U modules
	 */
	var $d2u_modules = [];
	
	/**
	 * @var string Folder within addon, in which modules can be found. Trailing
	 * slash must be included.
	 */
	var $module_folder = "";

	/**
	 * @var rex_addon Redaxo Addon module belongs to
	 */
	private $module_addon;
	
	/**
	 * Constructor. Sets values. The path that is constructed is during addon
	 * update the path of the new addon folder. Otherwise the normal addon path.
	 * @param D2UModule[] $d2u_modules Array with D2U modules
	 * @param string $module_folder Folder, in which modules can be found.
	 * Trailing slash must be included. Default is D2UModuleManager::MODULE_FOLDER.
	 * @param string $addon_key Redaxo Addon name module belongs to, default "d2u_helper"
	 */
	public function __construct($d2u_modules, $module_folder = "", $addon_key = "d2u_helper") {
		$module_folder = $module_folder ?: D2UModuleManager::MODULE_FOLDER;
		$this->module_addon = rex_addon::get($addon_key);
		$this->module_folder = $this->module_addon->getPath($module_folder);
		// Path during addon update
		$temp_update_folder = $this->module_addon->getPath('../.new.' . $addon_key . '/' . $module_folder);
		if(file_exists($temp_update_folder)) {
			$this->module_folder = $temp_update_folder;
		}

		for($i = 0; $i < count($d2u_modules); $i++) {
			$d2u_module = $d2u_modules[$i];
			$d2u_module->initRedaxoContext($this->module_addon, $this->module_folder);
			$this->d2u_modules[$i] = $d2u_module;
		}
	}
	
	/**
	 * Update modules.
	 */
	public function autoupdate() {
		foreach($this->d2u_modules as $module) {
			// Only check autoupdate, not if needed. That would not work during addon update
			if($module->isAutoupdateActivated()) {
				$module->install();
			}
		}
		rex_delete_cache();
	}
	
	/**
	 * Performs actions offerd in manager list function.
	 * @param string $d2u_module_id D2U Module ID
	 * @param string $function Possible values: autoupdate, unlink
	 * @param int $paired_module_id Redaxo module ID
	 */
	public function doActions($d2u_module_id, $function, $paired_module_id) {
		// Form actions
		for($i = 0; $i < count($this->d2u_modules); $i++) {
			$module = $this->d2u_modules[$i];
			if($module->getD2UId() == $d2u_module_id) {
				if($function == "autoupdate") {
					if($module->isAutoupdateActivated()) {
						$module->disableAutoupdate();
						print rex_view::success($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_autoupdate_deactivated'));
					}
					else {
						$module->activateAutoupdate();
						print rex_view::success($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_autoupdate_activated'));
					}
				}
				else if($function == "unlink") {
					$module->unlink();
					$this->d2u_modules[$i] = $module;
					print rex_view::success($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_modules_pair_unlinked'));
				}
				else {
					$success = $module->install($paired_module_id);
					if($success && key_exists($module->getRedaxoId(), D2UModuleManager::getRexModules())) {
						rex_delete_cache();
						print rex_view::success($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_modules_installed'));
					}
					else {
						print rex_view::error($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_install_error'));
					}
				}
				break;
			}
		}
	}
	
	/**
	 * Get combined CSS styles for installed modules.
	 * @return string Combined CSS
	 */
	public function getAutoCSS() {
		$css = "";
		foreach($this->d2u_modules as $module) {
			if($module->isInstalled() && $module->getCSS() != "") {
				$css .= $module->getCSS() . PHP_EOL;
			}
		}
		return $css;
	}
	
	/**
	 * Get combined JavaScript for installed modules.
	 * @return string Combined JavaScript
	 */
	public function getAutoJS() {
		$js = "";
		foreach($this->d2u_modules as $module) {
			if($module->isInstalled() && $module->getJS() != "") {
				$js .= $module->getJS() . PHP_EOL;
			}
		}
		return $js;
	}
	
	/**
	 * Get modules offered by D2U Helper addon.
	 * @return D2UModule[] Modules offered by D2U Helper addon
	 */
	public static function getModules() {
		$modules = [];
		$modules[] = new D2UModule("00-1",
			"Umbruch ganze Breite",
			4);
		$modules[] = new D2UModule("01-1",
			"Texteditor",
			9);
		$modules[] = new D2UModule("01-2",
			"Texteditor mit Bild und Fettschrift",
			10);
		$modules[] = new D2UModule("02-1",
			"Überschrift",
			8);
		$modules[] = new D2UModule("02-2",
			"Überschrift mit Klapptext",
			3);
		$modules[] = new D2UModule("03-1",
			"Bild",
			7);
		$modules[] = new D2UModule("03-2",
			"Bildergalerie Ekko Lightbox",
			8);
		$modules[] = new D2UModule("04-1",
			"Google Maps",
			10);
		$modules[] = new D2UModule("05-1",
			"Artikelweiterleitung",
			10);
		$modules[] = new D2UModule("05-2",
			"Artikel aus anderer Sprache übernehmen",
			4);
		$modules[] = new D2UModule("06-1",
			"YouTube Video einbinden",
			4);
		$modules[] = new D2UModule("06-2",
			"IFrame einbinden",
			3);
		$modules[] = new D2UModule("10-1",
			"Box mit Bild und Ueberschrift",
			2);
		$modules[] = new D2UModule("10-2",
			"Box mit Bild und Text",
			4);
		$modules[] = new D2UModule("10-3",
			"Box mit Downloads",
			5);
		$modules[] = new D2UModule("11-1",
			"YForm Kontaktformular (DSGVO kompatibel)",
			6);
		$modules[] = new D2UModule("12-1",
			"Feeds Stream Galerie",
			3);
		$modules[] = new D2UModule("13-1",
			"Lauftext",
			2);
		$modules[] = new D2UModule("14-1",
			"Search It Suchmodul",
			1);
		// 20-x reserved for D2U Addresss
		// 21-x reserved for D2U History
		// 22-x reserved for D2U Staff
		// 23-x reserved for D2U Jobs
		// 24-x reserved for D2U Linkbox
		// 25-x reserved for D2U Partner
		// 26-x reserved for D2U Courses
		// 27-x reserved for D2U Heinzmann
		// 30-x reserved for D2U Videos
		// 40-x reserved for D2U News
		// 50-x reserved for D2U References
		// 60-x reserved for D2U Guestbook
		// 70-x reserved for D2U Immo Addon
		// 80-x reserved for MultiNewsletter Addon
		// 90-x reserved for D2U Machinery Addon
		return $modules;
	}
	
	/**
	 * Get paired module ids. 
	 * @return string[] Paired module ids. Key is Redaxo module id, value is 
	 * D2U module id.
	 */
	public static function getModulePairs() {
		$paired_modules = [];
		$query_paired = 'SELECT id, attributes, `key` FROM `'. \rex::getTablePrefix() .'module` WHERE `key` LIKE "d2u_%"';
		$result_paired = rex_sql::factory();
		$result_paired->setQuery($query_paired);
		for($i = 0; $i < $result_paired->getRows(); $i++) {
			$attributes = json_decode($result_paired->getValue("attributes"), true);
			$paired_modules[$result_paired->getValue('id')] = [
				'd2u_id' => str_replace('d2u_', '', $result_paired->getValue("key")),
				'addon_key' => $attributes['addon_key']
			];
			$result_paired->next();
		}

		return $paired_modules;
	}
	
	/**
	 * Gets Redaxo Modules.
	 * @param bool If TRUE, only unpaired modules are returned.
	 * @return string[] Redaxo modules. Key ist the module ID, value ist the module name
	 */
	public static function getRexModules($unpaired_only = FALSE) {
		$rex_modules = [];
		// Get Redaxo modules (must be after form actions, in case new module was installed)
		$query = 'SELECT id, name FROM ' . \rex::getTablePrefix() . 'module '
			.($unpaired_only ? "WHERE `key` NOT LIKE 'd2u_%' OR `key` IS NULL " : "")
			. 'ORDER BY name';
		$result = rex_sql::factory();
		$result->setQuery($query);
		for($i = 0; $i < $result->getRows(); $i++) {
			$rex_modules[$result->getValue("id")] = $result->getValue("name");
			$result->next();
		}

		return $rex_modules;
	}
	
	/**
	 * Prints list that offers module managing options
	 */
	public function showManagerList() {
		$url_params = [];
		// Compatibility for MultiNewsletter installation site
		if(filter_input(INPUT_GET, "chapter") != "") {
			$url_params["chapter"] = filter_input(INPUT_GET, "chapter");
		}
		print '<form action="'. rex_url::currentBackendPage($url_params) .'" method="post">';
		print '<section class="rex-page-section">';
		print '<div class="panel panel-default">';
		print '<header class="panel-heading"><div class="panel-title">'. rex_i18n::msg('d2u_helper_meta_modules') .'</div></header>';

		print '<table class="table table-striped table-hover">';

		print '<thead>';
		print '<tr>';
		print '<th class="rex-table-id">'. rex_i18n::msg('d2u_helper_d2u_id') .'</th>';
		print '<th>'. rex_i18n::msg('d2u_helper_modules_d2u_module_name') .'</th>';
		print '<th>'. rex_i18n::msg('version') .'</th>';
		print '<th>'. rex_i18n::msg('d2u_helper_modules_paired_rex_module') .'</th>';
		print '<th>'. rex_i18n::msg('d2u_helper_autoupdate') .'</th>';
		print '<th>'. rex_i18n::msg('module_functions') .'</th>';
		print '</tr>';
		print '</thead>';

		print '<tbody>';
		
		// Redaxo modules
		$rex_modules = D2UModuleManager::getRexModules();
		$unpaired_rex_modules = D2UModuleManager::getRexModules(TRUE);
		// Fix follows: directly after module installation, newly paired module is not detected as paired
		$installed_d2u_module_id = rex_request('d2u_module_id', 'string');
		if($installed_d2u_module_id != "") {
			foreach($unpaired_rex_modules as $rex_id => $name) {
				if(strpos($name, $installed_d2u_module_id) !== FALSE) {
					unset($unpaired_rex_modules[$rex_id]);
				}
			}
		}

		foreach($this->d2u_modules as $module) {
			print '<tr>';
			print '<td>'. $module->getD2UId() .'</td>';
			print '<td>'. $module->getName() .'</td>';
			print '<td>'. $module->getRevision() .'</td>';
			// Redaxo modules
			print '<td>';
			if($module->getRedaxoId() == 0) {
				$modules_select = new rex_select();
				$modules_select->addOption(rex_i18n::msg('d2u_helper_modules_pair_new'), 0);
				$modules_select->addArrayOptions($unpaired_rex_modules);
				$modules_select->setName("pair_". $module->getD2UId());
				$modules_select->setAttribute("class", "form-control");
				$modules_select->setSelected(0);
				print $modules_select->get();
			}
			else {
				print '<a href="'. rex_url::currentBackendPage(["function" => "unlink", "d2u_module_id" => $module->getD2UId()]) .'" title="'. rex_i18n::msg('d2u_helper_modules_pair_unlink') .'"><i class="rex-icon fa-chain-broken"></i> ';
				print $rex_modules[$module->getRedaxoId()];
				print '</a>';
			}
			print '</td>';
			// Autoupdate
			print '<td>';
			if($module->isInstalled()) {
				print '<a href="'. rex_url::currentBackendPage(["function" => "autoupdate", "d2u_module_id" => $module->getD2UId()]) .'">'
					. '<i class="rex-icon '. ($module->isAutoupdateActivated() ? 'rex-icon-package-is-activated' : 'rex-icon-package-not-activated') .'"></i> '
					. ($module->isAutoupdateActivated() ? rex_i18n::msg('package_deactivate') : rex_i18n::msg('package_activate')) .' </a>';
			}
			print '</td>';
			// Action
			print '<td class="rex-table-action"><button type="submit" name="d2u_module_id" class="btn btn-save" value="'. $module->getD2UId() .'">';
			if(!$module->isInstalled()) {
				print '<i class="rex-icon rex-icon-package-not-installed"></i> '. rex_i18n::msg('package_install');
			}
			else if($module->isUpdateNeeded()) {
				print '<i class="rex-icon rex-icon-package-is-installed"></i> '. rex_i18n::msg('update');
			}
			else {
				print '<i class="rex-icon rex-icon-package-is-activated"></i> '. rex_i18n::msg('package_reinstall');							
			}
			print '</button></td>';
			print '</tr>';
		}
		print '</tbody>';

		print '</table>';
		
		print '</div>';
		print '</section>';
		print '</form>';
	}
}

/**
 * Class managing modules published by www.design-to-use.de
 *
 * @author Tobias Krais
 */
class D2UModule {
	/**
	 * CSS file name for modules.
	 */
	const MODULE_CSS_FILE = 'style.css';

	/**
	 * JS file name for modules.
	 */
	const MODULE_JS_FILE = 'js.js';

	/**
	 * File name for module input.
	 */
	const MODULE_INPUT = 'input.php';

	/**
	 * File name for module install AND update script.
	 */
	const MODULE_INSTALL = 'install.php';

	/**
	 * File name for module output.
	 */
	const MODULE_OUTPUT = 'output.php';

	/**
	 * File name for module uninstall script.
	 */
	const MODULE_UNINSTALL = 'uninstall.php';

	/**
	 * @var int D2U Module ID
	 */
	private $d2u_module_id = "";
	
	/**
	 * @var bool TRUE if autoupdate ist activated
	 */
	private $autoupdate = FALSE;

	/**
	 * @var string Folder within addon, in which modules can be found. Trailing
	 * slash must be included.
	 */
	private $module_folder = "";

	/**
	 * @var string Modules title or name
	 */
	private $name = "";
	
	/**
	 * @var int Module version number
	 */
	private $revision = 0;

	/**
	 * @var int Redaxo Module ID
	 */
	private $rex_module_id = 0;
	
	/**
	 * @var rex_addon Redaxo Addon module belongs to
	 */
	private $rex_addon;

	/**
	 * Constructor. Sets values.
	 * @param string $d2u_module_id D2U Module ID, if known, else set 0
	 * @param string $name Modules title or name
	 * @param int $revision Module version number
	 */
	public function __construct($d2u_module_id, $name, $revision) {
		$this->d2u_module_id = $d2u_module_id;
		$d2u_module_id_explode = explode("-", $this->d2u_module_id);
		$this->module_folder = D2UModuleManager::MODULE_FOLDER . $d2u_module_id_explode[0]."/". $d2u_module_id_explode[1] ."/";
		$this->name = $name;
		$this->revision = $revision;
	}
	
	/**
	 * Activate autoupdate.
	 */
	public function activateAutoupdate() {
		$this->autoupdate = TRUE;
		$this->setAttributes();
	}

	/**
	 * Disable autoupdate.
	 */
	public function disableAutoupdate() {
		$this->autoupdate = FALSE;
		$this->setAttributes();
	}

	/**
	 * Get module stylesheets.
	 * @return string CSS
	 */
	public function getCSS() {
		if(D2UModule::MODULE_CSS_FILE != "" && file_exists($this->module_folder . D2UModule::MODULE_CSS_FILE)){
			return file_get_contents($this->module_folder . D2UModule::MODULE_CSS_FILE);
		}
		return "";
	}

	/**
	 * Get D2U Id.
	 * @return string D2U Module Id
	 */
	public function getD2UId() {
		return $this->d2u_module_id;
	}

	/**
	 * Get module Javacript.
	 * @return string JS
	 */
	public function getJS() {
		if(D2UModule::MODULE_CSS_FILE != "" && file_exists($this->module_folder . D2UModule::MODULE_CSS_FILE)){
			return file_get_contents($this->module_folder . D2UModule::MODULE_CSS_FILE);
		}
		return "";
	}

	/**
	 * Get name.
	 * @return string Module name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Get revision.
	 * @return int Module revision
	 */
	public function getRevision() {
		return $this->revision;
	}
	
	/**
	 * Get Redaxo module Id.
	 * @return string Redaxo Module Id
	 */
	public function getRedaxoId() {
		return $this->rex_module_id;
	}

	/**
	 * Initializes object in Redaxo context: sets Redaxo module id and folders
	 * @param rex_addon Redaxo Addon module belongs to
	 * @param string Complete folder string, in which module files can be found.
	 * Trailing slash must be included.
	 */
	public function initRedaxoContext(rex_addon $module_addon, string $module_folder) {
		$this->rex_addon = $module_addon;
		$sql = rex_sql::factory();
		$sql->setTable(rex::getTablePrefix(). 'module');
		$sql->setWhere('`key` = "d2u_'. $this->d2u_module_id .'"');
		$sql->select();
		foreach ($sql->getArray() as $result) {
			// Get redaxo module id
			$this->rex_module_id = $result['id'];
			// Get Autoupdate settings
			$attributes = json_decode($result['attributes'], true);
			$this->autoupdate = key_exists('autoupdate', $attributes) && $attributes['autoupdate'] == "active" ? true : false;
		}
		
		// Set folders correctly
		$d2u_module_id = explode("-", $this->d2u_module_id);
		$this->module_folder = $module_folder . $d2u_module_id[0] ."/". $d2u_module_id[1] ."/";
	}

	/**
	 * Installes or updates the module in redaxo module table.
	 * @param int Redaxo module id, if not passed, already available ID is taken.
	 * @return bool TRUE if installed, otherwise FALSE
	 */
	public function install($rex_module_id = 0) {
		if(file_exists($this->module_folder . D2UModule::MODULE_INSTALL)) {
			$success = include $this->module_folder . D2UModule::MODULE_INSTALL;
			if(!$success) {
				return FALSE;
			}
		}
		
		if($this->rex_module_id == 0 && $rex_module_id > 0) {
			$this->rex_module_id = $rex_module_id;
		}
		
		$insertmod = rex_sql::factory();
		$insertmod->setTable(\rex::getTablePrefix() . 'module');
        $insertmod->setValue('key', "d2u_". $this->d2u_module_id);
        $insertmod->setValue('name', $this->d2u_module_id ." ". $this->name);
		$insertmod->setValue('input', file_get_contents($this->module_folder . D2UModule::MODULE_INPUT));
		$insertmod->setValue('output', file_get_contents($this->module_folder . D2UModule::MODULE_OUTPUT));
		$insertmod->setValue('revision', $this->revision);
		if($this->rex_module_id == 0) {
			$insertmod->addGlobalCreateFields();
			$insertmod->insert();
			$this->rex_module_id = $insertmod->getLastId();
		}
		else {
			$insertmod->addGlobalUpdateFields();
			$insertmod->setWhere(['id' => $this->rex_module_id]);
			$insertmod->update();
		}
		// save module attributes
		$this->setAttributes();
		
		// Delete addon cache for new styles could have been added
		d2u_addon_frontend_helper::deleteCache();
		
		return TRUE;
	}
	
	/**
	 * Checks if module should be automatically updated if new revision is available
	 */
	public function isAutoupdateActivated() {
		return $this->autoupdate;
	}

	/**
	 * Checks if module is installed in Redaxo
	 */
	public function isInstalled() {
		return ($this->rex_module_id > 0);
	}

	/**
	 * Static method equivalent to isInstalled()
	 * @param string $d2u_module_id D2U Module ID, e.g. "03-2"
	 * @return bool TRUE if D2U module is installed, otherwise FALSE
	 */
	public static function isModuleIDInstalled($d2u_module_id) {
		$query = 'SELECT * FROM ' . \rex::getTablePrefix() . 'module WHERE `key` = "d2u_'. $d2u_module_id .'"';
		$result = rex_sql::factory();
		$result->setQuery($query);
		return $result->getRows() > 0 ? TRUE : FALSE;
	}

	/**
	 * Checks id module in Redaxo DB needs update or needs to be installed.
	 */
	public function isUpdateNeeded() {
		if($this->isInstalled()) {
			// Get redaxo module
			$query = 'SELECT revision FROM ' . \rex::getTablePrefix() . 'module WHERE id = '. $this->rex_module_id;
			$result = rex_sql::factory();
			$result->setQuery($query);
			if($result->getRows() > 0 && $result->getValue("revision") >= $this->revision) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * Removes the module from redaxo module table
	 */
	public function delete() {
		$removemod = rex_sql::factory();
		$removemod->setTable(\rex::getTablePrefix() . 'module');
		$removemod->setWhere(['id' => $this->d2u_module_id]);
		$removemod->delete();

		// uninstall action
		if(file_exists($this->module_folder . D2UModule::MODULE_UNINSTALL)) {
			include $this->module_folder . D2UModule::MODULE_UNINSTALL;
		}
	}
	
	/**
	 * Save module config.
	 */
	private function setAttributes() {
		// Autoupdate
		$params = [
			"autoupdate" => ($this->isAutoupdateActivated() ? "active" : "inactive"),
			"addon_key" => $this->rex_addon->getName()
			];

		$sql = rex_sql::factory();
		$sql->setQuery("UPDATE ". rex::getTablePrefix() ."module "
			."SET `attributes` = '". json_encode($params) ."' "
			."WHERE `key` = 'd2u_". $this->d2u_module_id ."'");
	}
	
	/**
	 * Remove module pair config.
	 */
	public function unlink() {
		$sql = rex_sql::factory();
		$sql->setQuery("UPDATE ". rex::getTablePrefix() ."module "
			."SET `key` = NULL, attributes = NULL "
			."WHERE `key` = 'd2u_". $this->d2u_module_id ."'");

		$this->rex_module_id = 0;
	}
}