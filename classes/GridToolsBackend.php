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

class GridToolsBackend extends \Template
{
	public function backButton($strContent, $strTemplate)
	{
		$strContent = preg_replace('/<div class=\"cte_type .*\">GridTools (.*)<\/div>/', '<div class="cte_type cte_type_gridparent published">GridTools $1</div>', $strContent);

		return $strContent;
	}
}