<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2013
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		metamodels_notelist
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['metamodels_notelist'] = '{type_legend},type,name;{fconfig_legend},metamodels_notelist_metamodel,metamodels_notelist_visibles;{template_legend},metamodels_notelist_formTpl,metamodels_notelist_mailTpl;';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['metamodels_notelist_metamodel'] = array
(
	'label'					  => &$GLOBALS['TL_LANG']['tl_form_field']['metamodels_notelist_metamodel'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('TableFormFieldMetaModelsNotelist', 'getMetaModels'),
	'eval'                    => array('includeBlankOption'=>true,'submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['metamodels_notelist_visibles'] = array
(
	'label'					  => &$GLOBALS['TL_LANG']['tl_form_field']['metamodels_notelist_visibles'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('TableFormFieldMetaModelsNotelist', 'getAttributes'),
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['metamodels_notelist_formTpl'] = array
(
	'label'					  => &$GLOBALS['TL_LANG']['tl_form_field']['metamodels_notelist_formTpl'],
	#'default'				  => 'form_mm_notelist',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('TableFormFieldMetaModelsNotelist', 'getFormTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['metamodels_notelist_mailTpl'] = array
(
	'label'					  => &$GLOBALS['TL_LANG']['tl_form_field']['metamodels_notelist_mailTpl'],
	#'default'				  => 'mail_booking',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('TableFormFieldMetaModelsNotelist', 'getMailTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);
