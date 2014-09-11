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

namespace Contao;

class ContentGridTools extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_gridtools';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		$id = $this->gridtools_grid;
		$query = $this->Database->prepare("SELECT configuration FROM tl_gridtools WHERE id=?")->limit(1)->execute($id);
		if($query->numRows)
		{
			$raw = $query->configuration;
		}

		$id = $this->id;
		$objElements = $this->findPublishedByGridToolsParentAndTable($id, 'tl_article');

		$contentElements = array();
		if($objElements !== null)
		{
    		while($objElements->next())
    		{
        		$contentElements[$objElements->gridtools_row][$objElements->gridtools_col][] = $this->getContentGridToolsElement($objElements->id);
    		}
		}

		$raw = str_replace('"', '\"', $raw);
		$raw = str_replace('\'', '"', $raw);
		$valArr = json_decode($raw, true);

		$valHtml = '';
		$gridWrap = explode('|', $valArr['wrap']);
		$valHtml .= $gridWrap[0];
		foreach($valArr['rows'] as $rowKey => $rowVal)
		{
			$rowWrap = explode('|', $rowVal['wrap']);
			$valHtml .= $rowWrap[0];
			foreach($rowVal['columns'] as $colKey => $colVal)
			{
				$colWrap = explode('|', $colVal['wrap']);
				$valHtml .= $colWrap[0];
				if($contentElements[$rowKey][$colKey])
				{
					foreach($contentElements[$rowKey][$colKey] as $contentKey => $contentVal)
					{
						$valHtml .= $contentVal;
					}
				}
				$valHtml .= $colWrap[1];
			}
			$valHtml .= $rowWrap[1];
		}
		$valHtml .= $gridWrap[1];

		$this->Template->gridTools = $valHtml;

		return;
	}

	public static function findPublishedByGridToolsParentAndTable($intPid, $strParentTable, array $arrOptions=array())
	{
		$t = 'tl_content';

		// Also handle empty ptable fields (backwards compatibility)
		if ($strParentTable == 'tl_article')
		{
			$arrColumns = array("$t.gridtools_parent=? AND (ptable=? OR ptable='')");
		}
		else
		{
			$arrColumns = array("$t.gridtools_parent=? AND ptable=?");
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.invisible=''";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return \ContentModel::findBy($arrColumns, array($intPid, $strParentTable), $arrOptions);
	}
	
	protected function getContentGridToolsElement($intId, $strColumn='main')
	{
		if (is_object($intId))
		{
			$objRow = $intId;
		}
		else
		{
			if (!strlen($intId) || $intId < 1)
			{
				return '';
			}

			$objRow = \ContentModel::findByPk($intId);

			if ($objRow === null)
			{
				return '';
			}
		}

		// Check the visibility (see #6311)
		if (!static::isVisibleElement($objRow))
		{
			return '';
		}

		// Remove the spacing in the back end preview
		if (TL_MODE == 'BE')
		{
			$objRow->space = null;
		}

		$strClass = \ContentElement::findClass($objRow->type);

		// Return if the class does not exist
		if (!class_exists($strClass))
		{
			$this->log('Content element class "'.$strClass.'" (content element "'.$objRow->type.'") does not exist', __METHOD__, TL_ERROR);
			return '';
		}

		$objRow->typePrefix = 'ce_';
		$objElement = new $strClass($objRow, $strColumn);
		$strBuffer = $objElement->generate();

		// Disable indexing if protected
		if ($objElement->protected && !preg_match('/^\s*<!-- indexer::stop/', $strBuffer))
		{
			$strBuffer = "\n<!-- indexer::stop -->". $strBuffer ."<!-- indexer::continue -->\n";
		}

		return $strBuffer;
	}
}