<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2013
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		metamodel_notelist
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class FormMetaModelNotelist
 *
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		metamodel_notelist
 */
class FormMetaModelNotelist extends Widget
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';
	protected $strTemplateForm = 'form_mm_notelist';
	protected $strTemplateMail = 'mail_mm_notelist';
	
	/**
	 * @var string
	 */
	protected $strStatusMessage = '';
	
	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;
	
	/**
	 * Initialize the object
	 * @param array
	 */
	public function __construct($arrAttributes=false)
	{
		parent::__construct($arrAttributes);
	}
	
	/**
	 * Getters
	 */
	public function __get($strKey)
	{
		switch($strKey)
		{
			case 'value':
				return $this->generateOutput(true);
			break;
			default:
				return parent::__get($strKey);
			break;
		}	
	}
	
	/**
	 * Generate the field and return html string
	 * @param boolean
	 * @return string
	 */
	protected function generateOutput($blnFormMail=false)
	{
		// return if no metamodel is selected
		if($this->metamodels_notelist_metamodel < 1)
		{
			return '';
		}
		
		$objInput = Input::getInstance();
		
		// MetaModelNotelist object, provides various helper functions
		$objMetaModelNotelist = MetaModelNotelist::getInstance();

		//-- toggle template
		$strTemplate = '';
		if(!$blnFormMail)
		{	
			$strTemplate = $this->strTemplateForm;
			if($this->strTemplateForm != $this->metamodels_notelist_formTpl)
			{
				$strTemplate = $this->metamodels_notelist_formTpl;
			}
		}
		else
		{
			$strTemplate = $this->strTemplateMail;
			if($this->strTemplateMail != $this->metamodels_notelist_mailTpl)
			{
				$strTemplate = $this->metamodels_notelist_mailTpl;
			}
		}
		
		//-- create template object and add template vars
		$objTemplate = new FrontendTemplate($strTemplate);
		$objTemplate->empty = $GLOBALS['TL_LANG']['metamodels_notelist']['emptyInfo'];
		
		// get notelist for this metamodel notelist field
		$arrNotelist = $objMetaModelNotelist->getNotelist($this->metamodels_notelist_metamodel);
		
		if(count($arrNotelist) < 1)
		{
			return $objTemplate->parse();
		}
		
		// visible fields
		$arrVisibles = deserialize($this->metamodels_notelist_visibles);
		
		// prepare template for regular FE output
		$arrTmp = array();
		if(!$blnFormMail)
		{
			//-- submits
			$objTemplate->submit = $GLOBALS['TL_LANG']['metamodels_notelist']['submitLabel'];
			$objTemplate->remove = $GLOBALS['TL_LANG']['metamodels_notelist']['removeLabel'];
			$objTemplate->update = $GLOBALS['TL_LANG']['metamodels_notelist']['updateLabel'];
			
			$i = 0;
			foreach($arrNotelist as $entry)
			{
				// add classes
				$arrClass = array('item_'.$entry['id']);
				($i == 0 ? $arrClass[] = 'first' : '');
				($i >= count($arrNotelist)-1 ? $arrClass[] = 'last' : '');
				($i%2 == 0 ? $arrClass[] = 'even' : $arrClass[] = 'odd');
				
				$entry['class'] = implode(' ', $arrClass);
				
				// id base for inputs
				$strId = 'mm_notelist_'.$entry['metamodel'].'_'.$entry['id'];
				
				//-- generate amount input and label and add to entry
				$objFormFieldAmount = new FormTextField();
				$objFormFieldAmount->id = $strId.'_amount';
				$objFormFieldAmount->name = $strId.'_amount';
				$objFormFieldAmount->value = $entry['amount'];
				$entry['label_amount'] = sprintf('<label for="ctrl_%s">%s</label>',$objFormFieldAmount->id,$GLOBALS['TL_LANG']['metamodels_notelist']['amountLabel']);
				$entry['input_amount'] = $objFormFieldAmount->generate();
				
				//-- generate update submit
				$objFormSubmitUpdate = new FormSubmit();
				$objFormSubmitUpdate->id = $strId.'_update';
				$objFormSubmitUpdate->name = $strId.'_update';
				$objFormSubmitUpdate->slabel = $GLOBALS['TL_LANG']['metamodels_notelist']['updateLabel'];
				$entry['input_update'] = $objFormSubmitUpdate->generate();
				
				//-- generate remove submit
				$objFormSubmitRemove = new FormSubmit();
				$objFormSubmitRemove->id = $strId.'_remove';
				$objFormSubmitRemove->name = $strId.'_remove';
				$objFormSubmitRemove->slabel = $GLOBALS['TL_LANG']['metamodels_notelist']['removeLabel'];
				$entry['input_remove'] = $objFormSubmitRemove->generate();
				
				//-- data
				$entry['data'] = $objMetaModelNotelist->prepareDataForWidget($entry['metamodel'],$entry['id'],$arrVisibles);
				
				//-- status message
				$entry['statusMessage'] = $this->strStatusMessage;
				
				//-- variants
				if(count($entry['variants']) > 0)
				{
					$arrTemplateVariants = array();
			
					// create metamodel instance
					$objMetaModel = MetaModelFactory::byId($entry['metamodel']);
					// create metamodelnotelistvariants instance
					$objMetaModelNotelistVariants = MetaModelNotelistVariants::getInstance();
			
					// generate variants
					foreach($entry['variants'] as $strName => $arrAttribute)
					{
						// metamodel attribute instance
						$objVariantAttr = $objMetaModel->getAttributeById($arrAttribute['id']);
						
						// generate widget
						$arrFieldDef = array
						(
							'id'	=> $entry['metamodel'].'_'.$entry['id'].'_'.$objVariantAttr->get('id'),
							'name'	=> $strName,
							'value'	=> $arrAttribute['value'],
						);
						$arrFieldDef = array_merge($objVariantAttr->getFieldDefinition(),$arrFieldDef);
				
						$objWidget = $objMetaModelNotelistVariants->loadFormField($arrFieldDef);
				
						// collect variants
						$arrTemplateVariants[$strName] = array
						(
							'id'	=> $objVariantAttr->get('id'),
							'html'	=> $objWidget->generate(),
							'raw'	=> $objWidget,
							'value'	=> $arrAttribute['value'],
							'attribute' => $objVariantAttr,
						);
					}
					
					$entry['variants'] = $arrTemplateVariants;
				}
			
				// set
				$arrTmp[] = $entry;
				
				++$i;
			}
		}
		// prepare for email
		else
		{
			foreach($arrNotelist as $entry)
			{
				$entry['data'] = $objMetaModelNotelist->prepareDataForWidget($entry['metamodel'],$entry['id'],$arrVisibles);
				
				//-- variants
				if(count($entry['variants']) > 0)
				{
					$arrTemplateVariants = array();
			
					// create metamodel instance
					$objMetaModel = MetaModelFactory::byId($entry['metamodel']);
					// create metamodelnotelistvariants instance
					$objMetaModelNotelistVariants = MetaModelNotelistVariants::getInstance();
			
					// generate variants
					foreach($entry['variants'] as $strName => $arrAttribute)
					{
						// metamodel attribute instance
						$objVariantAttr = $objMetaModel->getAttributeById($arrAttribute['id']);
						
						// generate widget
						$arrFieldDef = array
						(
							'id'	=> $entry['metamodel'].'_'.$entry['id'].'_'.$objVariantAttr->get('id'),
							'name'	=> $strName,
							'value'	=> $arrAttribute['value'],
						);
						$arrFieldDef = array_merge($objVariantAttr->getFieldDefinition(),$arrFieldDef);
				
						$objWidget = $objMetaModelNotelistVariants->loadFormField($arrFieldDef);
				
						// collect variants
						$arrTemplateVariants[$strName] = array
						(
							'id'	=> $objVariantAttr->get('id'),
							'html'	=> $objWidget->generate(),
							'raw'	=> $objWidget,
							'value'	=> $arrAttribute['value'],
							'attribute' => $objVariantAttr,
						);
						
					}
					
					$entry['variants'] = $arrTemplateVariants;
				}
				
				// set
				$arrTmp[] = $entry;
			}
		}
		$arrNotelist = $arrTmp;
		unset($arrTmp);
		
		$objTemplate->entries = $arrNotelist;
		$objTemplate->total = count($arrNotelist);
		
		$strBuffer = $objTemplate->parse();
		$strBuffer = $this->replaceInsertTags($strBuffer);
		
		if($blnFormMail)
		{
			$objString = String::getInstance();
			$strBuffer = trim($objString->toXhtml($strBuffer));
			$strBuffer = str_replace("\t", " ", $strBuffer);;
			
			#$strBuffer = $objString->decodeEntities(trim($strBuffer)); 
			$strBuffer = trim(preg_replace('/\.$/m', ' ', $strBuffer));
			#$strBuffer = trim(preg_replace('/\s\s+/', ' ', $strBuffer));
			#$strBuffer = trim(preg_replace('{(.)\1+}', '$1', $strBuffer));
			
		}
		
		return $strBuffer;

	}
	
	/**
	 * Update or remove items in here
	 */
	public function validate()
	{
		$objMetaModelNotelist = MetaModelNotelist::getInstance();
		
		$arrNotelist = $objMetaModelNotelist->getNotelist();
		if(count($arrNotelist) < 1)
		{
			return;
		}
		
		$blnReload = $GLOBALS['metamodels_notelist']['autoReloadPage'];
		
		$objInput = Input::getInstance();
		
		foreach($arrNotelist as $mmId => $arrEntries)
		{
			foreach($arrEntries as $entry)
			{
				$strId = 'mm_notelist_'.$entry['metamodel'].'_'.$entry['id'];
				
				//-- check for post action
				// update item
				#if(strlen($objInput->post($strId.'_update')) > 0)
				if(strlen($_POST[$strId.'_update']) > 0)
				{
					$blnUpdate = false;
					
					$amount = $objInput->post($strId.'_amount');
					
					if($entry['amount'] != $amount)
					{
						$blnUpdate = true;
					}
					
					if(count($entry['variants']) > 0)
					{
						foreach($entry['variants'] as $strName => $arrAttribute)
						{
							if($objInput->post($strName) && $arrAttribute['value'] != $objInput->post($strName) )
							{
								$entry['variants'][$strName]['value'] = $objInput->post($strName);
								$blnUpdate = true;
							}
						}
					}
					
					// create a psydo amount input field to valide input
					$arrData=array('eval'=>array('rgxp' => 'digit', 'mandatory'=>true));
					$objAmountWidget=new FormTextField($this->prepareForWidget($arrData, $strId.'_amount', $amount, $strId.'_amount'));
					$objAmountWidget->validate();
					if($objAmountWidget->hasErrors())
					{
						$this->class = 'error';
						$this->addError($GLOBALS['TL_LANG']['ERR']['digit']);
					}
					else
					{	
						if($blnUpdate)
						{
							// toggle status message
							if(strlen($_POST[$strId.'_update']) > 0)	
								{$this->strStatusMessage = $GLOBALS['TL_LANG']['metamodels_notelist']['itemUpdated'];}
							else 
								{$this->strStatusMessage = $GLOBALS['TL_LANG']['metamodels_notelist']['itemAdded'];}
							
							// set the notelist
							$objMetaModelNotelist->setItem($entry['metamodel'],$entry['id'],$amount,$entry['variants'],$blnReload);
						}
					}
					
					// avoid sending the form when page is not being reloaded after an update
					$this->addError('');
					
				}
				// remove item
				else if($_POST[$strId.'_remove'])
				{
					// remove item and reload
					$objMetaModelNotelist->removeItem($entry['metamodel'],$entry['id']);
				}
				else{}
			}
		}
		
		
	}

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### METAMODELS NOTELIST ###';
			$objTemplate->id = $this->id;
			$objTemplate->title = $this->headline;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=form&amp;table=tl_form_field&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		
		return $this->generateOutput();
	}
	
	
	
	
}
