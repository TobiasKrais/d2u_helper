<?php
// Update modules
$d2u_module_manager = new D2UModuleManager(D2UModuleManager::getD2UHelperModules());
$d2u_module_manager->autoupdate();

// Update templates
$d2u_template_manager = new D2UTemplateManager(D2UTemplateManager::getD2UHelperTemplates());
$d2u_template_manager->autoupdate();