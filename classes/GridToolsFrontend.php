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

namespace GridTools;

class GridToolsFrontend
{
	public function filterCe($strContent, $strTemplate)
	{
		if($strContent->gridtools_parent != 0)
		{
			return '';
		}
		else
		{
			return $strTemplate;
		}
	}
}