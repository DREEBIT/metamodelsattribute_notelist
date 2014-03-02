-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_metamodel_attribute`
-- 

CREATE TABLE `tl_metamodel_attribute` (
  `notelist_enableVariants` char(1) NOT NULL default '',
  `notelist_variants` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_form_field`
-- 

CREATE TABLE `tl_form_field` (
  `metamodels_notelist_formTpl` varchar(64) NOT NULL default '',
  `metamodels_notelist_mailTpl` varchar(64) NOT NULL default '',
  `metamodels_notelist_metamodel` int(10) unsigned NOT NULL default '0',
  `metamodels_notelist_visibles` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;