Sample Configurations - like JSON with SingleQuotes




Title: Bootstrap 1er Grid (12)

{
	'pid': '0',
	'wrap': '<div class="container">|</div>',
	'rowCount': '1',
	'rows': {
		'1': {
			'colCount': '1',
			'wrap': '<div class="row">|</div>',
			'columns': {
				'1': {
					'name': '12er Grid',
					'wrap': '<div class="col-md-12">|</div>'
				}
			}
		}
	}
}




Title: Bootstrap 2er Grid (6-6)

{
	'pid': '0',
	'allowed': 'text',
	'wrap': '<div class="container">|</div>',
	'rowCount': '1',
	'rows': {
		'1': {
			'colCount': '2',
			'wrap': '<div class="row">|</div>',
			'columns': {
				'1': {
					'name': 'Links 6',
					'wrap': '<div class="col-md-6">|</div>'
				},
				'2': {
					'name': 'Rechts 6',
					'wrap': '<div class="col-md-6">|</div>'
				}
			}
		}
	}
}




Title: Bootstrap 4er Grid (3-3-3-3)

{
	'pid': '0',
	'wrap': '<div class="container">|</div>',
	'rowCount': '1',
	'rows': {
		'1': {
			'colCount': '4',
			'wrap': '<div class="row">|</div>',
			'columns': {
				'1': {
					'name': 'Spalte 1',
					'wrap': '<div class="col-md-3">|</div>'
				},
				'2': {
					'name': 'Spalte 2',
					'wrap': '<div class="col-md-3">|</div>'
				},
				'3': {
					'name': 'Spalte 3',
					'wrap': '<div class="col-md-3">|</div>'
				},
				'4': {
					'name': 'Spalte 4',
					'wrap': '<div class="col-md-3">|</div>'
				}
			}
		}
	}
}




Title: Bootstrap 2 Rows (6-6, 12)

{
	'pid': '0',
	'allowed': 'text,headline,html,image,gallery,module',
	'wrap': '<div class="container">|</div>',
	'rowCount': '2',
	'rows': {
		'1': {
			'colCount': '2',
			'wrap': '<div class="row">|</div>',
			'columns': {
				'1': {
					'name': 'Links 6',
					'wrap': '<div class="col-md-6">|</div>'
				},
				'2': {
					'name': 'Rechts 6',
					'wrap': '<div class="col-md-6">|</div>'
				}
			}
		},
		'2': {
			'colCount': '1',
			'wrap': '<div class="row">|</div>',
			'columns': {
				'1': {
					'name': '12er Grid',
					'wrap': '<div class="col-md-12">|</div>'
				}
			}
		}
	}
}