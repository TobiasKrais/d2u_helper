<?php

// Set Session
if ('' === rex_session('d2u_helper_translation')) {
    $default_settings = ['clang_id' => rex_clang::getStartId(), 'filter' => 'update'];
    rex_request::setSession('d2u_helper_translation', $default_settings);
}

// Save form in session
if ('save' === filter_input(INPUT_POST, 'btn_save')) {
    $settings = rex_post('settings', 'array', []);
    rex_request::setSession('d2u_helper_translation', $settings);
}

?>

<h2><?= rex_i18n::msg('d2u_helper_meta_translations') ?></h2>
<p><?= rex_i18n::msg('d2u_helper_translations_description') ?></p>

<?php
if (1 === count(rex_clang::getAll())) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_translations_none'));
} else {
    $source_clang_id = (int) rex_config::get('d2u_helper', 'default_lang');
    $target_clang_id = is_array(rex_session('d2u_helper_translation')) && array_key_exists('clang_id', rex_session('d2u_helper_translation')) ? (int) rex_session('d2u_helper_translation')['clang_id'] : rex_clang::getStartId();
    $filter_type = is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) ? (string) rex_session('d2u_helper_translation')['filter'] : 'update';
?>
	<form action="<?= rex_url::currentBackendPage() ?>" method="post">
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_helper_translations_filter') ?></div></header>
			<div class="panel-body">
				<?php
                    // Language selection
                    $lang_options = [];
                    if (count(rex_clang::getAll()) > 1) {
                        foreach (rex_clang::getAll() as $rex_clang) {
                            if ($source_clang_id !== $rex_clang->getId() && rex::getUser() instanceof rex_user &&
                                    (\rex::getUser()->isAdmin() || \rex::getUser()->getComplexPerm('clang') instanceof rex_clang_perm && \rex::getUser()->getComplexPerm('clang')->hasPerm($rex_clang->getId()))) {
                                $lang_options[$rex_clang->getId()] = $rex_clang->getName();
                            }
                        }
                    }
                    if (!in_array($target_clang_id, array_keys($lang_options))) {
                        $target_clang_id = array_keys($lang_options)[0];
                    }
                    d2u_addon_backend_helper::form_select('d2u_helper_translations_language', 'settings[clang_id]', $lang_options, [$target_clang_id]);

                    $filter_options = [
                        'update' => rex_i18n::msg('d2u_helper_translations_filter_update'),
                        'missing' => rex_i18n::msg('d2u_helper_translations_filter_missing'),
                    ];
                    d2u_addon_backend_helper::form_select('d2u_helper_translations_filter_select', 'settings[filter]', $filter_options, [$filter_type]);
                ?>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="save"><?= rex_i18n::msg('d2u_helper_translations_apply') ?></button>
					</div>
				</div>
			</footer>
		</div>
	</form>
<?php
    if (rex_addon::get('d2u_address')->isAvailable()) {
        $continents = D2U_Address\Continent::getTranslationHelperObjects($target_clang_id, $filter_type);
        $countries = D2U_Address\Country::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_address') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-globe"></i></small> <?= rex_i18n::msg('d2u_address_continents') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($continents) > 0) {
                        echo '<ul>';
                        foreach ($continents as $continent) {
                            if ('' !== $continent->name) {
                                $continent = new \D2U_Address\Continent($continent->continent_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_address/continent', ['entry_id' => $continent->continent_id, 'func' => 'edit']) .'">'. $continent->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-flag"></i></small> <?= rex_i18n::msg('d2u_address_countries') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($countries) > 0) {
                        echo '<ul>';
                        foreach ($countries as $country) {
                            if ('' !== $country->name) {
                                $country = new \D2U_Address\Country($country->country_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_address/country', ['entry_id' => $country->country_id, 'func' => 'edit']) .'">'. $country->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_history')->isAvailable()) {
        $history_events = D2U_History\History::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_history') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-flag"></i></small> <?= rex_i18n::msg('d2u_history_events') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($history_events) > 0) {
                        echo '<ul>';
                        foreach ($history_events as $history_event) {
                            if ('' !== $history_event->name) {
                                $history_event = new \D2U_History\History($history_event->history_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_history/history', ['entry_id' => $history_event->history_id, 'func' => 'edit']) .'">'. $history_event->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_immo')->isAvailable()) {
        $categories = D2U_Immo\Category::getTranslationHelperObjects($target_clang_id, $filter_type);
        $properties = D2U_Immo\Property::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_immo') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?= rex_i18n::msg('d2u_helper_categories') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($categories) > 0) {
                        echo '<ul>';
                        foreach ($categories as $category) {
                            if ('' !== $category->name) {
                                $category = new \D2U_Immo\Category($category->category_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_immo/category', ['entry_id' => $category->category_id, 'func' => 'edit']) .'">'. $category->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-home"></i></small> <?= rex_i18n::msg('d2u_immo_properties') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($properties) > 0) {
                        echo '<ul>';
                        foreach ($properties as $property) {
                            if ('' !== $property->name) {
                                $property = new \D2U_Immo\Property($property->property_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_immo/property', ['entry_id' => $property->property_id, 'func' => 'edit']) .'">'. $property->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                if (rex_plugin::get('d2u_immo', 'window_advertising')->isAvailable()) {
                    $ads = D2U_Immo\Advertisement::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-desktop"></i></small> <?= rex_i18n::msg('d2u_immo_window_advertising') .' - '. rex_i18n::msg('d2u_immo_window_advertising_ads') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($ads) > 0) {
                        echo '<ul>';
                        foreach ($ads as $ad) {
                            if ('' !== $ad->title) {
                                $ad = new \D2U_Immo\Advertisement($ad->ad_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_immo/window_advertising/advertisement', ['entry_id' => $ad->ad_id, 'func' => 'edit']) .'">'. $ad->title .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
            ?>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_jobs')->isAvailable()) {
        $categories = D2U_Jobs\Category::getTranslationHelperObjects($target_clang_id, $filter_type);
        $jobs = D2U_Jobs\Job::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_jobs') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?= rex_i18n::msg('d2u_helper_category') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($categories) > 0) {
                        echo '<ul>';
                        foreach ($categories as $category) {
                            if ('' !== $category->name) {
                                $category = new \D2U_Jobs\Category($category->category_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_jobs/category', ['entry_id' => $category->category_id, 'func' => 'edit']) .'">'. $category->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-users"></i></small> <?= rex_i18n::msg('d2u_jobs_jobs') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($jobs) > 0) {
                        echo '<ul>';
                        foreach ($jobs as $job) {
                            if ('' !== $job->name) {
                                foreach (rex_clang::getAllIds() as $clang_id) {
                                    $temp_job = new \D2U_Jobs\Job($job->job_id, $clang_id);
                                    if ('' !== $temp_job->name) {
                                        $job = $temp_job;
                                        break;
                                    }
                                }
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_jobs/jobs', ['entry_id' => $job->job_id, 'func' => 'edit']) .'">'. $job->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_linkbox')->isAvailable()) {
        $linkboxes = \D2U_Linkbox\Linkbox::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_linkbox') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-window-maximize"></i></small> <?= rex_i18n::msg('d2u_linkbox_linkbox') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($linkboxes) > 0) {
                        echo '<ul>';
                        foreach ($linkboxes as $linkbox) {
                            if ('' !== $linkbox->title) {
                                $linkbox = new \D2U_Linkbox\Linkbox($linkbox->box_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_linkbox/linkbox', ['entry_id' => $linkbox->box_id, 'func' => 'edit']) .'">'. $linkbox->title .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

        if (rex_addon::get('d2u_machinery')->isAvailable()) {
        $categories = Category::getTranslationHelperObjects($target_clang_id, $filter_type);
        $machines = Machine::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_machinery_meta_title') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?= rex_i18n::msg('d2u_helper_categories') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($categories) > 0) {
                        echo '<ul>';
                        foreach ($categories as $category) {
                            if ('' !== $category->name) {
                                $category = new Category($category->category_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/category', ['entry_id' => $category->category_id, 'func' => 'edit']) .'">'. $category->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-module"></i></small> <?= rex_i18n::msg('d2u_machinery_meta_machines') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($machines) > 0) {
                        echo '<ul>';
                        foreach ($machines as $machine) {
                            if ('' !== $machine->name) {
                                $machine = new Machine($machine->machine_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine', ['entry_id' => $machine->machine_id, 'func' => 'edit']) .'">'. $machine->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                if (rex_plugin::get('d2u_machinery', 'equipment')->isAvailable()) {
                    $equipments = Equipment::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $equipment_groups = EquipmentGroup::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-plug"></i></small> <?= rex_i18n::msg('d2u_machinery_equipments') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($equipments) > 0) {
                        echo '<ul>';
                        foreach ($equipments as $equipment) {
                            if ('' !== $equipment->name) {
                                $equipment = new Equipment($equipment->equipment_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/equipment/equipment', ['entry_id' => $equipment->equipment_id, 'func' => 'edit']) .'">'. $equipment->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-plug"></i></small> <?= rex_i18n::msg('d2u_machinery_equipment_groups') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($equipment_groups) > 0) {
                        echo '<ul>';
                        foreach ($equipment_groups as $equipment_group) {
                            if ('' !== $equipment_group->name) {
                                $equipment_group = new EquipmentGroup($equipment_group->group_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/equipment/equipment_group', ['entry_id' => $equipment_group->group_id, 'func' => 'edit']) .'">'. $equipment_group->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
                    $industry_sectors = IndustrySector::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-industry"></i></small> <?= rex_i18n::msg('d2u_machinery_industry_sectors') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($industry_sectors) > 0) {
                        echo '<ul>';
                        foreach ($industry_sectors as $industry_sector) {
                            if ('' !== $industry_sector->name) {
                                $industry_sector = new IndustrySector($industry_sector->industry_sector_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/industry_sectors', ['entry_id' => $industry_sector->industry_sector_id, 'func' => 'edit']) .'">'. $industry_sector->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'machine_certificates_extension')->isAvailable()) {
                    $certificates = Certificate::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-certificate"></i></small> <?= rex_i18n::msg('d2u_machinery_certificates') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($certificates) > 0) {
                        echo '<ul>';
                        foreach ($certificates as $certificate) {
                            if ('' !== $certificate->name) {
                                $certificate = new Certificate($certificate->certificate_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_certificates_extension', ['entry_id' => $certificate->certificate_id, 'func' => 'edit']) .'">'. $certificate->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'machine_features_extension')->isAvailable()) {
                    $features = Feature::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-plug"></i></small> <?= rex_i18n::msg('d2u_machinery_features') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($features) > 0) {
                        echo '<ul>';
                        foreach ($features as $feature) {
                            if ('' !== $feature->name) {
                                $feature = new Feature($feature->feature_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_features_extension', ['entry_id' => $feature->feature_id, 'func' => 'edit']) .'">'. $feature->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'machine_options_extension')->isAvailable()) {
                    $options = Option::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-plug"></i></small> <?= rex_i18n::msg('d2u_machinery_options') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($options) > 0) {
                        echo '<ul>';
                        foreach ($options as $option) {
                            if ('' !== $option->name) {
                                $option = new Option($option->option_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_options_extension', ['entry_id' => $option->option_id, 'func' => 'edit']) .'">'. $option->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'machine_steel_processing_extension')->isAvailable()) {
                    $automations = Automation::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $materials = Material::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $procedures = Procedure::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $processes = Process::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $profiles = Profile::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $supplies = Supply::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $tools = Tool::getTranslationHelperObjects($target_clang_id, $filter_type);
                    $weldings = Welding::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-exchange"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_automation_degrees') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($automations) > 0) {
                        echo '<ul>';
                        foreach ($automations as $automation) {
                            if ('' !== $automation->name) {
                                $automation = new Automation($automation->automation_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/automation', ['entry_id' => $automation->automation_id, 'func' => 'edit']) .'">'. $automation->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-flask"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_material_class') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($materials) > 0) {
                        echo '<ul>';
                        foreach ($materials as $material) {
                            if ('' !== $material->name) {
                                $material = new Material($material->material_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/material', ['entry_id' => $material->material_id, 'func' => 'edit']) .'">'. $material->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-tasks"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_procedures') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($procedures) > 0) {
                        echo '<ul>';
                        foreach ($procedures as $procedure) {
                            if ('' !== $procedure->name) {
                                $procedure = new Procedure($procedure->procedure_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/procedure', ['entry_id' => $procedure->procedure_id, 'func' => 'edit']) .'">'. $procedure->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-sort-numeric-asc"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_processes') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($processes) > 0) {
                        echo '<ul>';
                        foreach ($processes as $process) {
                            if ('' !== $process->name) {
                                $process = new Process($process->process_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/process', ['entry_id' => $process->process_id, 'func' => 'edit']) .'">'. $process->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-i-cursor"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_profiles') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($profiles) > 0) {
                        echo '<ul>';
                        foreach ($profiles as $profile) {
                            if ('' !== $profile->name) {
                                $profile = new Profile($profile->profile_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/profile', ['entry_id' => $profile->profile_id, 'func' => 'edit']) .'">'. $profile->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-stack-overflow"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_supply') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($supplies) > 0) {
                        echo '<ul>';
                        foreach ($supplies as $supply) {
                            if ('' !== $supply->name) {
                                $supply = new Supply($supply->supply_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/supply', ['entry_id' => $supply->supply_id, 'func' => 'edit']) .'">'. $supply->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-magnet"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_tools') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($tools) > 0) {
                        echo '<ul>';
                        foreach ($tools as $tool) {
                            if ('' !== $tool->name) {
                                $tool = new Tool($tool->tool_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/tool', ['entry_id' => $tool->tool_id, 'func' => 'edit']) .'">'. $tool->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-magic"></i></small> <?= rex_i18n::msg('d2u_machinery_machine_steel_extension') .' - '. rex_i18n::msg('d2u_machinery_steel_welding') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($weldings) > 0) {
                        echo '<ul>';
                        foreach ($weldings as $welding) {
                            if ('' !== $welding->name) {
                                $welding = new Welding($welding->welding_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_steel_processing_extension/welding', ['entry_id' => $welding->welding_id, 'func' => 'edit']) .'">'. $welding->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable()) {
                    $usage_areas = UsageArea::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-codepen"></i></small> <?= rex_i18n::msg('d2u_machinery_usage_areas') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($usage_areas) > 0) {
                        echo '<ul>';
                        foreach ($usage_areas as $usage_area) {
                            if ('' !== $usage_area->name) {
                                $usage_area = new UsageArea($usage_area->usage_area_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/machine_usage_area_extension', ['entry_id' => $usage_area->usage_area_id, 'func' => 'edit']) .'">'. $usage_area->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'production_lines')->isAvailable()) {
                    $production_lines = ProductionLine::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-arrows-h"></i></small> <?= rex_i18n::msg('d2u_machinery_production_lines') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($production_lines) > 0) {
                        echo '<ul>';
                        foreach ($production_lines as $production_line) {
                            if ('' !== $production_line->name) {
                                $production_line = new ProductionLine($production_line->production_line_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/production_lines', ['entry_id' => $production_line->production_line_id, 'func' => 'edit']) .'">'. $production_line->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'service_options')->isAvailable()) {
                    $service_options = ServiceOption::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-plug"></i></small> <?= rex_i18n::msg('d2u_machinery_service_options') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($service_options) > 0) {
                        echo '<ul>';
                        foreach ($service_options as $service_option) {
                            if ('' !== $service_option->name) {
                                $service_option = new ServiceOption($service_option->service_option_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/service_option', ['entry_id' => $service_option->service_option_id, 'func' => 'edit']) .'">'. $service_option->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
                if (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
                    $used_machines = UsedMachine::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-truck"></i></small> <?= rex_i18n::msg('d2u_machinery_used_machines') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($used_machines) > 0) {
                        echo '<ul>';
                        foreach ($used_machines as $used_machine) {
                            if ('' !== $used_machine->name) {
                                $used_machine = new UsedMachine($used_machine->used_machine_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_machinery/used_machines', ['entry_id' => $used_machine->used_machine_id, 'func' => 'edit']) .'">'. $used_machine->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
            ?>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_staff')->isAvailable()) {
        $staff_members = Staff::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_staff') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-user-circle"></i></small> <?= rex_i18n::msg('d2u_staff_staff') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($staff_members) > 0) {
                        echo '<ul>';
                        foreach ($staff_members as $staff_member) {
                            if ('' !== $staff_member->name) {
                                $staff_member = new Staff($staff_member->staff_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_staff/staff', ['entry_id' => $staff_member->staff_id, 'func' => 'edit']) .'">'. $staff_member->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_news')->isAvailable()) {
        $news = \D2U_News\News::getTranslationHelperObjects($target_clang_id, $filter_type);
        $news_categories = \D2U_News\Category::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_news') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-open-category"></i></small> <?= rex_i18n::msg('d2u_helper_categories') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($news_categories) > 0) {
                        echo '<ul>';
                        foreach ($news_categories as $current_news_category) {
                            if ('' !== $current_news_category->name) {
                                $current_news_category = new \D2U_News\Category($current_news_category->category_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_news/news', ['entry_id' => $current_news_category->category_id, 'func' => 'edit']) .'">'. $current_news_category->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-newspaper-o"></i></small> <?= rex_i18n::msg('d2u_news_news_title') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($news) > 0) {
                        echo '<ul>';
                        foreach ($news as $current_news) {
                            if ('' !== $current_news->name) {
                                $current_news = new \D2U_News\News($current_news->news_id, $source_clang_id);
                                if ('' !== $current_news->name) {
                                    foreach (rex_clang::getAllIds() as $clang_id) {
                                        $current_news = new \D2U_News\News($current_news->news_id, $clang_id);
                                        if ('' !== $current_news->name) {
                                            break;
                                        }
                                    }
                                }
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_news/news', ['entry_id' => $current_news->news_id, 'func' => 'edit']) .'">'. $current_news->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                if (rex_plugin::get('d2u_news', 'news_types')->isAvailable()) {
                    $news_types = \D2U_News\Type::getTranslationHelperObjects($target_clang_id, $filter_type);
            ?>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-file-text-o"></i></small> <?= rex_i18n::msg('d2u_news_types') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($news_types) > 0) {
                        echo '<ul>';
                        foreach ($news_types as $news_type) {
                            if ('' !== $news_type->name) {
                                $news_type = new \D2U_News\Type($news_type->type_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_news/news_types', ['entry_id' => $news_type->type_id, 'func' => 'edit']) .'">'. $news_type->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<?php
                }
            ?>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_references')->isAvailable()) {
        $references = Reference::getTranslationHelperObjects($target_clang_id, $filter_type);
        $tags = Tag::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_references') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-thumbs-o-up"></i></small> <?= rex_i18n::msg('d2u_references_references') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($references) > 0) {
                        echo '<ul>';
                        foreach ($references as $reference) {
                            if ('' !== $reference->name) {
                                $reference = new Reference($reference->reference_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_references/reference', ['entry_id' => $reference->reference_id, 'func' => 'edit']) .'">'. $reference->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><small><i class="rex-icon fa-tags"></i></small> <?= rex_i18n::msg('d2u_references_tags') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($tags) > 0) {
                        echo '<ul>';
                        foreach ($tags as $tag) {
                            if ('' !== $tag->name) {
                                $tag = new Tag($tag->tag_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_references/tag', ['entry_id' => $tag->tag_id, 'func' => 'edit']) .'">'. $tag->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

    if (rex_addon::get('d2u_videos')->isAvailable()) {
        $videos = Video::getTranslationHelperObjects($target_clang_id, $filter_type);
?>
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_videos') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon fa-video-camera"></i></small> <?= rex_i18n::msg('d2u_videos') ?></legend>
				<div class="panel-body-wrapper slide">
				<?php
                    if (count($videos) > 0) {
                        echo '<ul>';
                        foreach ($videos as $video) {
                            if ('' !== $video->name) {
                                $video = new Video($video->video_id, $source_clang_id);
                            }
                            echo '<li><a href="'. rex_url::backendPage('d2u_videos/videos', ['entry_id' => $video->video_id, 'func' => 'edit']) .'">'. $video->name .'</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) && 'update' === rex_session('d2u_helper_translation')['filter'] ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
                    }
                ?>
				</div>
			</fieldset>
		</div>
	</div>
<?php
    }

    /**
     * Extension point for translation list.
     * @param array $translation_list List of addons and their pages with translation status
     * @param array $params Parameters
     * @param int $params['source_clang_id'] Source clang id
     * @param int $params['target_clang_id'] Target clang id
     * @param string $params['filter_type'] Filter type
     * @return array $translation_list List of addons and their pages with translation status. Example:
     * [
     *      [
     *          'addon_name' => 'addon name',
     *          'pages' => [
     *              [
     *                  'title' => 'addon page title',
     *                  'icon' => 'FontAwesome page icon',
     *                  'html' => 'ul html code containing links to the backend pages with the translations'
     *              ]
     *      ]
     * ]
     */
    $translation_list = rex_extension::registerPoint(new rex_extension_point('D2U_HELPER_TRANSLATION_LIST', [], ['source_clang_id' => $source_clang_id, 'target_clang_id' => $target_clang_id, 'filter_type' => $filter_type]));
/*
    // Translation list, TODO: uncomment starting from Version 2.0
    if (count($translation_list) > 0) {
        foreach ($translation_list as $translation_list_item) {
            if (count($translation_list_item['pages']) > 0) {
                echo '<div class="panel panel-edit">';
                echo '<header class="panel-heading"><div class="panel-title">'. $translation_list_item['addon_name'] .'</div></header>';
                echo '<div class="panel-body">';
                foreach ($translation_list_item['pages'] as $page) {
                    echo '<fieldset>';
                    echo '<legend><small><i class="rex-icon '. $page['icon'] .'"></i></small> '.$page['title'] .'</legend>';
                    echo '<div class="panel-body-wrapper slide">'. $page['html'] . '</div>';
                    echo '</fieldset>';
                }
                echo '</div>';
                echo '</div>';
            }
        }
    }
    else {
        echo 'update' === $filter_type ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
    }
*/

    echo d2u_addon_backend_helper::getCSS();
    echo d2u_addon_backend_helper::getJS();
    echo d2u_addon_backend_helper::getJSOpenAll();
}
