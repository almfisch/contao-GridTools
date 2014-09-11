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

if(TL_MODE === 'BE') {
	$GLOBALS['TL_CSS'][] = 'system/modules/GridTools/assets/css/be_gridtools.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/GridTools/assets/js/be_gridtools.js';
}


/**
 * Table tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('gridtools', 'loadCallback');
$GLOBALS['TL_DCA']['tl_content']['config']['oncut_callback'][] = array('gridtools', 'cutCallback');
$GLOBALS['TL_DCA']['tl_content']['list']['global_operations']['gridtools_headertoggle'] = array
(
	'label' => &$GLOBALS['TL_LANG']['tl_content']['gridtools_headertoggle_on'],
	'class' => 'header_gridtools_goggle',
	'icon' => 'invisible.gif',
	'button_callback' => array('gridtools', 'buttonToggleGridElements')
);


/**
 * Add palettes to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'gridtools_grid';
$GLOBALS['TL_DCA']['tl_content']['palettes']['gridtools'] = '{type_legend},type,gridtools_grid,gridtools_elements;{protected_legend:hide},protected;{expert_legend:hide},guests';


/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['gridtools_grid'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gridtools_grid'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_gridtools.title',
	//'relation'                => array('type'=>'hasOne', 'load'=>'eager'),
	'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true, 'includeBlankOption'=>true, 'blankOptionLabel'=>'--- Select ---'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['gridtools_elements'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gridtools_elements'],
	'exclude'                 => true,
	'inputType'               => 'gridContent',
	'load_callback'           => array(array('gridtools', 'getGridContent')),
	'eval'                    => array('doNotSaveEmpty'=>true)
);
$GLOBALS['TL_DCA']['tl_content']['fields']['gridtools_row'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gridtools_row'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'load_callback'           => array(array('gridtools', 'getGridRow')),
	'eval'                    => array('alwaysSave'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['gridtools_col'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gridtools_col'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'load_callback'           => array(array('gridtools', 'getGridCol')),
	'eval'                    => array('alwaysSave'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['gridtools_parent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gridtools_parent'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'load_callback'           => array(array('gridtools', 'getGridParent')),
	'eval'                    => array('alwaysSave'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);


class gridtools extends Backend
{
	public function loadCallback(DataContainer $dc)
	{
		$gridToolsShowCe = explode(',', $GLOBALS['TL_CONFIG']['gridToolsShowCe']);

		$this->Session->remove('showGridElements');
		if(in_array($dc->id, $gridToolsShowCe) || in_array('0', $gridToolsShowCe))
		{
			$this->Session->set('showGridElements', 'show');
		}

		if(Input::get('gridtoggle') == 'show')
		{
			$this->Session->set('showGridElements', 'show');
		}
		if(Input::get('gridtoggle') == 'hide')
		{
			$hideGridElements = 1;
			$this->Session->remove('showGridElements');
		}

		if(!$this->Session->get('showGridElements'))
		{
			if((!in_array($dc->id, $gridToolsShowCe) && !in_array('0', $gridToolsShowCe)) || $hideGridElements)
			{
				$GLOBALS['TL_DCA']['tl_content']['list']['sorting']['filter'] = array(array('gridtools_parent=?', '0'));
			}
		}

		$id = $dc->id;
		$query = $this->Database->prepare("SELECT type,gridtools_parent FROM tl_content WHERE id=?")->limit(1)->execute($id);
		if($query->numRows)
		{
			$cType = $query->type;
			$gridToolsParent = $query->gridtools_parent;
		}

		if($cType == 'gridtools')
		{
			$GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['attributes'] = 'onclick="Backend.getScrollOffset(); GridTools.toggleGridVisiblity(this,%s); return AjaxRequest.toggleVisibility(this,%s);"';
		}

		if(Input::get('gridparent') || $gridToolsParent != 0)
		{
			$query = $this->Database->prepare("SELECT gridtools_grid FROM tl_content WHERE id=?")->limit(1)->execute($gridToolsParent);
			if($query->numRows)
			{
				$gridToolsGrid = $query->gridtools_grid;
			}

			$query = $this->Database->prepare("SELECT configuration FROM tl_gridtools WHERE id=?")->limit(1)->execute($gridToolsGrid);
			if($query->numRows)
			{
				$gridToolsConfiguration = $query->configuration;
			}

			/* Start: Delete not allowed ContentElements from List */
			unset($GLOBALS['TL_CTE']['includes']['gridtools']);

			$rawConfiguration = $gridToolsConfiguration;
			$rawConfiguration = str_replace('"', '\"', $rawConfiguration);
			$rawConfiguration = str_replace('\'', '"', $rawConfiguration);
			$valArr = json_decode($rawConfiguration, true);

			if($valArr['allowed'])
			{
				$gridToolsAllowed = explode(',', $valArr['allowed']);
				foreach($GLOBALS['TL_CTE'] as $section => $sectionArr)
				{
					foreach($sectionArr as $singleType => $singleName)
					{
						if(!in_array($singleType, $gridToolsAllowed))
						{
							unset($GLOBALS['TL_CTE'][$section][$singleType]);
						}
					}
				}
			}
			/* End: Delete not allowed ContentElements from List */

			foreach($GLOBALS['TL_DCA']['tl_content']['palettes'] as $key => $value)
			{
				if($key != '__selector__')
				{
					$GLOBALS['TL_DCA']['tl_content']['palettes'][$key] = $value . ';{gridtools_legend},gridtools_row,gridtools_col,gridtools_parent';
				}
			}
		}
	}
	
	
	public function cutCallback(DataContainer $dc)
	{
		if(Input::get('gridparent') && Input::get('gridrow') && Input::get('gridcol'))
		{
			$setDb = array('gridtools_parent' => Input::get('gridparent'), 'gridtools_row' => Input::get('gridrow'), 'gridtools_col' => Input::get('gridcol'));
			$this->Database->prepare("UPDATE tl_content %s WHERE id=?")->set($setDb)->execute($dc->id);
		}
	}


	public function buttonToggleGridElements($row, $href, $label, $css, $icon, $attributes)
	{
		$label = $GLOBALS['TL_LANG']['tl_content']['gridtools_headertoggle'];

		if(!$this->Session->get('showGridElements'))
		{
			$gridtoggle = '&amp;gridtoggle=show';
		}
		else
		{
			$gridtoggle = '&amp;gridtoggle=hide';
			$icon = str_replace('invisible.gif', 'visible.gif', $icon);
		}

		return '<a href="' . $this->addToUrl($gridtoggle) . '" class="' . $css . '" ' . $icon . ' title="' . $label[1] . '"' . $attributes . '>' . $label[0] . '</a> ';
	}


	public function getGridContent($varValue, DataContainer $dc)
	{
		$id = $dc->activeRecord->gridtools_grid;
		$query = $this->Database->prepare("SELECT configuration FROM tl_gridtools WHERE id=?")->limit(1)->execute($id);
		if($query->numRows)
		{
			$configuration = $query->configuration;
		}

		return $configuration;
	}


	public function getGridParent($varValue, DataContainer $dc)
	{
		if(Input::get('gridparent'))
		{
			$varValue = Input::get('gridparent');
		}

		return $varValue;
	}


	public function getGridRow($varValue, DataContainer $dc)
	{
		if(Input::get('gridrow') == true)
		{
			$varValue = Input::get('gridrow');
		}

		return $varValue;
	}


	public function getGridCol($varValue, DataContainer $dc)
	{
		if(Input::get('gridcol') == true)
		{
			$varValue = Input::get('gridcol');
		}

		return $varValue;
	}
}
