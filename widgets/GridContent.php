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

class GridContent extends \Widget
{
	protected $blnSubmitInput = true;
	protected $blnForAttribute = true;
	protected $strTemplate = 'be_widget';


	public function __set($strKey, $varValue)
	{
		parent::__set($strKey, $varValue);
	}


	public function generate()
	{
		if(!$this->varValue)
		{
			return false;
		}

		$this->import('Database');

		$contentElements = array();
		$query = $this->Database->prepare("SELECT id,type,invisible,gridtools_row,gridtools_col FROM tl_content WHERE gridtools_parent=? ORDER BY sorting")->execute($this->activeRecord->id);
		while($query->next())
		{
			$contentElements[$query->gridtools_row][$query->gridtools_col][] = array('id' => $query->id, 'type' => $query->type, 'invisible' => $query->invisible);
		}

		$raw = $this->varValue;
		$raw = str_replace('"', '\"', $raw);
		$raw = str_replace('\'', '"', $raw);
		$valArr = json_decode($raw, true);

		$id = $this->activeRecord->id;
		$pid = $valArr['pid'];
		if($valArr['pid'] == 0)
		{
			$pid = $this->activeRecord->pid;
		}
		
		// check clipboard for paste
		$blnClipboard = false;
		$arrClipboard = $this->Session->get('CLIPBOARD');
		if(!empty($arrClipboard['tl_content']))
		{
			$blnClipboard = true;
			$arrClipboard = $arrClipboard['tl_content'];
			if(is_array($arrClipboard['id']))
			{
				$blnClipboard = false;
			}
		}

		// set referer
		$ref = \Input::get('ref');
		$session = $this->Session->get('referer');
		if($session[$ref])
		{
			$session[TL_REFERER_ID]['last'] = $session[$ref]['last'];
			$session[TL_REFERER_ID]['tl_article'] = $session[$ref]['current'];
			$session[TL_REFERER_ID]['current'] = substr(\Environment::get('requestUri'), strlen(TL_PATH) + 1);
			$this->Session->set('referer', $session);
		}

		$valHtml = '';
		foreach($valArr['rows'] as $rowKey => $rowVal)
		{
			$width = 100 / $rowVal['colCount'];
			$valHtml .= '<div class="gridtools_row">';
			foreach($rowVal['columns'] as $colKey => $colVal)
			{
				$newElement = '<a href="' . $this->addToUrl('&amp;gridparent=' . $id . '&amp;gridrow=' . $rowKey . '&amp;gridcol=' . $colKey . '&amp;act=create&amp;mode=2&amp;pid=' . $pid . '&amp;id=' . $pid) . '" class="header_new" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['new'][1]).'" accesskey="n" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG'][$this->strTable]['new'][0].'</a>';

				$valHtml .= '<div class="gridtools_col" style="width: ' . $width . '%;">';
				$valHtml .= '<div class="gridtools_inner">';
				$valHtml .= '<div class="gridtools_head">';
				$valHtml .= $colKey . ': ' . $colVal['name'];
				$valHtml .= '</div>';
				$valHtml .= '<div class="gridtools_content">';
				$valHtml .= $newElement;
				$valHtml .= '<ul class="gridtools_ce_elements" id="gridtools_' . $rowKey . '_' . $colKey . '_ul_' . $pid . '">';
				if($contentElements[$rowKey][$colKey])
				{
					foreach($contentElements[$rowKey][$colKey] as $contentKey => $contentVal)
					{
						$published = 'published';
						if($contentVal['invisible'] == 1)
						{
							$published = 'unpublished';
						}

						//$arrButtons = array('edit', 'copy', 'cut', 'delete', 'toggle');
						$arrButtons = array('edit', 'delete', 'toggle');
						$buttons = $this->genButtons($contentVal, $arrButtons);
						$buttons .= ' ' . \Image::getHtml('drag.gif', '', 'class="drag-handle" title="' . sprintf($GLOBALS['TL_LANG'][$this->strTable]['cut'][1], $contentVal['id']) . '"');
						if($blnClipboard)
						{
							$imagePasteAfter = \Image::getHtml('pasteafter.gif', sprintf($GLOBALS['TL_LANG']['tl_content']['pasteafter'][1], $id));
							$buttons .= ' <a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$contentVal['id'].'&amp;id='.$arrClipboard['id']).'&amp;gridparent=' . $id . '&amp;gridrow=' . $rowKey . '&amp;gridcol=' . $colKey . '" title="'.specialchars(sprintf($GLOBALS['TL_LANG']['tl_content']['pasteafter'][1], $contentVal['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteAfter.'</a>';
						}

						$valHtml .= '<li class="gridtools_element" id="li_' . $contentVal['id'] . '">';
						$valHtml .= '<div class="gridtools_element_left cte_type ' . $published . '">';
						$valHtml .= $GLOBALS['TL_LANG']['CTE'][$contentVal['type']][0] . ' (' . $contentVal['id'] . ')';
						$valHtml .= '</div>';
						$valHtml .= '<div class="gridtools_element_right">';
						$valHtml .= $buttons;
						$valHtml .= '</div>';
						$valHtml .= '<div class="gridtools_clear"></div>';
						$valHtml .= '</li>';
					}
				}
				$valHtml .= '</ul>';
				$valHtml .= '<script>GridTools.makeParentViewSortable(\'gridtools_' . $rowKey . '_' . $colKey . '_ul_' . $pid . '\');</script>';
				$valHtml .= '</div>';
				$valHtml .= '</div>';
				$valHtml .= '</div>';
			}
			$valHtml .= '<div class="gridtools_clear"></div>';
			$valHtml .= '</div>';
		}

		$output = '';
		$output .= '<div class="gridtools_wrapper">';
		$output .= $valHtml;
		$output .= '</div>';

		return $output;
	}


	/* system/modules/core/classes/DataContainer.php generateButtons() */
	function genButtons($arrRow, $arrButtons)
	{
		$buttons = '';
		foreach($GLOBALS['TL_DCA']['tl_content']['list']['operations'] as $k => $v)
		{
			if(in_array($k, $arrButtons))
			{
				$v = is_array($v) ? $v : array($v);
				$id = specialchars(rawurldecode($arrRow['id']));

				$label = $v['label'][0] ?: $k;
				$title = sprintf($v['label'][1] ?: $k, $id);
				$attributes = ($v['attributes'] != '') ? ' ' . ltrim(sprintf($v['attributes'], $id, $id)) : '';

				if(strpos($attributes, 'class="') !== false)
				{
					$attributes = str_replace('class="', 'class="' . $k . ' ', $attributes);
				}
				else
				{
					$attributes = ' class="' . $k . '"' . $attributes;
				}

				if(is_array($v['button_callback']))
				{
					$this->import($v['button_callback'][0]);
					$buttons .= $this->$v['button_callback'][0]->$v['button_callback'][1]($arrRow, $v['href'], $label, $title, $v['icon'], $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext);
					continue;
				}
				elseif(is_callable($v['button_callback']))
				{
					$buttons .= $v['button_callback']($arrRow, $v['href'], $label, $title, $v['icon'], $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext);
					continue;
				}

				$buttons .= '<a href="'.$this->addToUrl($v['href'].'&amp;id='.$arrRow['id']).'&amp;gridelement=1" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($v['icon'], $label).'</a> ';
			}
		}

		return $buttons;
	}
}

?>