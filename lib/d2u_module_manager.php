<?php
/**
 * Class managing modules published by www.design-to-use.de
 *
 * @author Tobias Krais
 */
class D2UModuleManager {
	/**
	 * @var D2UModule[] Array with D2U modules
	 */
	var $d2u_modules = [];
	
	/**
	 * @var string Folder within addon, in which modules can be found. Trailing
	 * slash must be included.
	 */
	var $module_folder = "modules/";

	/**
	 * @var rex_addon Redaxo Addon module belongs to
	 */
	private $module_addon;
	
	/**
	 * @var string[] Array with redaxo modules. Key is module id, value is the name.
	 */
	private static $rex_modules = [];
	
	/**
	 * Constructor. Sets values. The path that is constructed is during addon
	 * update the path of the new addon folder. Otherwise the normal addon path.
	 * @param D2UModule[] $d2u_modules Array with D2U modules
	 * @param string $module_folder Folder, in which modules can be found.
	 * Trailing slash must be included. Default "modules/".
	 * @param string $addon_key Redaxo Addon name module belongs to, default "d2u_helper"
	 */
	public function __construct($d2u_modules, $module_folder = "modules/", $addon_key = "d2u_helper") {
		$this->d2u_modules = $d2u_modules;
		$this->module_addon = rex_addon::get($addon_key);
		$this->module_folder = $this->module_addon->getPath($module_folder);
		// Path during addon update
		if(file_exists(str_replace($addon_key, ".new.". $addon_key, $this->module_folder))) {
			$this->module_folder = str_replace($addon_key, ".new.". $addon_key, $this->module_folder);
		}

		foreach($this->d2u_modules as $key => $d2u_module) {
			$d2u_module->initRedaxoContext($this->module_addon, $this->module_folder);
			$this->d2u_modules[$key] = $d2u_module;
		}
	}
	
	/**
	 * Perform pending module updates.
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
	 * @param string $function Possible values: autoupdate
	 * @param int $paired_module_id Redaxo module ID
	 */
	public function doActions($d2u_module_id, $function, $paired_module_id) {
		// Form actions
		foreach($this->d2u_modules as $module) {
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
				else {
					$success = $module->install($paired_module_id);
					if($success && key_exists($module->getRedaxoId(), D2UModuleManager::getRexModules(TRUE))) {
						print rex_view::success($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_modules_installed'));
					}
					else {
						print rex_view::error($module->getD2UId() ." ". $module->getName() .": ". rex_i18n::msg('d2u_helper_install_error'));
					}
				}
				break;
			}
		}		
		rex_delete_cache();
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
			3);
		$modules[] = new D2UModule("01-1",
			"Texteditor",
			5);
		$modules[] = new D2UModule("01-2",
			"Texteditor mit Bild und Überschrift",
			5);
		$modules[] = new D2UModule("02-1",
			"Ueberschrift",
			3);
		$modules[] = new D2UModule("03-1",
			"Bild",
			4);
		$modules[] = new D2UModule("03-2",
			"Bildergallerie Ekko Lightbox",
			4);
		$modules[] = new D2UModule("04-1",
			"Google Maps",
			6);
		$modules[] = new D2UModule("05-1",
			"Artikelweiterleitung",
			4);
		$modules[] = new D2UModule("05-2",
			"Artikel aus anderer Sprache übernehmen",
			2);
		$modules[] = new D2UModule("06-1",
			"YouTube Video einbinden",
			2);
		$modules[] = new D2UModule("06-2",
			"IFrame einbinden",
			1);
		$modules[] = new D2UModule("10-1",
			"Box mit Bild und Ueberschrift",
			1);
		$modules[] = new D2UModule("10-2",
			"Box mit Bild und Text",
			1);
		$modules[] = new D2UModule("10-3",
			"Box mit Download",
			1);
		// 20-x reserved for D2U Addresss
		// 21-x reserved for D2U History
		// 22-x reserved for D2U Staff
		// 23-x reserved for D2U Jobs
		// 24-x reserved for D2U Linkbox
		// 25-x reserved for D2U Partner
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
	 * Gets Redaxo Modules.
	 * @param boolean If TRUE, reload of modules is performed.
	 * @return string[] Redaxo modules. Key ist the module ID, value ist the module name
	 */
	public static function getRexModules($reload = FALSE) {
		if($reload || count(D2UModuleManager::$rex_modules) == 0) {
			D2UModuleManager::$rex_modules = [];
			// Get Redaxo modules (must be after form actions, in case new module was installed)
			$query = 'SELECT id, name FROM ' . \rex::getTablePrefix() . 'module ORDER BY name';
			$result = rex_sql::factory();
			$result->setQuery($query);
			for($i = 0; $i < $result->getRows(); $i++) {
				D2UModuleManager::$rex_modules[$result->getValue("id")] = $result->getValue("name");
				$result->next();
			}
		}
		return D2UModuleManager::$rex_modules;
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
		// Available Redaxo modules
		$rex_modules = D2UModuleManager::getRexModules(TRUE);
		// Create arrays with unpaired redaxo modules
		$unpaired_redaxo_modules = [0 => rex_i18n::msg('d2u_helper_modules_pair_new')] + $rex_modules;
		foreach($this->d2u_modules as $module) {
			if($module->getRedaxoId() > 0) {
				unset($unpaired_redaxo_modules[$module->getRedaxoId()]);
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
				$modules_select->addArrayOptions($unpaired_redaxo_modules);
				$modules_select->setName("pair_". $module->getD2UId());
				$modules_select->setAttribute("class", "form-control");
				$modules_select->setSelected($module->getRedaxoId());
				print $modules_select->get();
			}
			else {
				print $rex_modules[$module->getRedaxoId()];
			}
			print '</td>';
			// Autoupdate
			print '<td>';
			if($module->isInstalled()) {
				$message = $module->isAutoupdateActivated() ? rex_i18n::msg('package_deactivate') : rex_i18n::msg('package_activate');
				$icon = $module->isAutoupdateActivated() ? 'rex-icon-package-is-activated' : 'rex-icon-package-not-activated';
				print '<a href="'. rex_url::currentBackendPage(["function" => "autoupdate", "d2u_module_id" => $module->getD2UId()]) .'"><i class="rex-icon '. $icon .'"></i> '. $message .' </a>';
			}
			print '</td>';
			// Action
			print '<td class="rex-table-action"><button type="submit" name="d2u_module_id" class="btn btn-save" value="'. $module->getD2UId() .'">';
			if(!$module->isInstalled()) {
				print '<i class="rex-icon rex-icon-package-not-installed"></i> '.rex_i18n::msg('package_install');
			}
			else if($module->isUpdateNeeded()) {
				print '<i class="rex-icon rex-icon-package-is-installed"></i> '.rex_i18n::msg('update');
			}
			else {
				print '<i class="rex-icon rex-icon-package-is-activated"></i> '.rex_i18n::msg('package_reinstall');							
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
	 * @var int D2U Module ID
	 */
	private $d2u_module_id = "";
	
	/**
	 * @var boolean TRUE if autoupdate ist activated
	 */
	private $autoupdate = FALSE;

	/**
	 * @var string CSS file for the module
	 */
	private $filename_css = "style.css";

	/**
	 * @var string Filename with module input
	 */
	private $filename_input = "input.php";

	/**
	 * @var string JS file for the module
	 */
	private $filename_js = "js.js";

	/**
	 * @var string Filename with module output
	 */
	private $filename_output = "output.php";
	
	/**
	 * @var string Folder within addon, in which modules can be found. Trailing
	 * slash must be included.
	 */
	private $module_folder = "modules/";

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
	 * @param string $css Modules CSS filename
	 * @param string $js Modules JS filename
	 */
	public function __construct($d2u_module_id, $name, $revision) {
		$this->d2u_module_id = $d2u_module_id;
		$d2u_module_id_explode = explode("-", $this->d2u_module_id);
		$this->module_folder .= $d2u_module_id_explode[0]."/". $d2u_module_id_explode[1] ."/";
		$this->name = $name;
		$this->revision = $revision;
	}
	
	/**
	 * Activate autoupdate.
	 */
	public function activateAutoupdate() {
		$this->autoupdate = TRUE;
		$this->setConfig();
	}

	/**
	 * Disable autoupdate.
	 */
	public function disableAutoupdate() {
		$this->autoupdate = FALSE;
		$this->setConfig();
	}

	/**
	 * Get module stylesheets.
	 * @return string CSS
	 */
	public function getCSS() {
		if($this->filename_css != "" && file_exists($this->module_folder . $this->filename_css)){
			return file_get_contents($this->module_folder . $this->filename_css);
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
		if($this->filename_js != "" && file_exists($this->module_folder . $this->filename_js)){
			return file_get_contents($this->module_folder . $this->filename_js);
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
	public function initRedaxoContext($module_addon, $module_folder) {
		$this->rex_addon = $module_addon;
		if($this->rex_addon->hasConfig("module_". $this->d2u_module_id)) {
			$config = $this->rex_addon->getConfig("module_". $this->d2u_module_id);
			if(key_exists($config["rex_module_id"], D2UModuleManager::getRexModules())) {
				// Get paired module id
				$this->rex_module_id = $config["rex_module_id"];
				// Get Autoupdate settings
				$this->autoupdate = $config["autoupdate"] == "active" ? TRUE : FALSE;
			}
			else {
				// If module no more exists, delete pairing
				$this->rex_addon->removeConfig("module_". $this->d2u_module_id);
			}
		}

		// Set folders correctly
		$d2u_module_id = explode("-", $this->d2u_module_id);
		$this->module_folder = $module_folder . $d2u_module_id[0] ."/". $d2u_module_id[1] ."/";
		$this->filename_input = $this->module_folder . $this->filename_input;
		$this->filename_output = $this->module_folder . $this->filename_output;
	}

	/**
	 * Installes or updates the module in redaxo module table.
	 * @param int Redaxo module id, if not passed, already available ID is taken.
	 * @return boolean TRUE if installed, otherwise FALSE
	 */
	public function install($rex_module_id = 0) {
		if(file_exists($this->module_folder ."install.php")) {
			$success = include $this->module_folder ."install.php";
			if(!$success) {
				return FALSE;
			}
		}
		
		if($this->rex_module_id == 0 && $rex_module_id > 0) {
			$this->rex_module_id = $rex_module_id;
		}
		
		$insertmod = rex_sql::factory();
		$insertmod->setTable(\rex::getTablePrefix() . 'module');
        $insertmod->setValue('name', $this->d2u_module_id ." ". $this->name);
		$insertmod->setValue('input', file_get_contents($this->filename_input));
		$insertmod->setValue('output', file_get_contents($this->filename_output));
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
		
		// save pairing in config
		$this->setConfig();
		
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
		if($this->rex_module_id > 0) {
			return TRUE;
		}
		else {
			return FALSE;
		}
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

		// remove addon config
		if($this->rex_addon->hasConfig("module_". $this->d2u_module_id)) {
			$this->rex_addon->removeConfig("module_". $this->d2u_module_id);
		}

		// uninstall action
		if(file_exists($this->module_folder ."uninstall.php")) {
			include $this->module_folder ."uninstall.php";
		}
	}
	
	/**
	 * Save module config.
	 */
	private function setConfig() {
		// Module pairing
		$params = ["rex_module_id" => $this->rex_module_id];
		// Autoupdate
		if($this->isAutoupdateActivated()) {
			$params["autoupdate"] = "active";
		}
		else {
			$params["autoupdate"] = "inactive";
		}
		$this->rex_addon->setConfig("module_". $this->d2u_module_id, $params);

	}
}