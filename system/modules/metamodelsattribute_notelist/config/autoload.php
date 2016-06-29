<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Metamodelsattribute_notelist
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'FormMetaModelNotelist'               => 'system/modules/metamodelsattribute_notelist/FormMetaModelNotelist.php',
	'MetaModelAttributeNotelist'          => 'system/modules/metamodelsattribute_notelist/MetaModelAttributeNotelist.php',
	'MetaModelFilterSettingNotelistItems' => 'system/modules/metamodelsattribute_notelist/MetaModelFilterSettingNotelistItems.php',
	'MetaModelNotelist'                   => 'system/modules/metamodelsattribute_notelist/MetaModelNotelist.php',
	'MetaModelNotelistHooks'              => 'system/modules/metamodelsattribute_notelist/MetaModelNotelistHooks.php',
	'MetaModelNotelistVariants'           => 'system/modules/metamodelsattribute_notelist/MetaModelNotelistVariants.php',
	'MetaModelTemplateNotelist'           => 'system/modules/metamodelsattribute_notelist/MetaModelTemplateNotelist.php',
	'TableFormFieldMetaModelsNotelist'    => 'system/modules/metamodelsattribute_notelist/TableFormFieldMetaModelsNotelist.php',
	'TableMetaModelsAttributeNotelist'    => 'system/modules/metamodelsattribute_notelist/TableMetaModelsAttributeNotelist.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'form_mm_notelist'    => 'system/modules/metamodelsattribute_notelist/templates',
	'mail_mm_notelist'    => 'system/modules/metamodelsattribute_notelist/templates',
	'mm_attr_notelist_be' => 'system/modules/metamodelsattribute_notelist/templates',
	'mm_attr_notelist_fe' => 'system/modules/metamodelsattribute_notelist/templates',
	'mm_attr_notelist'    => 'system/modules/metamodelsattribute_notelist/templates',
));
