<?php
// Update modules
if(class_exists(D2UModuleManager)) {
	$d2u_module_manager = new D2UModuleManager(D2UModuleManager::getD2UHelperModules());
	$d2u_module_manager->autoupdate();
}

// Update templates
if(class_exists(D2UTemplateManager)) {
	$d2u_template_manager = new D2UTemplateManager(D2UTemplateManager::getD2UHelperTemplates());
	$d2u_template_manager->autoupdate();
}

// Update standard settings
if (!$this->hasConfig(subhead_include_articlename)) {
	$this->setConfig('subhead_include_articlename', '"true"');
}