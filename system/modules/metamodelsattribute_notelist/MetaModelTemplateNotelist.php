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

use MetaModels\Render\Template;

/**
 * MetaModels subpackage
 *
 * @package     MetaModels
 * @subpackage  AttributeNotelist
 * @author      Tim Gatzky <info@tim-gatzky.de>
 */
 
class MetaModelTemplateNotelist extends Template
{
	/**
	 * Add template vars
	 * called from metamodel parseTemplate HOOK
	 */
	public function parseTemplateCallback($objTemplate)
	{
		if(get_class($objTemplate->attribute) != 'MetaModelAttributeNotelist')
		{
			return $objTemplate;
		}
		
		$blnReload = $GLOBALS['metamodels_notelist']['autoReloadPage'];
		
		// declare libraries
		$objSession = Session::getInstance();
		$objInput = Input::getInstance();
		
		#$objSession->remove('metamodels_notelist');
		#return ;
		
		// attribute object
		$objAttr = $objTemplate->attribute;
		
		// record
		$arrRow = $objTemplate->row;
		
		$objTemplate->includeNotelist = true;
		if(!$arrRow[$objAttr->get('colname')])
		{
			$objTemplate->includeNotelist = false;
			return $objTemplate;
		}
		
		// MetaModelNotelist object, provides various helper functions
		$objMetaModelNotelist = MetaModelNotelist::getInstance();
		
		$strFormID = 'mm_notelist_'.$objAttr->get('pid').'_'.$arrRow['id'];
		
		$objTemplate->action = $this->replaceInsertTags('{{env::request}}');
		$objTemplate->formID = $strFormID;
		$objTemplate->itemID = $arrRow['id'];
		$objTemplate->metamodelID = $objAttr->get('pid');
		
		//-- submits
		$objTemplate->submit = $GLOBALS['TL_LANG']['metamodels_notelist']['submitLabel'];
		$objTemplate->submitName = $strFormID.'_add'; #'ADD_NOTELIST_ITEM';
		$objTemplate->update = $GLOBALS['TL_LANG']['metamodels_notelist']['updateLabel'];
		$objTemplate->updateName = $strFormID.'_update'; #'UPDATE_NOTELIST_ITEM';
		$objTemplate->remove = $GLOBALS['TL_LANG']['metamodels_notelist']['removeLabel'];
		$objTemplate->removeName = $strFormID.'_remove'; #'REMOVE_NOTELIST_ITEM';
		
		// get item from notelist and set amount value
		$arrItem = $objMetaModelNotelist->getItem($objAttr->get('pid'),$arrRow['id']);
		$amount = ($arrItem['amount'] ? $arrItem['amount'] : $GLOBALS['metamodels_notelist']['default_amount']);
		
		// create amount widget
		$arrData=array('eval'=>array('rgxp' => 'digit', 'mandatory'=>true));
		$objWidgetAmount = new FormTextField($this->prepareForWidget($arrData, $strFormID.'_amount', $amount, $strFormID.'_amount'));	
		
		$objTemplate->amountInput = $objWidgetAmount->generate();
		$objTemplate->amountLabel = sprintf('<label for="ctrl_%s">%s</label>',$objWidgetAmount->id,$GLOBALS['TL_LANG']['metamodels_notelist']['amountLabel']);
		
		
		//-- variants
		$arrVariants = array();
		if($objTemplate->attribute->get('notelist_variants'))
		{
			$arrTemplateVariants = array();
			
			// create metamodelnotelistvariants instance
			$objMetaModelNotelistVariants = MetaModelNotelistVariants::getInstance();
			
			$objWidget = null;
			foreach($objTemplate->attribute->get('notelist_variants') as $intVariantAttrId)
			{
				// metamodel attribute instance
				$objVariantAttr = $objTemplate->attribute->getMetaModel()->getAttributeById($intVariantAttrId);
				if(!$objVariantAttr)
				{
					continue;
				}
				
				$strName = $objVariantAttr->get('colname').'_'.$objAttr->get('pid').'_'.$arrRow['id'];				
				
				// generate widget
				$arrFieldDef = array
				(
					'id'	=> $objAttr->get('pid').'_'.$arrRow['id'].'_'.$objVariantAttr->get('id'),
					'name'	=> $strName,
					'value'	=> $arrItem['variants'][$strName]['value'],
				);
				$arrFieldDef = array_merge($objVariantAttr->getFieldDefinition(),$arrFieldDef);
				
				$objWidget = $objMetaModelNotelistVariants->loadFormField($arrFieldDef);
				
				// collect variants
				$arrTemplateVariants[$strName] = array
				(
					'id'	=> $objVariantAttr->get('id'),
					'html'	=> $objWidget->generate(),
					'raw'	=> $objWidget,
					'attribute' => $objVariantAttr,
				);
				
				// check if a variant field is submitted and store in array
				if($objInput->post('FORM_SUBMIT') == $strFormID && $objInput->post($objWidget->name))
				{
					$arrVariants[$strName] = array
					(
						'id'		=> $objVariantAttr->get('id'),
						'colname' 	=> $objVariantAttr->get('colname'),
						'value'		=> $objInput->post($objWidget->name),
					);
				}
			}
			
			// add variants fields to template
			$objTemplate->variants = $arrTemplateVariants;
		}
		
		//-- form submits
		if($objInput->post('FORM_SUBMIT') == $strFormID)
		{
			$intAmount = $objInput->post($strFormID.'_amount');
			$intItem = $objInput->post('ITEM_ID');
			$intMetaModel = $objInput->post('METAMODEL_ID');
			
			// insert or update an item
			if( strlen($objInput->post($objTemplate->submitName)) > 0 || strlen($objInput->post($objTemplate->updateName)) > 0 )
			{
				// validate amount
				$objWidgetAmount->validate();
				if($objWidgetAmount->hasErrors())
				{
					$objTemplate->statusMessage = $objWidgetAmount->getErrorAsString(0);
				}
				else
				{
					// toggle status message
					if(strlen($objInput->post($objTemplate->updateName)) > 0)	
					{
						$objTemplate->statusMessage = $GLOBALS['TL_LANG']['metamodels_notelist']['itemUpdated'];
						
						// reload if variants where updated
						#if(count($arrVariants) > 0)
						#{
						#	$blnReload = true;
						#}
					}
					else 
					{
						$objTemplate->statusMessage = $GLOBALS['TL_LANG']['metamodels_notelist']['itemAdded'];
					}
				
					// set the notelist
					$objMetaModelNotelist->setItem($intMetaModel,$intItem,$intAmount,$arrVariants,$blnReload);
				}
			}
			// remove an item and reload the page immediately
			else if(strlen($objTemplate->removeName) > 0)
			{
				$objMetaModelNotelist->removeItem($intMetaModel,$intItem);
			}
			else {}
				
			
		}
				
		// mark item as being added
		if($arrItem['amount'])
		{
			$objTemplate->added = true;
		}
		
		
	}
}