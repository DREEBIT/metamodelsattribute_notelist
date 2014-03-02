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
 * Filter setting for MetaModel to parse only items from the notelist to the lister module
 *
 * @package     MetaModels
 * @subpackage  AttributeNotelist
 * @author      Tim Gatzky <info@tim-gatzky.de>
 */

class MetaModelFilterSettingNotelistItems extends MetaModelFilterSetting
{
	/* (non-PHPdoc)
	 * @see IMetaModelFilterSetting::prepareRules()
	 */
	public function prepareRules(IMetaModelFilter $objFilter, $arrFilterUrl)
	{
		$objMetaModel = $this->getMetaModel();
		$objMetaModelNotelist = MetaModelNotelist::getInstance();
		
		$arrNotelist = $objMetaModelNotelist->getNotelist($objMetaModel->get('id'));
		
		$arrIds = array();
		foreach($arrNotelist as $entry)
		{
			$arrIds[] = $entry['id'];
		}
		
		// set filter
		$objFilter->addFilterRule(new MetaModelFilterRuleStaticIdList($arrIds));
	}
}