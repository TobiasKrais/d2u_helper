<?php
/**
 * @api
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

    /** 
     * @var array<D2UTemplate> Array with D2U templates 
     */
    public array $d2u_templates = [];

    /**
     * @var string Folder within addon, in which templates can be found. Trailing
     * slash must be included.
     */
    public string $template_folder = '';

    /** 
     * @var rex_addon_interface Redaxo Addon template belongs to 
     */
    private rex_addon_interface $template_addon;

    /**
     * Constructor. Sets values. The path that is constructed is during addon
     * update the path of the new addon folder. Otherwise the normal addon path.
     * @param array<D2UTemplate> $d2u_templates Array with D2U templates
     * @param string $template_folder Folder, in which templates can be found.
     * Trailing slash must be included. Default is D2UTemplateManager::TEMPLATE_FOLDER.
     * @param string $addon_key Redaxo Addon name template belongs to, default "d2u_helper"
     */
    public function __construct($d2u_templates, $template_folder = '', $addon_key = 'd2u_helper')
    {
        $template_folder = $template_folder !== '' ? $template_folder : self::TEMPLATE_FOLDER;
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
    public function autoupdate():void
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
    public function doActions($d2u_template_id, $function, $paired_template_id):void
    {
        // Form actions
        for ($i = 0; $i < count($this->d2u_templates); ++$i) {
            $template = $this->d2u_templates[$i];
            if ($template->getD2UId() === $d2u_template_id) {
                if ('autoupdate' === $function) {
                    if ($template->isAutoupdateActivated()) {
                        $template->disableAutoupdate();
                        echo rex_view::success($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_autoupdate_deactivated'));
                    } else {
                        $template->activateAutoupdate();
                        echo rex_view::success($template->getD2UId() .' '. $template->getName() .': '. rex_i18n::msg('d2u_helper_autoupdate_activated'));
                    }
                } elseif ('unlink' === $function) {
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
            if ($d2u_template->getD2UId() === $template_id) {
                return $d2u_template;
            }
        }
        return false;
    }

    /**
     * Get paired template ids.
     * @param string $addon_key Redaxo addon key for filtering pairs. If missing,
     * pairs of all D2U addons are searched.
     * @return array<int, array<string, string>> Paired template ids. Key is Redaxo template id, value is
     * an array width D2U template id (named "d2u_id") and addon key (named "addon_key").
     */
    private static function getTemplatePairs($addon_key = '')
    {
        $paired_templates = [];
        $query_paired = 'SELECT * FROM `'. \rex::getTablePrefix() .'config` WHERE `key` LIKE "template_%"'
            .('' === $addon_key ? '' : ' AND `namespace` = "'. $addon_key .'"');
        $result_paired = rex_sql::factory();
        $result_paired->setQuery($query_paired);
        for ($i = 0; $i < $result_paired->getRows(); ++$i) {
            $template_info = json_decode((string) $result_paired->getValue('value'), true);
            if (is_array($template_info) && array_key_exists('rex_template_id', $template_info)) {
                $paired_templates[(int) $template_info['rex_template_id']] = [
                    'd2u_id' => str_replace('template_', '', (string) $result_paired->getValue('key')),
                    'addon_key' => (string) $result_paired->getValue('key'),
                ];
            }
            $result_paired->next();
        }
        return $paired_templates;
    }

    /**
     * Gets Redaxo Templates.
     * @param bool $unpaired_only if true, reload of templates is performed
     * @return array<int, string> Redaxo templates. Key ist the template ID, value ist the template name
     */
    public static function getRexTemplates($unpaired_only = false)
    {
        $rex_templates = [];
        // Get Redaxo modules (must be after form actions, in case new module was installed)
        $query = 'SELECT id, name FROM ' . \rex::getTablePrefix() . 'template ORDER BY name';
        $result = rex_sql::factory();
        $result->setQuery($query);
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $rex_templates[(int) $result->getValue('id')] = (string) $result->getValue('name');
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
    public function showManagerList():void
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
            if (0 === $template->getRedaxoId()) {
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