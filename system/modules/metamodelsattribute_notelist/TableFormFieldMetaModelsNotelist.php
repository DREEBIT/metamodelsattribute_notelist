<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2013
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		metamodels_notelist
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Imports
 */
use \MetaModels\Factory as MetaModelFactory;

/**
 * Class TableFormMetaModelsNotelist
 *
 * @author		Tim Gatzky <info@tim-gatzky.de>
 */
class TableFormFieldMetaModelsNotelist extends \Backend
{
	/**
	 * Return all metamodels as array
	 * @param object
	 * @return array
	 */
	public function getMetaModels(DataContainer $dc)
	{
		$objDatabase = Database::getInstance();
		
		$objResult = $objDatabase->execute("SELECT * FROM tl_metamodel");
		
		if($objResult->numRows < 1)
		{
			return array();
		}
		
		$arrReturn = array();
		while($objResult->next())
		{
			$arrReturn[$objResult->id] = $objResult->name;
		}
		
		return $arrReturn;
	}
	
	/**
	 * Get all attribute fields of a meta model
	 */
	public function getAttributes(DataContainer $dc)
	{
		if($dc->activeRecord->metamodels_notelist_metamodel < 1)
		{
			return array();
		}

		// metamodel
		$objMetaModel = MetaModelFactory::byId($dc->activeRecord->metamodels_notelist_metamodel);
		
		// get available attributes
		$arrAttributes = $objMetaModel->getAttributes();
		
		if(count($arrAttributes) < 1)
		{
			return array();
		}
		
		$arrReturn = array();
		foreach($arrAttributes as $objAttr)
		{
			$arrReturn[$objAttr->get('id')] = $objAttr->getName();
		}
		
		return $arrReturn;
	}
	
	/**
	 * Return all form templates as array
	 * @param object
	 * @return array
	 */
	public function getFormTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}
		
		return $this->getTemplateGroup('form_mm_notelist', $intPid);
	}
	
	/**
	 * Return all mail templates as array
	 * @param object
	 * @return array
	 */
	public function getMailTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mail_mm_notelist', $intPid);
	}

}
