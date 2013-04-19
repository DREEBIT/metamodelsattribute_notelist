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
 * Class MetaModelNotelist
 *
 * Provide various functions for the metamodels_notelist
 * @package		metamodels_notelist
 * @author		Tim Gatzky <info@tim-gatzky.de>
 */
 
class MetaModelNotelist extends Frontend
{
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
	 * Insert items in notelist from submitted notelist forms
	 * @param object
	 * @param object
	 * @param object
	 * called from generatePage HOOK
	 */
	#public function formSubmitListener(Database_Result $objPage, Database_Result $objLayout, PageRegular $objPageRegular)
	#{
	#	// declare libraries
	#	$objSession = Session::getInstance();
	#	$objInput = Input::getInstance();
	#	
	#	if(strpos($objInput->post('FORM_SUBMIT'), 'mm_notelist') !== 0)
	#	{
	#		return;
	#	}
	#	
	#	$strFormID = $objInput->post('FORM_SUBMIT');
	#	$intAmount = $objInput->post($strFormID.'_amount');
	#	$intItem = $objInput->post('ITEM_ID');
	#	$intMetaModel = $objInput->post('METAMODEL_ID');
	#	
	#	// insert or update an item
	#	if( strlen($objInput->post('ADD_NOTELIST_ITEM')) > 0 || strlen($objInput->post('UPDATE_NOTELIST_ITEM')) > 0 )
	#	{
	#		// validate amount
	#		$arrData=array('eval'=>array('rgxp' => 'digit', 'mandatory'=>true));
	#		$objWidgetAmount=new FormTextField($this->prepareForWidget($arrData, $strFormID.'_amount', $intAmount, $strFormID.'_amount'));
	#		$objWidgetAmount->validate();
	#		if($objWidgetAmount->hasErrors())
	#		{
	#			return;
	#		}
	#	
	#		$this->setItem($intMetaModel,$intItem,$intAmount);
	#	}
	#	// remove an item
	#	else if(strlen($objInput->post('REMOVE_NOTELIST_ITEM')) > 0)
	#	{
	#		// set item
	#		$this->removeItem($intMetaModel,$intItem);
	#	}
	#	else {}
	#}
	
	
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
		$objSession = Session::getInstance();
		$arrSession = $objSession->get('metamodels_notelist');
		
		
		$arrSession[$intMetaModel][$intItem] = array
		(
			'tstamp'	=> time(),
			'id'		=> $intItem,
			'metamodel'	=> $intMetaModel,
			'amount'	=> ($intAmount < 0 || !$intAmount ? 0 : $intAmount),
			'variants'	=> $arrVariants,
		);
		
		// set Session
		$objSession->set('metamodels_notelist',$arrSession);
		
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
	 * @param integer
	 * @return array
	 */
	public function getItem($intMetaModel,$intItem)
	{
		// get Session
		$objSession = Session::getInstance();
		$arrSession = $objSession->get('metamodels_notelist');
		
		return $arrSession[$intMetaModel][$intItem];
	}
	
	
	/**
	 * Remove an item from notelist
	 * @param integer
	 * @param integer
	 */
	public function removeItem($intMetaModel,$intItem,$blnReload=true)
	{
		// get Session
		$objSession = Session::getInstance();
		$arrSession = $objSession->get('metamodels_notelist');
		
		unset($arrSession[$intMetaModel][$intItem]);
		
		// set Session
		$objSession->set('metamodels_notelist',$arrSession);
		
		if($blnReload)
		{
			// reload the page to see changes	
			$this->reload();
		}
	}
	
		
	/**
	 * Get all items from current notelist and return as array
	 * @return array
	 */
	public function getNotelist($intNotelist=0)
	{
		// Session
		$objSession = Session::getInstance();
		$arrSession = $objSession->get('metamodels_notelist');
		
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
		$objMetaModel = MetaModelFactory::byId($intMetaModel);
		
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