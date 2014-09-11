<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   GridTools
 * @author    Die Chiemseeler (Andi Platen)
 * @copyright Die Chiemseeler (Andi Platen)
 * @license   GNU/LGPL
 */


/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(';{frontend_legend}', ',gridToolsShowCe;{frontend_legend}', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['gridToolsShowCe'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['gridToolsShowCe'],
	'inputType'	=> 'text',
	'eval'      => array('tl_class'=>'w50 m12')
);
