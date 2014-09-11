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

class GridToolsHelper extends \BackendModule
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_gridtools_helper';

	/**
	 * Data container object
	 * @var object
	 */
	protected $objDc;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Generate module
	 */
	protected function compile()
	{
		\System::loadLanguageFile('tl_content');
		\System::import('ContentModel');

		$arrRow = array();
		$result = $this->Database->prepare("SELECT id,pid,type,invisible,gridtools_parent,gridtools_row,gridtools_col FROM tl_content WHERE gridtools_parent!=? ORDER BY id")->execute(0);
		while($result->next())
		{
			$arrRow[] = $result->row(false);
		}

		if(\Input::get('restore_id') && \Input::get('restore_pid'))
		{
			$restoreId = \Input::get('restore_id');
			$restorePid = \Input::get('restore_pid');

			$setDb = array('pid' => $restorePid, 'gridtools_parent' => '0', 'gridtools_row' => '0', 'gridtools_col' => '0');
			$this->Database->prepare("UPDATE tl_content %s WHERE id=?")->set($setDb)->execute($restoreId);

			$this->redirect($this->getReferer());
		}

		$gridTools = '';
		$gridTools .= '<h1 id="tl_welcome">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_headline'] . '</h1>';
		$gridTools .= '<div class="tl_listing_container list_view">';
		$gridTools .= '<h2>' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_infotable'] . '</h2>';
		$gridTools .= '<table class="tl_listing">';
		$gridTools .= '<tbody>';
		$gridTools .= '<tr>';
		$gridTools .= '<td class="tl_folder_tlist">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_table_id'] . '</td>';
		$gridTools .= '<td class="tl_folder_tlist">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_table_type'] . '</td>';
		$gridTools .= '<td class="tl_folder_tlist">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_table_article_id'] . '</td>';;
		$gridTools .= '<td class="tl_folder_tlist">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_table_parent_id'] . '</td>';
		$gridTools .= '<td class="tl_folder_tlist">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_table_parent_row'] . '</td>';
		$gridTools .= '<td class="tl_folder_tlist">' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_table_parent_col'] . '</td>';
		$gridTools .= '<td class="tl_folder_tlist">&nbsp;</td>';
		$gridTools .= '</tr>';
		$i = 1;
		foreach($arrRow as $rowKey => $rowVal)
		{
			if($i == 1)
			{
				$evod = 'even';
				$i++;
			}
			else
			{
				$evod = 'odd';
				$i = 1;
			}

			$arrButtons = array('edit', 'restore', 'delete', 'show');
			$buttons = $this->genButtons($rowVal, $arrButtons);

			$gridTools .= '<tr class="' . $evod . ' click2edit" onmouseout="Theme.hoverRow(this,0)" onmouseover="Theme.hoverRow(this,1)">';
			$gridTools .= '<td class="tl_file_list">' . $rowVal['id'] . '</td>';
			$gridTools .= '<td class="tl_file_list">' . $GLOBALS['TL_LANG']['CTE'][$rowVal['type']][0] . '</td>';
			$gridTools .= '<td class="tl_file_list">' . $rowVal['pid'] . '</td>';
			$gridTools .= '<td class="tl_file_list">' . $rowVal['gridtools_parent'] . '</td>';
			$gridTools .= '<td class="tl_file_list">' . $rowVal['gridtools_row'] . '</td>';
			$gridTools .= '<td class="tl_file_list">' . $rowVal['gridtools_col'] . '</td>';
			$gridTools .= '<td class="tl_file_list">' . $buttons . '</td>';
			$gridTools .= '</tr>';
		}
		$gridTools .= '</tbody>';
		$gridTools .= '</table>';
		$gridTools .= '</div>';

		$gridToolsShowCe = explode(',', $GLOBALS['TL_CONFIG']['gridToolsShowCe']);
		if(in_array('0', $gridToolsShowCe))
		{
			$gridConfig = '<li>GridElemente werden immer angezeigt</li>';
		}

		$gridToolsConfig .= '<div class="tl_listing_container">';
		$gridToolsConfig .= '<h2>' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_configuration'] . '</h2>';
		$gridToolsConfig .= '<ul>';
		$gridToolsConfig .= $gridConfig;
		$gridToolsConfig .= $GLOBALS['TL_LANG']['MOD']['gridtools_helper_configuration_text'] . ' <b>' . $GLOBALS['TL_CONFIG']['gridToolsShowCe'] . '</b>';
		$gridToolsConfig .= '</ul>';
		$gridToolsConfig .= '</div>';

		$this->Template->gridElements = $gridTools;
		$this->Template->gridElementsConfig = $gridToolsConfig;
	}


	/* system/modules/core/classes/DataContainer.php generateButtons() */
	function genButtons($arrRow, $arrButtons)
	{
		$GLOBALS['TL_DCA']['tl_content']['list']['operations']['restore'] = array
		(
			'label' => &$GLOBALS['TL_LANG']['MOD']['gridtools_helper_restore'],
			//'href' => 'restore=1',
			'icon' => 'cut_.gif',
			'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MOD']['gridtools_helper_restore_confirm'] . '\'))return false;Backend.getScrollOffset()"',
			'button_callback' => array('GridToolsHelper', 'restoreElement')
		);

		$buttons = '';
		foreach($GLOBALS['TL_DCA']['tl_content']['list']['operations'] as $k => $v)
		{
			if(in_array($k, $arrButtons))
			{
				$v = is_array($v) ? $v : array($v);
				$id = specialchars(rawurldecode($arrRow['id']));
				$pid = specialchars(rawurldecode($arrRow['pid']));

				$label = $v['label'][0] ?: $k;
				$title = sprintf($v['label'][1] ?: $k, $id);
				if($k == 'restore')
				{
					$title = sprintf($v['label'][1] ?: $k, $pid);
				}
				$attributes = ($v['attributes'] != '') ? ' ' . ltrim(sprintf($v['attributes'], $id, $id)) : '';

				if(strpos($attributes, 'class="') !== false)
				{
					$attributes = str_replace('class="', 'class="' . $k . ' ', $attributes);
				}
				else
				{
					$attributes = ' class="' . $k . '"' . $attributes;
				}

				$href = $v['href'] . '&amp;do=article&amp;table=tl_content';
				if(is_array($v['button_callback']))
				{
					$this->import($v['button_callback'][0]);
					$buttons .= $this->$v['button_callback'][0]->$v['button_callback'][1]($arrRow, $href, $label, $title, $v['icon'], $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext);
					continue;
				}
				elseif(is_callable($v['button_callback']))
				{
					$buttons .= $v['button_callback']($arrRow, $href, $label, $title, $v['icon'], $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext);
					continue;
				}

				$buttons .= '<a href="'.$this->addToUrl($v['href'].'&amp;do=article&amp;table=tl_content&amp;id='.$arrRow['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($v['icon'], $label).'</a> ';
			}
		}

		return $buttons;
	}


	public function restoreElement($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="'.$this->addToUrl('&amp;do=gridtools_helper&amp;restore_id='.$row['id']).'&amp;restore_pid='.$row['pid'].'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
	}
}