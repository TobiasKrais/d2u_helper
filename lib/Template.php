<?php

namespace TobiasKrais\D2UHelper;

use rex_addon;
use rex_addon_interface;
use rex_config;
use rex_path;
use rex_sql;
use rex_version;

/**
 * @api
 * Class managing templates published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class Template
{
    /**
     * CSS file name for templates.
     */
    private const TEMPLATE_CSS_FILE = 'styles.css';

    /**
     * CSS file name for templates.
     */
    private const TEMPLATE_FILE = 'template.php';

    /**
     * CSS file name for templates.
     */
    private const TEMPLATE_INSTALL = 'install.php';

    /**
     * CSS file name for templates.
     */
    private const TEMPLATE_UNINSTALL = 'uninstall.php';

    /** @var string D2U Template ID */
    private string $d2u_template_id = '0';

    /** @var bool true if autoupdate ist activated */
    private bool $autoupdate = false;

    /** @var string Templates title or name */
    private string $name = '';

    /** @var int Template version number */
    private int $revision = 0;

    /** @var int Redaxo Template ID */
    private int $rex_template_id = 0;

    /** @var rex_addon_interface Redaxo Addon template belongs to */
    private rex_addon_interface $rex_addon;

    /** @var string template_folder Redaxo Addon template folder7 */
    private string $template_folder;

    /**
     * Constructor. Sets values.
     * @param string $d2u_template_id D2U Template ID, if known, else set 0
     * @param string $name Template title or name
     * @param int $revision Template version number
     */
    public function __construct($d2u_template_id, $name, $revision)
    {
        $this->d2u_template_id = $d2u_template_id;
        $this->name = $name;
        $this->revision = $revision;
    }

    /**
     * Activate autoupdate.
     */
    public function activateAutoupdate(): void
    {
        $this->autoupdate = true;
        $this->setConfig();
    }

    /**
     * Disable autoupdate.
     */
    public function disableAutoupdate(): void
    {
        $this->autoupdate = false;
        $this->setConfig();
    }

    /**
     * Get CSS for template.
     * @return string Template name
     */
    public function getCSS()
    {
        $template_css = '';
        // Template CSS
        if (file_exists($this->template_folder . self::TEMPLATE_CSS_FILE)) {
            $css = file_get_contents($this->template_folder . self::TEMPLATE_CSS_FILE);
            $template_css = false !== $css ? $css : '';
        }

        // Footer CSS
        $footer_type = rex_config::get('d2u_helper', 'footer_type', '');
        if ('' !== $footer_type && file_exists(rex_path::addonAssets('d2u_helper', 'template/footer/d2u_template_footer_'. $footer_type .'.css'))) {
            $css = file_get_contents(rex_path::addonAssets('d2u_helper', 'template/footer/d2u_template_footer_'. $footer_type .'.css'));
            $template_css .= false !== $css ? $css : '';
        }
        // Language modal CSS
        if (file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_language_modal.css'))) {
            $css = file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_language_modal.css'));
            $template_css .= false !== $css ? $css : '';
        }
        // Search icon CSS
        if (file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_search_icon.css'))) {
            $css = file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_search_icon.css'));
            $template_css .= false !== $css ? $css : '';
        }
        // Header slider CSS
        if ('04' === substr($this->d2u_template_id, 0, 2) && file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_header_slider.css'))) {
            $css = file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_header_slider.css'));
            $template_css .= false !== $css ? $css : '';
        }
        // Consent manager CSS
        if (rex_addon::get('consent_manager')->isAvailable() && rex_version::compare('4.0.0', rex_addon::get('consent_manager')->getVersion(), '>=') && file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_consent_manager_v3.css'))) {
            $css = file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_consent_manager_v3.css'));
            $template_css .= false !== $css ? $css : '';
        }
        // CTA box CSS
        if ((bool) rex_config::get('d2u_helper', 'show_cta_box', false) && file_exists(rex_path::addonAssets('d2u_helper', 'template/d2u_template_cta_box.css'))) {
            $css = file_get_contents(rex_path::addonAssets('d2u_helper', 'template/d2u_template_cta_box.css'));
            $template_css .= false !== $css ? $css : '';
        }

        return $template_css;
    }

    /**
     * Get D2U Id.
     * @return string D2U Template Id
     */
    public function getD2UId()
    {
        return $this->d2u_template_id;
    }

    /**
     * Get name.
     * @return string Template name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get revision.
     * @return int Template revision
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Get Redaxo template Id.
     * @return int Redaxo Template Id
     */
    public function getRedaxoId()
    {
        return $this->rex_template_id;
    }

    /**
     * Initializes object in Redaxo context: sets Redaxo template id and folders.
     * @param rex_addon_interface $template_addon Redaxo Addon template belongs to
     * @param string $template_folder Complete folder string, in which template files can be found.
     * Trailing slash must be included.
     */
    public function initRedaxoContext($template_addon, $template_folder): void
    {
        $this->rex_addon = $template_addon;
        if ($this->rex_addon->hasConfig('template_'. $this->d2u_template_id)) {
            $config = $this->rex_addon->getConfig('template_'. $this->d2u_template_id);
            if (is_array($config) && array_key_exists((int) $config['rex_template_id'], TemplateManager::getRexTemplates())) {
                // Get paired template id
                $this->rex_template_id = (int) $config['rex_template_id'];
                // Get Autoupdate settings
                $this->autoupdate = 'active' === (string) $config['autoupdate'] ? true : false;
            } else {
                // If template no more exists, delete pairing
                $this->rex_addon->removeConfig('template_'. $this->d2u_template_id);
            }
        }

        // Set folders correctly
        $this->template_folder = $template_folder . $this->d2u_template_id .'/';
    }

    /**
     * Installes or updates the template in redaxo template table.
     * @param int $rex_template_id redaxo template id, if not passed, already available ID is taken
     * @return bool true if installed, otherwise false
     */
    public function install($rex_template_id = 0)
    {
        if (file_exists($this->template_folder . self::TEMPLATE_INSTALL)) {
            $success = include $this->template_folder . self::TEMPLATE_INSTALL;
            if (!$success) {
                return false;
            }
        }

        if (0 === $this->rex_template_id && $rex_template_id > 0) {
            $this->rex_template_id = $rex_template_id;
        }

        $insert = rex_sql::factory();
        $insert->setTable(\rex::getTablePrefix() . 'template');
        $insert->setValue('key', 'd2u_'. $this->d2u_template_id);
        $insert->setValue('name', $this->d2u_template_id .' '. $this->name);
        $insert->setValue('content', file_get_contents($this->template_folder . self::TEMPLATE_FILE));
        $insert->setValue('active', 1);
        $insert->setValue('revision', $this->revision);
        if (0 === $this->rex_template_id) {
            $insert->addGlobalCreateFields();
            $insert->setValue('attributes', '{"modules":{"1":{"all":"1"}},"ctype":[],"categories":{"all":"1"}}');
            $insert->insert();
            $this->rex_template_id = (int) $insert->getLastId();
        } else {
            $insert->addGlobalUpdateFields();
            $insert->setWhere(['id' => $this->rex_template_id]);
            $insert->update();
        }

        // save pairing in config
        $this->setConfig();

        return true;
    }

    /**
     * Checks if template should be automatically updated if new revision is available.
     * @return bool true if autoupdate is activated
     */
    public function isAutoupdateActivated()
    {
        return $this->autoupdate;
    }

    /**
     * Checks if template is installed in Redaxo.
     * @return bool true if template is installed
     */
    public function isInstalled()
    {
        if ($this->rex_template_id > 0) {
            return true;
        }

        return false;

    }

    /**
     * Checks id template in Redaxo DB needs update or needs to be installed.
     * @return bool true if template needs to be updated
     */
    public function isUpdateNeeded()
    {
        if ($this->isInstalled()) {
            // Get redaxo template
            $query = 'SELECT revision FROM ' . \rex::getTablePrefix() . 'template WHERE id = '. $this->rex_template_id;
            $result = rex_sql::factory();
            $result->setQuery($query);
            if ($result->getRows() > 0 && $result->getValue('revision') >= $this->revision) {
                return false;
            }
        }
        return true;
    }

    /**
     * Removes the template from redaxo template table.
     */
    public function delete(): void
    {
        $removetemp = rex_sql::factory();
        $removetemp->setTable(\rex::getTablePrefix() . 'template');
        $removetemp->setWhere(['id' => $this->d2u_template_id]);
        $removetemp->delete();

        // remove addon config
        if ($this->rex_addon->hasConfig('template_'. $this->d2u_template_id)) {
            $this->rex_addon->removeConfig('template_'. $this->d2u_template_id);
        }

        // template specific uninstall action
        if (file_exists($this->template_folder . self::TEMPLATE_UNINSTALL)) {
            include $this->template_folder . self::TEMPLATE_UNINSTALL;
        }
    }

    /**
     * Save template config.
     */
    private function setConfig(): void
    {
        // Template pairing
        $params = ['rex_template_id' => $this->rex_template_id];
        // Autoupdate
        if ($this->isAutoupdateActivated()) {
            $params['autoupdate'] = 'active';
        } else {
            $params['autoupdate'] = 'inactive';
        }
        rex_addon::get('d2u_helper')->setConfig('template_'. $this->d2u_template_id, $params);

    }

    /**
     * Remove template pair config.
     */
    public function unlink(): void
    {
        // unlink
        $this->rex_addon->removeConfig('template_'. $this->d2u_template_id);
        $this->rex_template_id = 0;

        // remove template key after unlink
        $update = rex_sql::factory();
        $update->setTable(\rex::getTablePrefix() . 'template');
        $update->setValue('key', '');
        $update->addGlobalUpdateFields();
        $update->setWhere(['key' => 'd2u_'. $this->d2u_template_id]);
        $update->update();
    }
}
