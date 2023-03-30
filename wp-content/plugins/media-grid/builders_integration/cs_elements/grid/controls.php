<?php

/**
 * Element Controls
 */
 
// be sure tax are registered
include_once(MG_DIR .'/admin_menu.php'); 
register_taxonomy_mg_grids();
register_cpt_mg_item();


// grids array
$grids_arr = array(); 
foreach(get_terms('mg_grids', array('hide_empty' => 0, 'orderby' => 'name')) as $grid) {
	$grids_arr[] = array(
		'value' => $grid->term_id,
		'label' => $grid->name
	);
}


// pagination systems
$pag_sys = array(
	0 => array(
		'value' => '',
		'label' => __('default one', MG_ML)
	)
);
foreach(mg_static::pag_layouts() as $type => $name) {
	$pag_sys[] = array(
		'value' => $type,
		'label' => $name
	);
}
	

// filters array (use full list for now)
$filters_arr = array(
	0 => array(
		'value' => '',
		'label' => __('no initial filter', MG_ML)
	)
); 
foreach(mg_static::item_cats() as $cat_id => $cat_name) {
	$filters_arr[] = array(
		'value' => $cat_id,
		'label' => $cat_name
	);
}
 
 


/* FIELDS */
$fields =  array(
	'gid' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Grid', MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => $grids_arr
		),
	),

	'title_under' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Text under items?', MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => 0, 'label' => __('No', MG_ML)),
				array('value' => 1, 'label' => __('Yes - attached to item', MG_ML)),
				array('value' => 2, 'label' => __('Yes - detached from item', MG_ML)),
			)
		),
	),
	
	'pag_sys' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Pagination system', MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => $pag_sys
		),
	),

	'search' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Enable search?', MG_ML),
			'tooltip' => __('Enables search bar for grid items', MG_ML),
		),
	),

	
	/************************/
	'filter' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Enable filters?', MG_ML),
			'tooltip' => __('Allows items filtering by category', MG_ML),
		),
	),

	'filters_align' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Filters position', MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => 'top', 'label' => __('on top', MG_ML)),
				array('value' => 'left', 'label' => __('left side', MG_ML)),
				array('value' => 'right', 'label' => __('right side', MG_ML)),
			)
		),
	),
	
	'hide_all' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Hide "All" filter?', MG_ML),
			'tooltip' => __('Hides the "All" option from filters', MG_ML),
		),
	),
	
	'def_filter' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Default filter', MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => $filters_arr
		),
	),
	/***********************/

	'mobile_tresh' => array(
		'type'    => 'number',
		'ui' => array(
			'title'   => __('Custom mobile threshold (in pixels)', MG_ML),
			'tooltip' => __('Overrides global threshold. Leave empty to ignore', MG_ML),
		),
	),


	
	/*** STYLING ***/
	'cell_margin' => array(
		'type'    => 'number',
		'ui' => array(
			'title'   => __('Items margin', MG_ML),
			'tooltip' => __('Leave empty to use default value', MG_ML),
		),
	),
	'border_w' => array(
		'type'    => 'number',
		'ui' => array(
			'title'   => __('Items border width', MG_ML),
			'tooltip' => __('Leave empty to use default value', MG_ML),
		),
	),
	'border_col' => array(
		'type'    => 'color',
		'ui' => array(
			'title'   => __('Items border color', MG_ML),
			'tooltip' => __('Leave empty to use default value', MG_ML),
		),
	),
	'border_rad' => array(
		'type'    => 'number',
		'ui' => array(
			'title'   => __('Items border radius', MG_ML),
			'tooltip' => __('Leave empty to use default value', MG_ML),
		),
	),
	'outline' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __("Display items outline?", MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => '', 'label' => __('As default', MG_ML)),
				array('value' => 1, 'label' => __('Yes', MG_ML)),
				array('value' => 0, 'label' => __('No', MG_ML)),
			)
		),
	),
	'outline_col' => array(
		'type'    => 'color',
		'ui' => array(
			'title'   => __('Outline color', MG_ML),
			'tooltip' => __('Leave empty to use default value', MG_ML),
		),
	),
	'shadow' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __("Display items shadow?", MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => '', 'label' => __('As default', MG_ML)),
				array('value' => 1, 'label' => __('Yes', MG_ML)),
				array('value' => 0, 'label' => __('No', MG_ML)),
			)
		),
	),
	'txt_under_col' => array(
		'type'    => 'color',
		'ui' => array(
			'title'   => __('Text under images color', MG_ML),
			'tooltip' => __('Leave empty to use default value', MG_ML),
		),
	),
);



///// OVERLAY MANAGER ADD-ON ///////////
if(defined('MGOM_DIR')) {
	register_taxonomy_mgom(); // be sure tax are registered
	$overlays = get_terms('mgom_overlays', 'hide_empty=0');
	
	$ol_arr = array(
		0 => array(
			'value' => '',
			'label' => __('default one', MG_ML)
		)
	);
	foreach($overlays as $ol) {
		$ol_arr[] = array(
			'value' => $ol->term_id,
			'label' => $ol->name
		);
	}
	
	$fields['overlay'] = array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Custom Overlay', MG_ML),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => $ol_arr
		),
	);
}

return $fields;
