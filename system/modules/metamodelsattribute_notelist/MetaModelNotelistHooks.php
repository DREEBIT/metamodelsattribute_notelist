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
 * Class file
 * MetaModelsNotelistHooks
 * Hook callbacks for metamodels notelists
 */
class MetaModelNotelistHooks extends \System
{
	/**
	 * Current object instance (Singleton)
	 * @var Database
	 */
	protected static $objInstance;
	
	/**
	 * Instantiate this class and return it (Factory)
	 * @return FormPayment
	 * @throws Exception
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
	}

	/**
	 * SetItem Hook
	 * Called when an Item is deleted from the notelist
	 * @param array		new Session array after removing
	 * @param integer	id of metamodel
	 * @param integer	id of metamodel item
	 * @param integer	amount
	 * @param array		item variants
	 * @return array	session array to be saved
	 */
	public function callSetItemHook($arrSession,$intMetaModel,$intItem,$intAmount,$arrVariants)
	{
		if (isset($GLOBALS['METAMODELNOTELIST_HOOKS']['addItem']) && count($GLOBALS['METAMODELNOTELIST_HOOKS']['addItem']) > 0)
		{
			foreach($GLOBALS['METAMODELNOTELIST_HOOKS']['addItem'] as $callback)
			{
				$this->import($callback[0]);
				$arrSession = $this->$callback[0]->$callback[1]($arrSession,$intMetaModel,$intItem,$intAmount,$arrVariants);
			}
		}
		
		return $arrSession;
	}
	
	/**
	 * RemoveItem Hook
	 * Called when an Item is deleted from the notelist
	 * @param array		new Session array after removing
	 * @param integer	id of metamodel
	 * @param integer	id of metamodel item
	 */
	public function callRemoveItemHook($arrSession,$intMetaModel,$intItem)
	{
		if (isset($GLOBALS['METAMODELNOTELIST_HOOKS']['removeItem']) && count($GLOBALS['METAMODELNOTELIST_HOOKS']['removeItem']) > 0)
		{
			foreach($GLOBALS['METAMODELNOTELIST_HOOKS']['removeItem'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrSession,$intMetaModel,$intItem);
			}
		}
	}
	
}