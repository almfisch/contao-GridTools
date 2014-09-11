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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'GridTools',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'GridTools\GridToolsBackend' => 'system/modules/GridTools/classes/GridToolsBackend.php',
	'GridTools\GridToolsFrontend' => 'system/modules/GridTools/classes/GridToolsFrontend.php',
	'GridTools\GridToolsHelper' => 'system/modules/GridTools/classes/GridToolsHelper.php',

	// Elements
	'Contao\ContentGridTools'     => 'system/modules/GridTools/elements/ContentGridTools.php',

	// Widgets
	'Contao\GridContent'          => 'system/modules/GridTools/widgets/GridContent.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_gridtools' => 'system/modules/GridTools/templates',
	'mod_gridtools_helper' => 'system/modules/GridTools/templates'
));
