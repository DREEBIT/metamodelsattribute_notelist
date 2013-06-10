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
 * MetaModels Attributes
 */
$GLOBALS['METAMODELS']['attributes']['notelist'] = array
(
	'class' => 'MetaModelAttributeNotelist',
	'image' => 'system/modules/metamodels_notelist/html/letter-3-16.png'
);

/**
 * Frontend filter
 */
$GLOBALS['METAMODELS']['filters']['notelistitems'] = array
(
	'class' => 'MetaModelFilterSettingNotelistItems',
	'image' => 'system/modules/metamodelsattribute_notelist/html/letter-3-16.png',
	#'info_callback' => array('TableMetaModelFilterSetting','infoCallback'),
);

/**
 * Form fields
 */
array_insert($GLOBALS['TL_FFL'],14,array
(
	'metamodels_notelist'	=> 'FormMetaModelNotelist'
));


/**
 * Hooks
 */
$GLOBALS['METAMODEL_HOOKS']['parseTemplate'][] 		= array('MetaModelTemplateNotelist','parseTemplateCallback');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] 		= array('MetaModelNotelist', 'replaceTags'); 


/**
 * Globals
 */
$GLOBALS['metamodels_notelist']['default_amount']	= 1;
$GLOBALS['metamodels_notelist']['autoReloadPage']	= true; // reload the page when amount is being updated or an item is placed on the notelist
