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
 * MetaModels subpackage for the notelist attribute
 *
 * @package     MetaModels
 * @subpackage  AttributeNotelist
 * @author      Tim Gatzky <info@tim-gatzky.de>
 */

use MetaModels\Attribute\BaseSimple;
use MetaModels\Render\Template;

class MetaModelAttributeNotelist extends BaseSimple
{
	/**
	 * {@inheritdoc}
	 */
	public function getSQLDataType()
	{
		return "char(1) NOT NULL default ''";
	}
	
	public function getAttributeSettingNames()
	{
		return array_merge(parent::getAttributeSettingNames(), array
		(
			'notelist_variants',
			'includeBlankOption',
		));
	}

	public function getFieldDefinition($arrOverrides = array())
	{
		\System::loadLanguageFile('default');
		
		$arrFieldDef = parent::getFieldDefinition($arrOverrides);
		$arrFieldDef['inputType'] = 'radio';
		$arrFieldDef['default'] = 1;
		$arrFieldDef['options'] = array(1=>$GLOBALS['TL_LANG']['metamodels_notelist']['insertNotelistOption'] ?: 'Insert notelist');
		$arrFieldDef['eval']['includeBlankOption'] = true;
		return $arrFieldDef;
	} 
}
