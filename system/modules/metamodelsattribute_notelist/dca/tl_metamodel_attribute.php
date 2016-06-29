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
 * Table tl_metamodel_attribute 
 */
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['notelist extends _simpleattribute_'] = array
(
	'+display' => array('notelist_variants')
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['notelist_enableVariants'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['notelist_enableVariants'],
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'eval'                  => array('tl_class'=>'','submitOnChange'=>true),
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['notelist_variants'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['notelist_variants'],
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'options_callback'      => array('TableMetaModelsAttributeNotelist', 'getVariantAttributes'),
	'eval'                  => array('tl_class'=>'','multiple'=>true),
	'sql'					=> "blob NULL"
);