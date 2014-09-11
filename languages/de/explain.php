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


$GLOBALS['TL_LANG']['XPL']['gridTools'] = array
(
	array('Format', 'Wie JSON nur mit SingleQuotes, so müssen DoubleQuotes nicht escaped werden und der Code bleibt übersichtlicher (siehe Beispielkonfiguration in der readme.txt und sample_grids.sql)'),
	array('pid', 'Entweder 0 für die ID des aktuellen Artikels oder die ID eines beliebigen Artikels, in dem die Inhalts-Elemente gespeichert werden sollen'),
	array('allowed', 'Kommagetrennte Liste erlaubter Inhaltstypen (z.B. text,headline,html,image,gallery,module)'),
	array('rowCount', 'Anzahl der Reihen'),
	array('colCount', 'Anzahl der Spalten'),
	array('wrap', 'Wrap um das Grid, die Rows und Cols mit Pipe (|) als Platzhalter für den Inhalt'),
	array('name', 'Bezeichnungen der einzelnen Elemente im Backend'),
	array('Weitere Infos', '<a href="http://www.json.org/json-de.html" target="_blank">json.org</a>, <a href="http://de.wikipedia.org/wiki/JavaScript_Object_Notation" target="_blank">wikipedia.org</a>')
);
