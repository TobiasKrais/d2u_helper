<?php
/**
 * Class managing templates published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class D2UTemplateManager
{
    /**
     * Folder where templates can be found.
     */
    public const TEMPLATE_FOLDER = 'templates/';

    /** @var D2UTemplate[] Array with D2U templates */
    public $d2u_templates = [];

    /**
     * @var string Folder within addon, in which templates can be found. Trailing
     * slash must be included.
     */
    public $template_folder = '';

    /** @var rex_addon Redaxo Addon template belongs to */
    private $template_addon;

    /**
     * Constructor. Sets values. The path that is constructed is during addon
     * update the path of the new addon folder. Otherwise the normal addon path.
     * @param D2UTemplate[] $d2u_templates Array with D2U templates
     * @param string $template_folder Folder, in which templates can be found.
     * Trailing slash must be included. Default is D2UTemplateManager::TEMPLATE_FOLDER.
     * @param string $addon_key Redaxo Addon name template belongs to, default "d2u_helper"
     */
    public function __construct($d2u_templates, $template_folder = '', $addon_key = 'd2u_helper')
    {
        $template_folder = $template_folder ?: self::TEMPLATE_FOLDER;
        $this->template_addon = rex_addon::get($addon_key);
        $this->template_folder = $this->template_addon->getPath($template_folder);
        // Path during addon update
        $temp_update_folder = $this->template_addon->getPath('../.new.' . $addon_key . '/' . $template_folder);
        if (file_exists($temp_update_folder)) {
            $this->template_folder = $temp_update_folder;
        }

        for ($i = 0; $i < count($d2u_templates); ++$i) {
            $d2u_template = $d2u_templates[$i];
            $d2u_template->initRedaxoContext($this->template_addon, $this->template_folder);
            $this->d2u_templates[$i] = $d2u_template;
        }
    }

    /**
     * Perform pending template updates.
     */
    public function autoupdate()
    {
        foreach ($this->d2u_templates as $template) {
            // Only check autoupdate, not if update is needed. That would not work during addon update
            if ($template->isAutoupdateActivated()) {
                $template->install();
            }
        }
        rex_delete_cache();
    }

    /**
     * Performs actions offerd in manager list function.
     * @param string $d2u_template_id D2U Template ID
     * @param string $function Possible values: autoupdate
     * @param int $paired_template_id Redaxo template ID
     */
    public function doActions($d2u_template_id, $function, $paired_template_id)
    {
        // Form actions
        for ($i = 0; $i < count($this->d2u_templates); ++$i) {
            $template = $this->d2u_templates[$i];
            if ($template->getD2UId() == $d2u_template_id) {
                if ('autoupdate' == $function) {
                    if ($template->isAutoupdateActivated()) {
                        $template->disableAutoupdate();
                        echo rex_view::success($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_autoupdate_deactivated'));
                    } else {
                        $template->activateAutoupdate();
                        echo rex_view::success($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_autoupdate_activated'));
                    }
                } elseif ('unlink' == $function) {
                    $template->unlink();
                    $this->d2u_templates[$i] = $template;
                    echo rex_view::success($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_templates_pair_unlinked'));
                } else {
                    $success = $template->install($paired_template_id);
                    if ($success && array_key_exists($template->getRedaxoId(), self::getRexTemplates())) {
                        echo rex_view::success($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_templates_installed'));
                    } else {
                        echo rex_view::error($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_install_error'));
                    }
                }
                break;
            }
        }

        // Save before cache deletion
        rex_config::save();

        rex_delete_cache();
    }

    /**
     * Get templates offered by D2U Helper addon.
     * @return D2UTemplate[] Templates offered by D2U Helper addon
     */
    public static function getD2UHelperTemplates()
    {
        $d2u_templates = [];
        $d2u_templates[] = new D2UTemplate('00-1',
            'Big Header Template',
            20);
        $d2u_templates[] = new D2UTemplate('01-1',
            'Side Picture Template',
            12);
        $d2u_templates[] = new D2UTemplate('02-1',
            'Header Pic Template',
            15);
        $d2u_templates[] = new D2UTemplate('03-1',
            'Immo Template - 2 Columns',
            14);
        $d2u_templates[] = new D2UTemplate('03-2',
            'Immo Window Advertising Template',
            11);
        $d2u_templates[] = new D2UTemplate('04-1',
            'Header Slider Template with Slogan',
            14);
        $d2u_templates[] = new D2UTemplate('04-2',
            'Header Slider Template',
            20);
        $d2u_templates[] = new D2UTemplate('04-3',
            'Header Slider Template with news column',
            15);
        $d2u_templates[] = new D2UTemplate('05-1',
            'Double Logo Template',
            13);
        $d2u_templates[] = new D2UTemplate('06-1',
            'Paper Sheet Template',
            7);
        $d2u_templates[] = new D2UTemplate('99-1',
            'Feed Generator',
            1);
        return $d2u_templates;
    }

    /**
     * Get initialized template by ID.
     * @param string $template_id D2U template ID
     * @return D2UTemplate|bool Requested template object, in case template was not found: false
     */
    public function getTemplate($template_id)
    {
        foreach ($this->d2u_templates as $d2u_template) {
            if ($d2u_template->getD2UId() == $template_id) {
                return $d2u_template;
            }
        }
        return false;
    }

    /**
     * Get paired template ids.
     * @param string $addon_key Redaxo addon key for filtering pairs. If missing,
     * pairs of all D2U addons are searched.
     * @return string[] Paired template ids. Key is Redaxo template id, value is
     * an array width D2U template id (named "d2u_id") and addon key (named "addon_key").
     */
    public static function getTemplatePairs($addon_key = '')
    {
        $paired_templates = [];
        $query_paired = 'SELECT * FROM `'. \rex::getTablePrefix() .'config` WHERE `key` LIKE "template_%"'
            .('' == $addon_key ? '' : ' AND `namespace` = "'. $addon_key .'"');
        $result_paired = rex_sql::factory();
        $result_paired->setQuery($query_paired);
        for ($i = 0; $i < $result_paired->getRows(); ++$i) {
            $template_info = json_decode($result_paired->getValue('value'), true);
            if (is_array($template_info) && array_key_exists('rex_template_id', $template_info)) {
                $paired_templates[$template_info['rex_template_id']] = [
                    'd2u_id' => str_replace('template_', '', $result_paired->getValue('key')),
                    'addon_key' => $result_paired->getValue('key'),
                ];
            }
            $result_paired->next();
        }
        return $paired_templates;
    }

    /**
     * Gets Redaxo Templates.
     * @param bool if true, reload of templates is performed
     * @return string[] Redaxo templates. Key ist the template ID, value ist the template name
     */
    public static function getRexTemplates($unpaired_only = false)
    {
        $rex_templates = [];
        // Get Redaxo modules (must be after form actions, in case new module was installed)
        $query = 'SELECT id, name FROM ' . \rex::getTablePrefix() . 'template ORDER BY name';
        $result = rex_sql::factory();
        $result->setQuery($query);
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $rex_templates[$result->getValue('id')] = $result->getValue('name');
            $result->next();
        }

        if ($unpaired_only) {
            // Remove paired modules
            foreach (array_keys(self::getTemplatePairs()) as $rex_id) {
                if (array_key_exists($rex_id, $rex_templates)) {
                    unset($rex_templates[$rex_id]);
                }
            }
        }

        return $rex_templates;
    }

    /**
     * Prints list that offers template managing options.
     */
    public function showManagerList()
    {
        echo '<form action="'. rex_url::currentBackendPage() .'" method="post">';
        echo '<section class="rex-page-section">';
        echo '<div class="panel panel-default">';
        echo '<header class="panel-heading"><div class="panel-title">'. rex_i18n::msg('d2u_helper_meta_templates') .'</div></header>';

        echo '<table class="table table-striped table-hover">';

        echo '<thead>';
        echo '<tr>';
        echo '<th class="rex-table-id">'. rex_i18n::msg('d2u_helper_d2u_id') .'</th>';
        echo '<th>'. rex_i18n::msg('d2u_helper_templates_d2u_template_name') .'</th>';
        echo '<th>'. rex_i18n::msg('version') .'</th>';
        echo '<th>'. rex_i18n::msg('d2u_helper_templates_paired_rex_template') .'</th>';
        echo '<th>'. rex_i18n::msg('d2u_helper_autoupdate') .'</th>';
        echo '<th>'. rex_i18n::msg('module_functions') .'</th>';
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';

        // Redaxo templates
        $rex_templates = self::getRexTemplates();
        $unpaired_rex_templates = self::getRexTemplates(true);

        foreach ($this->d2u_templates as $template) {
            echo '<tr>';
            echo '<td>'. $template->getD2UId() .'</td>';
            echo '<td>'. $template->getName() .'</td>';
            echo '<td>'. $template->getRevision() .'</td>';
            // Redaxo templates
            echo '<td>';
            if (0 == $template->getRedaxoId()) {
                $templates_select = new rex_select();
                $templates_select->addOption(rex_i18n::msg('d2u_helper_templates_pair_new'), 0);
                $templates_select->addArrayOptions($unpaired_rex_templates);
                $templates_select->setName('pair_'. $template->getD2UId());
                $templates_select->setAttribute('class', 'form-control');
                $templates_select->setSelected(0);
                echo $templates_select->get();
            } else {
                echo '<a href="'. rex_url::currentBackendPage(['function' => 'unlink', 'd2u_template_id' => $template->getD2UId()]) .'" title="'. rex_i18n::msg('d2u_helper_modules_pair_unlink') .'"><i class="rex-icon fa-chain-broken"></i> ';
                echo $rex_templates[$template->getRedaxoId()];
                echo '</a>';
            }
            echo '</td>';
            // Autoupdate
            echo '<td>';
            if ($template->isInstalled()) {
                $message = $template->isAutoupdateActivated() ? rex_i18n::msg('package_deactivate') : rex_i18n::msg('package_activate');
                $icon = $template->isAutoupdateActivated() ? 'rex-icon-package-is-activated' : 'rex-icon-package-not-activated';
                echo '<a href="'. rex_url::currentBackendPage(['function' => 'autoupdate', 'd2u_template_id' => $template->getD2UId()]) .'"><i class="rex-icon '. $icon .'"></i> '. $message .' </a>';
            }
            echo '</td>';
            // Action
            echo '<td class="rex-table-action"><button type="submit" name="d2u_template_id" class="btn btn-save" value="'. $template->getD2UId() .'">';
            if (!$template->isInstalled()) {
                echo '<i class="rex-icon rex-icon-package-not-installed"></i> '.rex_i18n::msg('package_install');
            } elseif ($template->isUpdateNeeded()) {
                echo '<i class="rex-icon rex-icon-package-is-installed"></i> '.rex_i18n::msg('update');
            } else {
                echo '<i class="rex-icon rex-icon-package-is-activated"></i> '.rex_i18n::msg('package_reinstall');
            }
            echo '</button></td>';
            echo '</tr>';
        }
        echo '</tbody>';

        echo '</table>';

        echo '</div>';
        echo '</section>';
        echo '</form>';
    }
}

/**
 * Class managing templates published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class D2UTemplate
{
    /**
     * CSS file name for modules.
     */
    public const TEMPLATE_CSS_FILE = 'styles.css';

    /**
     * CSS file name for modules.
     */
    public const TEMPLATE_FILE = 'template.php';

    /**
     * CSS file name for modules.
     */
    public const TEMPLATE_INSTALL = 'install.php';

    /**
     * CSS file name for modules.
     */
    public const TEMPLATE_UNINSTALL = 'uninstall.php';

    /** @var int D2U Template ID */
    private $d2u_template_id = '';

    /** @var bool true if autoupdate ist activated */
    private $autoupdate = false;

    /** @var string Templates title or name */
    private $name = '';

    /** @var int Template version number */
    private $revision = 0;

    /** @var int Redaxo Template ID */
    private $rex_template_id = 0;

    /** @var rex_addon Redaxo Addon template belongs to */
    private $rex_addon;

    /** @var template_folder Redaxo Addon template folder */
    private $template_folder;

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
    public function activateAutoupdate()
    {
        $this->autoupdate = true;
        $this->setConfig();
    }

    /**
     * Disable autoupdate.
     */
    public function disableAutoupdate()
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
            $template_css = file_get_contents($this->template_folder . self::TEMPLATE_CSS_FILE);
        }

        // Footer CSS
        $footer_type = rex_config::get('d2u_helper', 'footer_type', '');
        if ('' !== $footer_type && file_exists(rex_path::addonAssets('d2u_helper', 'template/footer/d2u_template_footer_'. $footer_type .'.css'))) {
            $template_css .= file_get_contents(rex_path::addonAssets('d2u_helper', 'template/footer/d2u_template_footer_'. $footer_type .'.css'));
        }
        // Language modal CSS
        if (file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_language_modal.css'))) {
            $template_css .= file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_language_modal.css'));
        }
        // Search icon CSS
        if (file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_search_icon.css'))) {
            $template_css .= file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_search_icon.css'));
        }
        // Header slider CSS
        if ('04' == substr($this->d2u_template_id, 0, 2) && file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_header_slider.css'))) {
            $template_css .= file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_header_slider.css'));
        }
        // Consent manager CSS
        if (rex_addon::get('consent_manager')->isAvailable() && file_exists(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_consent_manager.css'))) {
            $template_css .= file_get_contents(rex_path::addonAssets('d2u_helper', 'template/header/d2u_template_consent_manager.css'));
        }
        // CTA box CSS
        if (rex_config::get('d2u_helper', 'show_cta_box', false) && file_exists(rex_path::addonAssets('d2u_helper', 'template/d2u_template_cta_box.css'))) {
            $template_css .= file_get_contents(rex_path::addonAssets('d2u_helper', 'template/d2u_template_cta_box.css'));
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
     * @return string Redaxo Template Id
     */
    public function getRedaxoId()
    {
        return $this->rex_template_id;
    }

    /**
     * Initializes object in Redaxo context: sets Redaxo template id and folders.
     * @param rex_addon Redaxo Addon template belongs to
     * @param string Complete folder string, in which template files can be found.
     * Trailing slash must be included.
     */
    public function initRedaxoContext($template_addon, $template_folder)
    {
        $this->rex_addon = $template_addon;
        if ($this->rex_addon->hasConfig('template_'. $this->d2u_template_id)) {
            $config = $this->rex_addon->getConfig('template_'. $this->d2u_template_id);
            if (array_key_exists($config['rex_template_id'], D2UTemplateManager::getRexTemplates())) {
                // Get paired template id
                $this->rex_template_id = $config['rex_template_id'];
                // Get Autoupdate settings
                $this->autoupdate = 'active' == $config['autoupdate'] ? true : false;
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
     * @param int redaxo template id, if not passed, already available ID is taken
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

        if (0 == $this->rex_template_id && $rex_template_id > 0) {
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
            $this->rex_template_id = $insert->getLastId();
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
     */
    public function isAutoupdateActivated()
    {
        return $this->autoupdate;
    }

    /**
     * Checks if template is installed in Redaxo.
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
        $removemod = rex_sql::factory();
        $removemod->setTable(\rex::getTablePrefix() . 'template');
        $removemod->setWhere(['id' => $this->d2u_template_id]);
        $removemod->delete();

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
    private function setConfig()
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
    public function unlink()
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
