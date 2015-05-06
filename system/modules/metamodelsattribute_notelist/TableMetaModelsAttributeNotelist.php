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
 * Supplementary class for handling DCA information for notelist attributes.
 *
 * @package     MetaModels
 * @subpackage  AttributeNotelist
 * @author      Tim Gatzky <info@tim-gatzky.de>
 */

#use DcGeneral\DataContainerInterface;

class TableMetaModelsAttributeNotelist extends \Backend
{
	/**
	 * @var TableMetaModelsAttributeNotelist
	 */
	protected static $objInstance = null;

	/**
	 * @var
	 * possible variants attributes
	 */
	protected $arrVariantAttributes = array('select','tags');


	/**
	 * Get the static instance.
	 *
	 * @static
	 * @return MetaPalettes
	 */
	public static function getInstance()
	{
		if (self::$objInstance == null) {
			self::$objInstance = new TableMetaModelsAttributeNotelist();
		}
		return self::$objInstance;
	}


	/**
	 * Get attributes for notelist variants of the current metamodel and return as array
	 * @return array
	 */
	public function getVariantAttributes($objDC)
	{
		$objDatabase = \Database::getInstance();
		
		$arrMM = explode('::',\Input::get('pid'));
		$intPid = $arrMM[1];
		
		// fetch possible variants attributes (selects, tags)
		$objAttributes = $objDatabase->prepare("SELECT * FROM tl_metamodel_attribute WHERE pid=? AND ".$objDatabase->findInSet('type',$this->arrVariantAttributes))
						->execute($intPid);
		
		if($objAttributes->numRows < 1)
		{
			return array();
		}
		
		$arrReturn = array();
		while($objAttributes->next())
		{
			$arrName = deserialize($objAttributes->name);
			if(is_array($arrName))
			{
				$strName = ($arrName[$GLOBALS['TL_LANGUAGE']] ? $arrName[$GLOBALS['TL_LANGUAGE']] : $objAttributes->type . $objAttributes->id);
			}
			else
			{
				$strName = $arrName . ' '.$objAttributes->type . ' (id='.$objAttributes->id.')';
			}
			$arrReturn[$objAttributes->id] = $strName;
		}
		
		return $arrReturn;
	}
	
	
	/**
	 * Return all notelist templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getNotelistTemplates(DataContainerInterface $objDC)
	{
		$objThis = $objDC->getEnvironment()->getCurrentModel();
		
		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('mm_attr_notelist', $objThis->getProperty('pid'));
	}
}