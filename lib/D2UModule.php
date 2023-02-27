<?php

/**
 * @api
 * Class managing modules published by www.design-to-use.de
 *
 * @author Tobias Krais
 */
class D2UModule
{
    /**
     * CSS file name for modules.
     */
    public const MODULE_CSS_FILE = 'style.css';

    /**
     * JS file name for modules.
     */
    public const MODULE_JS_FILE = 'js.js';

    /**
     * File name for module input.
     */
    public const MODULE_INPUT = 'input.php';

    /**
     * File name for module install AND update script.
     */
    public const MODULE_INSTALL = 'install.php';

    /**
     * File name for module output.
     */
    public const MODULE_OUTPUT = 'output.php';

    /**
     * File name for module uninstall script.
     */
    public const MODULE_UNINSTALL = 'uninstall.php';

    /** @var string D2U Module ID */
    private string $d2u_module_id = '';

    /** @var bool true if autoupdate ist activated */
    private bool $autoupdate = false;

    /**
     * @var string Folder within addon, in which modules can be found. Trailing
     * slash must be included.
     */
    private string $module_folder = '';

    /** @var string Modules title or name */
    private string $name = '';

    /** @var int Module version number */
    private int $revision = 0;

    /** @var int Redaxo Module ID */
    private int $rex_module_id = 0;

    /** @var rex_addon_interface Redaxo Addon module belongs to */
    private rex_addon_interface $rex_addon;

    /**
     * Constructor. Sets values.
     * @param string $d2u_module_id D2U Module ID, if known, else set 0
     * @param string $name Modules title or name
     * @param int $revision Module version number
     */
    public function __construct($d2u_module_id, $name, $revision)
    {
        $this->d2u_module_id = $d2u_module_id;
        $d2u_module_id_explode = explode('-', $this->d2u_module_id);
        $this->module_folder = D2UModuleManager::MODULE_FOLDER . $d2u_module_id_explode[0].'/'. $d2u_module_id_explode[1] .'/';
        $this->name = $name;
        $this->revision = $revision;
    }

    /**
     * Activate autoupdate.
     */
    public function activateAutoupdate(): void
    {
        $this->autoupdate = true;
        $this->setAttributes();
    }

    /**
     * Disable autoupdate.
     */
    public function disableAutoupdate(): void
    {
        $this->autoupdate = false;
        $this->setAttributes();
    }

    /**
     * Get module stylesheets.
     * @return string CSS
     */
    public function getCSS()
    {
        if (file_exists($this->module_folder . self::MODULE_CSS_FILE)) {
            $content = file_get_contents($this->module_folder . self::MODULE_CSS_FILE);
            return false !== $content ? $content : '';
        }
        return '';
    }

    /**
     * Get D2U Id.
     * @return string D2U Module Id
     */
    public function getD2UId()
    {
        return $this->d2u_module_id;
    }

    /**
     * Get module Javacript.
     * @return string JS
     */
    public function getJS()
    {
        if (file_exists($this->module_folder . self::MODULE_JS_FILE)) {
            $content = file_get_contents($this->module_folder . self::MODULE_JS_FILE);
            return false !== $content ? $content : '';
        }
        return '';
    }

    /**
     * Get name.
     * @return string Module name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get revision.
     * @return int Module revision
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Get Redaxo module Id.
     * @return int Redaxo Module Id
     */
    public function getRedaxoId()
    {
        return $this->rex_module_id;
    }

    /**
     * Initializes object in Redaxo context: sets Redaxo module id and folders.
     * @param rex_addon_interface $module_addon Redaxo Addon module belongs to
     * @param string $module_folder Complete folder string, in which module files can be found.Trailing slash must be included.
     */
    public function initRedaxoContext($module_addon, string $module_folder): void
    {
        $this->rex_addon = $module_addon;
        $sql = rex_sql::factory();
        $sql->setTable(rex::getTablePrefix(). 'module');
        $sql->setWhere('`key` = "d2u_'. $this->d2u_module_id .'"');
        $sql->select();
        foreach ($sql->getArray() as $result) {
            // Get redaxo module id
            $this->rex_module_id = (int) $result['id'];
            // Get Autoupdate settings
            $attributes = json_decode((string) $result['attributes'], true);
            $this->autoupdate = is_array($attributes) && array_key_exists('autoupdate', $attributes) && 'active' === $attributes['autoupdate'] ? true : false;
        }

        // Set folders correctly
        $d2u_module_id = explode('-', $this->d2u_module_id);
        $this->module_folder = $module_folder . $d2u_module_id[0] .'/'. $d2u_module_id[1] .'/';
    }

    /**
     * Installes or updates the module in redaxo module table.
     * @param int $rex_module_id redaxo module id, if not passed, already available ID is taken
     * @return bool true if installed, otherwise false
     */
    public function install($rex_module_id = 0)
    {
        if (file_exists($this->module_folder . self::MODULE_INSTALL)) {
            $success = include $this->module_folder . self::MODULE_INSTALL;
            if (!$success) {
                return false;
            }
        }

        if (0 === $this->rex_module_id && $rex_module_id > 0) {
            $this->rex_module_id = $rex_module_id;
        }

        $insertmod = rex_sql::factory();
        $insertmod->setTable(\rex::getTablePrefix() . 'module');
        $insertmod->setValue('key', 'd2u_'. $this->d2u_module_id);
        $insertmod->setValue('name', $this->d2u_module_id .' '. $this->name);
        $insertmod->setValue('input', file_get_contents($this->module_folder . self::MODULE_INPUT));
        $insertmod->setValue('output', file_get_contents($this->module_folder . self::MODULE_OUTPUT));
        $insertmod->setValue('revision', $this->revision);
        if (0 === $this->rex_module_id) {
            $insertmod->addGlobalCreateFields();
            $insertmod->insert();
            $this->rex_module_id = (int) $insertmod->getLastId();
        } else {
            $insertmod->addGlobalUpdateFields();
            $insertmod->setWhere(['id' => $this->rex_module_id]);
            $insertmod->update();
        }
        // save module attributes
        $this->setAttributes();

        // Delete addon cache for new styles could have been added
        d2u_addon_frontend_helper::deleteCache();

        return true;
    }

    /**
     * Checks if module should be automatically updated if new revision is available.
     * @return bool true if autoupdate is activated
     */
    public function isAutoupdateActivated()
    {
        return $this->autoupdate;
    }

    /**
     * Checks if module is installed in Redaxo.
     * @return bool true if installed
     */
    public function isInstalled()
    {
        return $this->rex_module_id > 0;
    }

    /**
     * Static method equivalent to isInstalled().
     * @param string $d2u_module_id D2U Module ID, e.g. "03-2"
     * @return bool true if D2U module is installed, otherwise false
     */
    public static function isModuleIDInstalled($d2u_module_id)
    {
        $query = 'SELECT * FROM ' . \rex::getTablePrefix() . 'module WHERE `key` = "d2u_'. $d2u_module_id .'"';
        $result = rex_sql::factory();
        $result->setQuery($query);
        return $result->getRows() > 0 ? true : false;
    }

    /**
     * Checks id module in Redaxo DB needs update or needs to be installed.
     * @return bool true if update is needed
     */
    public function isUpdateNeeded()
    {
        if ($this->isInstalled()) {
            // Get redaxo module
            $query = 'SELECT revision FROM ' . \rex::getTablePrefix() . 'module WHERE id = '. $this->rex_module_id;
            $result = rex_sql::factory();
            $result->setQuery($query);
            if ($result->getRows() > 0 && $result->getValue('revision') >= $this->revision) {
                return false;
            }
        }
        return true;
    }

    /**
     * Removes the module from redaxo module table.
     */
    public function delete(): void
    {
        $removemod = rex_sql::factory();
        $removemod->setTable(\rex::getTablePrefix() . 'module');
        $removemod->setWhere(['id' => $this->d2u_module_id]);
        $removemod->delete();

        // uninstall action
        if (file_exists($this->module_folder . self::MODULE_UNINSTALL)) {
            include $this->module_folder . self::MODULE_UNINSTALL;
        }
    }

    /**
     * Save module config.
     */
    private function setAttributes(): void
    {
        // Autoupdate
        $params = [
            'autoupdate' => ($this->isAutoupdateActivated() ? 'active' : 'inactive'),
            'addon_key' => $this->rex_addon->getName(),
        ];

        $sql = rex_sql::factory();
        $sql->setQuery('UPDATE '. rex::getTablePrefix() .'module '
            ."SET `attributes` = '". json_encode($params) ."' "
            ."WHERE `key` = 'd2u_". $this->d2u_module_id ."'");
    }

    /**
     * Remove module pair config.
     */
    public function unlink(): void
    {
        $sql = rex_sql::factory();
        $sql->setQuery('UPDATE '. rex::getTablePrefix() .'module '
            .'SET `key` = NULL, attributes = NULL '
            ."WHERE `key` = 'd2u_". $this->d2u_module_id ."'");

        $this->rex_module_id = 0;
    }
}
