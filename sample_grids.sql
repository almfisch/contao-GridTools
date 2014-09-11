--
-- Daten für Tabelle `tl_gridtools`
--

INSERT INTO `tl_gridtools` (`title`, `configuration`) VALUES
('Bootstrap 1er Grid &#40;12&#41;', '{\n	''pid'': ''0'',\n	''wrap'': ''<div class="container">|</div>'',\n	''rowCount'': ''1'',\n	''rows'': {\n		''1'': {\n			''colCount'': ''1'',\n			''wrap'': ''<div class="row">|</div>'',\n			''columns'': {\n				''1'': {\n					''name'': ''12er Grid'',\n					''wrap'': ''<div class="col-md-12">|</div>''\n				}\n			}\n		}\n	}\n}'),
('Bootstrap 2er Grid &#40;6-6&#41;', '{\n	''pid'': ''0'',\n	''allowed'': ''text'',\n	''wrap'': ''<div class="container">|</div>'',\n	''rowCount'': ''1'',\n	''rows'': {\n		''1'': {\n			''colCount'': ''2'',\n			''wrap'': ''<div class="row">|</div>'',\n			''columns'': {\n				''1'': {\n					''name'': ''Links 6'',\n					''wrap'': ''<div class="col-md-6 col-lg-8">|</div>''\n				},\n				''2'': {\n					''name'': ''Rechts 6'',\n					''wrap'': ''<div class="col-md-6 col-lg-4">|</div>''\n				}\n			}\n		}\n	}\n}'),
('Bootstrap 4er Grid &#40;3-3-3-3&#41;', '{\n	''pid'': ''0'',\n	''wrap'': ''<div class="container">|</div>'',\n	''rowCount'': ''1'',\n	''rows'': {\n		''1'': {\n			''colCount'': ''4'',\n			''wrap'': ''<div class="row">|</div>'',\n			''columns'': {\n				''1'': {\n					''name'': ''Spalte 1'',\n					''wrap'': ''<div class="col-md-3">|</div>''\n				},\n				''2'': {\n					''name'': ''Spalte 2'',\n					''wrap'': ''<div class="col-md-3">|</div>''\n				},\n				''3'': {\n					''name'': ''Spalte 3'',\n					''wrap'': ''<div class="col-md-3">|</div>''\n				},\n				''4'': {\n					''name'': ''Spalte 4'',\n					''wrap'': ''<div class="col-md-3">|</div>''\n				}\n			}\n		}\n	}\n}'),
('Bootstrap 2 Rows &#40;6-6, 12&#41;', '{\n	''pid'': ''0'',\n	''allowed'': ''text,headline,html,image,gallery,module'',\n	''wrap'': ''<div class="container">|</div>'',\n	''rowCount'': ''2'',\n	''rows'': {\n		''1'': {\n			''colCount'': ''2'',\n			''wrap'': ''<div class="row">|</div>'',\n			''columns'': {\n				''1'': {\n					''name'': ''Links 6'',\n					''wrap'': ''<div class="col-md-6">|</div>''\n				},\n				''2'': {\n					''name'': ''Rechts 6'',\n					''wrap'': ''<div class="col-md-6">|</div>''\n				}\n			}\n		},\n		''2'': {\n			''colCount'': ''1'',\n			''wrap'': ''<div class="row">|</div>'',\n			''columns'': {\n				''1'': {\n					''name'': ''12er Grid'',\n					''wrap'': ''<div class="col-md-12">|</div>''\n				}\n			}\n		}\n	}\n}');