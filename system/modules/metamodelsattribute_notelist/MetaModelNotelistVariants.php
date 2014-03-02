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
 * Class MetaModelNotelistVariants
 *
 * Provide various functions for the metamodels_notelist variants handling
 * @package		metamodels_notelist
 * @author		Tim Gatzky <info@tim-gatzky.de>
 */
 
class MetaModelNotelistVariants extends MetaModelNotelist
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
			self::$objInstance = new MetaModelNotelistVariants();
		}
		return self::$objInstance;
	}
	
	
	/**
	 * Create widgets and return the widget object
	 * @param array
	 * @return object
	 */
	public function loadFormField($arrFieldDef)
	{
		// generate widget by type
		$objWidget = null;
		switch($arrFieldDef['inputType'])
		{
			case 'select':
				$arrFieldDef['options'] = $this->getOptions($arrFieldDef);
				
				$objWidget = new FormSelectMenu($arrFieldDef);
				$objWidget->__set('id',($arrFieldDef['id'] ? $arrFieldDef['id'] : $objAttribute->get('id')) );
				$objWidget->__set('name',($arrFieldDef['name'] ? $arrFieldDef['name'] : $objAttribute->get('colname')) );
				#$objWidget->__set('label',$arrFieldDef['label'][0]);
				
				break;
			case 'checkbox':
				$arrFieldDef['options'] = $this->getOptions($arrFieldDef);
				
				$objWidget = new FormCheckbox($arrFieldDef);
				$objWidget->__set('id',($arrFieldDef['id'] ? $arrFieldDef['id'] : $objAttribute->get('id')) );
				$objWidget->__set('name',($arrFieldDef['name'] ? $arrFieldDef['name'] : $objAttribute->get('colname')) );
				$objWidget->__set('label',$arrFieldDef['label'][0]);
				
				break;
			case 'radio':
				$arrFieldDef['options'] = $this->getOptions($arrFieldDef);
				
				$objWidget = new FormRadioButton($arrFieldDef);
				$objWidget->__set('id',($arrFieldDef['id'] ? $arrFieldDef['id'] : $objAttribute->get('id')) );
				$objWidget->__set('name',($arrFieldDef['name'] ? $arrFieldDef['name'] : $objAttribute->get('colname')) );
				$objWidget->__set('label',$arrFieldDef['label'][0]);
				
				break;
			// HOOK: allow other extensions to insert widgets
			default:
				if (isset($GLOBALS['TL_HOOKS']['METAMODELNOTELIST']['loadFormField']) && count($GLOBALS['TL_HOOKS']['METAMODELNOTELIST']['loadFormField']) > 0)
				{
					foreach($GLOBALS['TL_HOOKS']['METAMODELNOTELIST']['loadFormField'] as $callback)
					{
						$this->import($callback[0]);
						$objWidget = $this->$callback[0]->$callback[1]($arrFieldDef);
					}
				}
				break;
		}

		return $objWidget;
	}
	
	
	/**
	 * Reformat options array for widgets
	 * @param array
	 * @return array
	 */
	protected function getOptions($arrFieldDef)
	{
		if(count($arrFieldDef['options']) < 1 && !$arrFieldDef['eval']['includeBlankOption'])
		{
			return array();
		}
		
		$arrReturn = array();
		if($arrFieldDef['inputType'] == 'select' && $arrFieldDef['eval']['includeBlankOption'])
		{
			$arrReturn[0] = array('value'=>$GLOBALS['TL_LANG']['metamodels_notelist']['blankOption']);
		}
		
		foreach($arrFieldDef['options'] as $value => $label)
		{
			$arrReturn[] = array('value'=>$value,'label'=>$label);
		}
		
		return $arrReturn;		
	}
	
}