<?php

/**
 * @api
 * Class managing modules published by www.design-to-use.de
 *
 * @author Tobias Krais
 */
class D2UModuleManager
{
    /**
     * Folder where modules can be found.
     */
    public const MODULE_FOLDER = 'modules/';

    /** @var array<D2UModule> Array with D2U modules */
    public array $d2u_modules = [];

    /**
     * @var string Folder within addon, in which modules can be found. Trailing
     * slash must be included.
     */
    public string $module_folder = '';

    /** @var rex_addon_interface Redaxo Addon module belongs to */
    private rex_addon_interface $module_addon;

    /**
     * Constructor. Sets values. The path that is constructed is during addon
     * update the path of the new addon folder. Otherwise the normal addon path.
     * @param array<D2UModule> $d2u_modules Array with D2U modules
     * @param string $module_folder Folder, in which modules can be found.
     * Trailing slash must be included. Default is D2UModuleManager::MODULE_FOLDER.
     * @param string $addon_key Redaxo Addon name module belongs to, default "d2u_helper"
     */
    public function __construct($d2u_modules, $module_folder = '', $addon_key = 'd2u_helper')
    {
        $module_folder = '' !== $module_folder ? $module_folder : self::MODULE_FOLDER;
        $this->module_addon = rex_addon::get($addon_key);
        $this->module_folder = $this->module_addon->getPath($module_folder);
        // Path during addon update
        $temp_update_folder = $this->module_addon->getPath('../.new.' . $addon_key . '/' . $module_folder);
        if (file_exists($temp_update_folder)) {
            $this->module_folder = $temp_update_folder;
        }

        for ($i = 0; $i < count($d2u_modules); ++$i) {
            $d2u_module = $d2u_modules[$i];
            $d2u_module->initRedaxoContext($this->module_addon, $this->module_folder);
            $this->d2u_modules[$i] = $d2u_module;
        }
    }

    /**
     * Update modules.
     */
    public function autoupdate(): void
    {
        foreach ($this->d2u_modules as $module) {
            // Only check autoupdate, not if needed. That would not work during addon update
            if ($module->isAutoupdateActivated()) {
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
    public function doActions($d2u_module_id, $function, $paired_module_id): void
    {
        // Form actions
        for ($i = 0; $i < count($this->d2u_modules); ++$i) {
            $module = $this->d2u_modules[$i];
            if ($module->getD2UId() === $d2u_module_id) {
                if ('autoupdate' === $function) {
                    if ($module->isAutoupdateActivated()) {
                        $module->disableAutoupdate();
                        echo rex_view::success($module->getD2UId() .' '. $module->getName() .': '. rex_i18n::msg('d2u_helper_autoupdate_deactivated'));
                    } else {
                        $module->activateAutoupdate();
                        echo rex_view::success($module->getD2UId() .' '. $module->getName() .': '. rex_i18n::msg('d2u_helper_autoupdate_activated'));
                    }
                } elseif ('unlink' === $function) {
                    $module->unlink();
                    $this->d2u_modules[$i] = $module;
                    echo rex_view::success($module->getD2UId() .' '. $module->getName() .': '. rex_i18n::msg('d2u_helper_modules_pair_unlinked'));
                } else {
                    $success = $module->install($paired_module_id);
                    if ($success && array_key_exists($module->getRedaxoId(), self::getRexModules())) {
                        rex_delete_cache();
                        echo rex_view::success($module->getD2UId() .' '. $module->getName() .': '. rex_i18n::msg('d2u_helper_modules_installed'));
                    } else {
                        echo rex_view::error($module->getD2UId() .' '. $module->getName() .': '. rex_i18n::msg('d2u_helper_install_error'));
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
    public function getAutoCSS()
    {
        $css = '';
        foreach ($this->d2u_modules as $module) {
            if ($module->isInstalled() && '' !== $module->getCSS()) {
                $css .= $module->getCSS() . PHP_EOL;
            }
        }
        return $css;
    }

    /**
     * Get combined JavaScript for installed modules.
     * @return string Combined JavaScript
     */
    public function getAutoJS()
    {
        $js = '';
        foreach ($this->d2u_modules as $module) {
            if ($module->isInstalled() && '' !== $module->getJS()) {
                $js .= $module->getJS() . PHP_EOL;
            }
        }
        return $js;
    }

    /**
     * Get modules offered by D2U Helper addon.
     * @return D2UModule[] Modules offered by D2U Helper addon
     */
    public static function getModules()
    {
        $modules = [];
        $modules[] = new D2UModule('00-1',
            'Umbruch ganze Breite',
            7);
        $modules[] = new D2UModule('01-1',
            'Texteditor',
            12);
        $modules[] = new D2UModule('01-2',
            'Texteditor mit Bild und Fettschrift',
            15);
        $modules[] = new D2UModule('01-3',
            'Texteditor in Alertbox',
            1);
        $modules[] = new D2UModule('02-1',
            'Überschrift',
            11);
        $modules[] = new D2UModule('02-2',
            'Überschrift mit Klapptext',
            5);
        $modules[] = new D2UModule('02-3',
            'Überschrift mit Untertitel und Textfeld',
            7);
        $modules[] = new D2UModule('02-4',
            'Überschrift mit Hintergrundbild und 2 Buttons',
            1);
        $modules[] = new D2UModule('02-5',
            'Inhaltsverzeichnis der Überschriften',
            1);
        $modules[] = new D2UModule('03-1',
            'Bild',
            12);
        $modules[] = new D2UModule('03-2',
            'Bildergalerie Ekko Lightbox',
            12);
        $modules[] = new D2UModule('03-3',
            '360° Bild',
            1);
        $modules[] = new D2UModule('04-1',
            'Google Maps Karte',
            13);
        $modules[] = new D2UModule('04-2',
            'OpenStreetMap Karte',
            5);
        $modules[] = new D2UModule('05-1',
            'Artikelweiterleitung',
            14);
        $modules[] = new D2UModule('05-2',
            'Artikel aus anderer Sprache übernehmen',
            5);
        $modules[] = new D2UModule('06-1',
            'YouTube Video einbinden',
            15);
        $modules[] = new D2UModule('06-2',
            'IFrame einbinden',
            5);
        $modules[] = new D2UModule('06-3',
            'Video mit Plyr einbinden',
            3);
        $modules[] = new D2UModule('06-4',
            'Videoliste mit Plyr einbinden',
            2);
        $modules[] = new D2UModule('07-1',
            'JavaScript einbinden',
            2);
        $modules[] = new D2UModule('10-1',
            'Box mit Bild und Ueberschrift',
            4);
        $modules[] = new D2UModule('10-2',
            'Box mit Bild und Text',
            4);
        $modules[] = new D2UModule('10-3',
            'Box mit Downloads',
            9);
        $modules[] = new D2UModule('11-1',
            'YForm Kontaktformular (DSGVO kompatibel)',
            12);
        $modules[] = new D2UModule('11-2',
            'Box mit Kontaktinformationen',
            2);
        $modules[] = new D2UModule('12-1',
            'Feeds Stream Galerie',
            4);
        $modules[] = new D2UModule('13-1',
            'Lauftext',
            4);
        $modules[] = new D2UModule('14-1',
            'Search It Suchmodul',
            5);
        $modules[] = new D2UModule('15-1',
            'Kategorie mit Liste der Unterkategorien',
            3);
        // 20-x reserved for D2U Addresss
        // 21-x reserved for D2U History
        // 22-x reserved for D2U Staff
        // 23-x reserved for D2U Jobs
        // 24-x reserved for D2U Linkbox
        // 25-x reserved for D2U Partner
        // 26-x reserved for D2U Courses
        // 27-x reserved for D2U Heinzmann
        // 28-x reserved for Inotec City
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
     * @return array<int, array<string,string>> Paired module ids. Key is Redaxo module id, value is
     * D2U module id.
     */
    public static function getModulePairs()
    {
        $paired_modules = [];
        $query_paired = 'SELECT id, attributes, `key` FROM `'. \rex::getTablePrefix() .'module` WHERE `key` LIKE "d2u_%"';
        $result_paired = rex_sql::factory();
        $result_paired->setQuery($query_paired);
        for ($i = 0; $i < $result_paired->getRows(); ++$i) {
            $attributes = json_decode((string) $result_paired->getValue('attributes'), true);
            if (is_array($attributes)) {
                $paired_modules[(int) $result_paired->getValue('id')] = [
                    'd2u_id' => str_replace('d2u_', '', (string) $result_paired->getValue('key')),
                    'addon_key' => (string) $attributes['addon_key'],
                ];
            }
            $result_paired->next();
        }

        return $paired_modules;
    }

    /**
     * Gets Redaxo Modules.
     * @param bool $unpaired_only if true, only unpaired modules are returned
     * @return array<int, string> Redaxo modules. Key ist the module ID, value ist the module name
     */
    public static function getRexModules($unpaired_only = false)
    {
        $rex_modules = [];
        // Get Redaxo modules (must be after form actions, in case new module was installed)
        $query = 'SELECT id, name FROM ' . \rex::getTablePrefix() . 'module '
            .($unpaired_only ? "WHERE `key` NOT LIKE 'd2u_%' OR `key` IS NULL " : '')
            . 'ORDER BY name';
        $result = rex_sql::factory();
        $result->setQuery($query);
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $rex_modules[(int) $result->getValue('id')] = (string) $result->getValue('name');
            $result->next();
        }

        return $rex_modules;
    }

    /**
     * Prints list that offers module managing options.
     */
    public function showManagerList(): void
    {
        $url_params = [];
        // Compatibility for MultiNewsletter installation site
        if ('' !== filter_input(INPUT_GET, 'chapter')) {
            $url_params['chapter'] = (string) filter_input(INPUT_GET, 'chapter');
        }
        echo '<form action="'. rex_url::currentBackendPage($url_params) .'" method="post">';
        echo '<section class="rex-page-section">';
        echo '<div class="panel panel-default">';
        echo '<header class="panel-heading"><div class="panel-title">'. rex_i18n::msg('d2u_helper_meta_modules') .'</div></header>';

        echo '<table class="table table-striped table-hover">';

        echo '<thead>';
        echo '<tr>';
        echo '<th class="rex-table-id">'. rex_i18n::msg('d2u_helper_d2u_id') .'</th>';
        echo '<th>'. rex_i18n::msg('d2u_helper_modules_d2u_module_name') .'</th>';
        echo '<th>'. rex_i18n::msg('version') .'</th>';
        echo '<th>'. rex_i18n::msg('d2u_helper_modules_paired_rex_module') .'</th>';
        echo '<th>'. rex_i18n::msg('d2u_helper_autoupdate') .'</th>';
        echo '<th>'. rex_i18n::msg('module_functions') .'</th>';
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';

        // Redaxo modules
        $rex_modules = self::getRexModules();
        $unpaired_rex_modules = self::getRexModules(true);
        // Fix follows: directly after module installation, newly paired module is not detected as paired
        $installed_d2u_module_id = rex_request('d2u_module_id', 'string');
        if ('' !== $installed_d2u_module_id) {
            foreach ($unpaired_rex_modules as $rex_id => $name) {
                if (str_contains($name, $installed_d2u_module_id)) {
                    unset($unpaired_rex_modules[$rex_id]);
                }
            }
        }

        foreach ($this->d2u_modules as $module) {
            echo '<tr>';
            echo '<td>'. $module->getD2UId() .'</td>';
            echo '<td>'. $module->getName() .'</td>';
            echo '<td>'. $module->getRevision() .'</td>';
            // Redaxo modules
            echo '<td>';
            if (0 === $module->getRedaxoId()) {
                $modules_select = new rex_select();
                $modules_select->addOption(rex_i18n::msg('d2u_helper_modules_pair_new'), 0);
                $modules_select->addArrayOptions($unpaired_rex_modules);
                $modules_select->setName('pair_'. $module->getD2UId());
                $modules_select->setAttribute('class', 'form-control');
                $modules_select->setSelected(0);
                echo $modules_select->get();
            } else {
                echo '<a href="'. rex_url::currentBackendPage(['function' => 'unlink', 'd2u_module_id' => $module->getD2UId()]) .'" title="'. rex_i18n::msg('d2u_helper_modules_pair_unlink') .'"><i class="rex-icon fa-chain-broken"></i> ';
                echo $rex_modules[$module->getRedaxoId()];
                echo '</a>';
            }
            echo '</td>';
            // Autoupdate
            echo '<td>';
            if ($module->isInstalled()) {
                echo '<a href="'. rex_url::currentBackendPage(['function' => 'autoupdate', 'd2u_module_id' => $module->getD2UId()]) .'">'
                    . '<i class="rex-icon '. ($module->isAutoupdateActivated() ? 'rex-icon-package-is-activated' : 'rex-icon-package-not-activated') .'"></i> '
                    . ($module->isAutoupdateActivated() ? rex_i18n::msg('package_deactivate') : rex_i18n::msg('package_activate')) .' </a>';
            }
            echo '</td>';
            // Action
            echo '<td class="rex-table-action"><button type="submit" name="d2u_module_id" class="btn btn-save" value="'. $module->getD2UId() .'">';
            if (!$module->isInstalled()) {
                echo '<i class="rex-icon rex-icon-package-not-installed"></i> '. rex_i18n::msg('package_install');
            } elseif ($module->isUpdateNeeded()) {
                echo '<i class="rex-icon rex-icon-package-is-installed"></i> '. rex_i18n::msg('update');
            } else {
                echo '<i class="rex-icon rex-icon-package-is-activated"></i> '. rex_i18n::msg('package_reinstall');
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
