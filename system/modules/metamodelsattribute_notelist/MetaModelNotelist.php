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
 * Imports
 */
use \MetaModelNotelistHooks as Hooks;

/**
 * Class MetaModelNotelist
 *
 * Provide various functions for the metamodels_notelist
 * @package		metamodels_notelist
 * @author		Tim Gatzky <info@tim-gatzky.de>
 */
class MetaModelNotelist extends \System
{
	/**
	 * Session node
	 * @var string
	 */
	protected $strSession	= 'metamodelnotelist';
	
	/**
	 * @var MetaModelNotelist
	 */
	protected static $objInstance = null;

	/**
	 * Get the static instance.
	 *
	 * @static
	 * @return MetaModelNotelist
	 */
	public static function getInstance()
	{
		if (self::$objInstance == null) {
			self::$objInstance = new MetaModelNotelist();
		}
		return self::$objInstance;
	}
		
	
	/**
	 * Insert/update an item in the notelist
	 * @param integer
	 * @param integer
	 * @param integer
	 * @param array
	 * @param boolean
	 */
	public function setItem($intMetaModel,$intItem,$intAmount=0,$arrVariants=array(),$blnReload=true)
	{
		// get Session
		$objSession = \Session::getInstance();
		$arrSession = $objSession->get($this->strSession);
		
		$time = time();
		
		$arrSession[$intMetaModel][$intItem] = array
		(
			'tstamp'	=> $time,
			'id'		=> $intItem,
			'metamodel'	=> $intMetaModel,
			'amount'	=> ($intAmount < 0 || !$intAmount ? 0 : $intAmount),
			'variants'	=> $arrVariants,
		);
		
		// HOOK allow other extensions to manipulate the session
		$arrSession =  Hooks::getInstance()->callSetItemHook($arrSession,$intMetaModel,$intItem,$intAmount,$arrVariants);
		
		// set Session
		$objSession->set($this->strSession,$arrSession);
		
		if($blnReload)
		{
			// reload the page to see changes	
			$this->reload();
		}
	}
	
	
	/**
	 * Get an item from the notelist and return as array
	 * @param integer
	 * @param integer
	 * @return array
	 */
	public function getItem($intMetaModel,$intItem)
	{
		// get Session
		$objSession = Session::getInstance();
		$arrSession = $objSession->get($this->strSession);
		
		return $arrSession[$intMetaModel][$intItem];
	}
	
	
	/**
	 * Remove an item from notelist
	 * @param integer
	 * @param integer
	 * @param boolean
	 */
	public function removeItem($intMetaModel,$intItem,$blnReload=true)
	{
		// get Session
		$objSession = \Session::getInstance();
		$arrSession = $objSession->get($this->strSession);
		
		unset($arrSession[$intMetaModel][$intItem]);
		
		// HOOK tell other extensions an item has been removed
		Hooks::getInstance()->callRemoveItemHook($arrSession,$intMetaModel,$intItem);
		
		// set Session
		$objSession->set($this->strSession,$arrSession);
		
		if($blnReload)
		{
			// reload the page to see changes	
			$this->reload();
		}
	}
	
		
	/**
	 * Get all items from current notelist and return as array
	 * @param integer
	 * @return array
	 */
	public function getNotelist($intNotelist=0)
	{
		// Session
		$objSession = Session::getInstance();
		$arrSession = $objSession->get($this->strSession);
		
		if(!is_array($arrSession) || count($arrSession) < 1 )
		{
			return array();
		}
		
		// set to a certain notelist node
		if($intNotelist > 0 && count($arrSession[$intNotelist]) > 0)
		{
			$arrSession = $arrSession[$intNotelist];
		}
		
		// clean out notelist session
		$arrReturn = array();
		foreach($arrSession as $id => $entries)
		{
			if(count($entries) < 1)
			{
				continue;
			}
			$arrReturn[$id] = $entries;
		}
		
		return $arrReturn;
	}
	
	
	/**
	 * Returns true if an element is already in the notelist
	 * @param integer
	 * @param integer
	 * @return boolean
	 */
	public function isInNotelist($intMetaModel, $intItem)
	{
		$arrNotelist = $this->getNotelist();
		
		if(isset($arrNotelist[$intMetaModel][$intItem]) && count($arrNotelist[$intMetaModel][$intItem]) > 0)
		{
			return true;
		}
		
		#if(count($intMetaModel) < 1 || count($arrNotelist[$intMetaModel]) < 1)
		#{
		#	return false;
		#}
		#
		#foreach($arrNotelist[$intMetaModel] as $entry)
		#{
		#	if($entry['id'] == $intItem)
		#	{
		#		return true;
		#	}
		#}
		
		return false;
	}
	
	
	/**
	 * Fetch the data from a metamodel and return a prepared array
	 * @param integer
	 * @param integer
	 * @param array		/ visible fields
	 * @return array
	 */
	public function prepareDataForWidget($intMetaModel,$intItem,$arrVisibles=array())
	{
		// get metamodel
		$objMetaModel = \MetaModelFactory::byId($intMetaModel);
		
		if(!$objMetaModel)
		{
			return array();
		}
		
		// get column names for visible attributes
		$arrTmp = array();
		if(count($arrVisibles) > 0)
		{
			foreach($arrVisibles as $id)
			{
				$objAttr = $objMetaModel->getAttributeById($id);
				$arrTmp[] = $objAttr->getColName(); #$attr->get('colname');
			}	
		}
		$arrVisibles = $arrTmp;
		unset($arrTmp);
		
		// #issue: $arrOnlyAttr not working the way it meant to be -> its not filtering attributes
		// get metamodelitem object
		$objMetaModelItem = $objMetaModel->findById($intItem,$arrVisibles);

		if(!$objMetaModelItem)
		{
			return array();
		}
		
		// parse only visible fields and store in array
		$arrReturn = array();
		if(count($arrVisibles) > 0)
		{
			foreach($arrVisibles as $attr)
			{
				$objAttr = $objMetaModelItem->getAttribute($attr);
				
				$arrReturn[$attr] = array
				(
					'label' => $objAttr->getName(),
					'id'	=> $objAttr->get('id'),
					'pid'	=> $objAttr->get('pid'),
					'type'	=> $objAttr->get('type'),
					'value' => $objMetaModelItem->parseAttribute($attr)
				);
			}
		}
		
		return $arrReturn;
	}
	
	
	/**
	 * Replace Inserttags
	 * @param string
	 * @return string or boolean
	 */
	public function replaceTags($strTag)
	{
		$strValue = '';
		$element = explode('::', $strTag);

		switch($element[0])
		{
			case 'form':
				$objInput = Input::getInstance();
				
				$strValue = $objInput->post($element[1]);
				
				// fallback
				if(strlen($strValue) < 1)
				{
					$strValue = $_POST[$element[1]];
				}
				
				return $strValue;
				
			break;
			
			default: return false; break;
		}


		return false;
	}

	
	

}