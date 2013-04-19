<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package     MetaModels
 * @subpackage  AttributeNotelist
 * @author      Tim Gatzky <info@tim-gatzky.de>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// modules
	'MetaModelAttributeNotelist'			=> 'system/modules/metamodels_notelist/MetaModelAttributeNotelist.php',
	'TableMetaModelAttributeNotelist'		=> 'system/modules/metamodels_notelist/TableMetaModelAttributeNotelist.php',
	'MetaModelTemplateNotelist'				=> 'system/modules/metamodels_notelist/MetaModelTemplateNotelist.php',
	'MetaModelNotelist'						=> 'system/modules/metamodels_notelist/MetaModelNotelist.php',
	'FormMetaModelNotelist'					=> 'system/modules/metamodels_notelist/FormMetaModelNotelist.php',
	'TableFormFieldMetaModelsNotelist'		=> 'system/modules/metamodels_notelist/TableFormFieldMetaModelsNotelist.php',
	'MetaModelNotelistVariants'				=> 'system/modules/metamodels_notelist/MetaModelNotelistVariants.php',

));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mm_attr_notelist_be'					=> 'system/modules/metamodels_notelist/templates',
	'mm_attr_notelist_fe'					=> 'system/modules/metamodels_notelist/templates',
	'form_mm_notelist'						=> 'system/modules/metamodels_notelist/templates',
	'mail_mm_notelist'						=> 'system/modules/metamodels_notelist/templates',
));
