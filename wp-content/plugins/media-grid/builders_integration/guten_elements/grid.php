<?php
// REGISTER GRID BLOCK



// grids array
$grids_arr = array(); 
foreach(get_terms('mg_grids', array('hide_empty' => 0, 'orderby' => 'name')) as $grid) {
	$grids_arr[ $grid->term_id ] = $grid->name;
}


// pagination systems
$pag_sys = array(
	'' => __('default one', MG_ML)
);
foreach(mg_static::pag_layouts() as $type => $name) {
	$pag_sys[ $type ] = $name;
}


// MG item categories array (use full list for now)
$def_filter = array(
	'' => __('no initial filter', MG_ML)
); 
foreach(mg_static::item_cats() as $cat_id => $cat_name) {
	$def_filter[ $cat_id ] = $cat_name;
}




///// ADVANCED FILTERS ADD-ON //////////
////////////////////////////////////////

$filters = array(
	'0' => __('No'),
	'1' => __('Yes'),
);
if(class_exists('mgaf_static')) {
	$filters = array(
		'0' => __('No', MG_ML),
		'1' => __('Yes (MG categories)', MG_ML),
	) + mgaf_static::filters_list();
}



///// OVERLAY MANAGER ADD-ON ///////////
////////////////////////////////////////


$overlays = array(
	__('default one', MG_ML) => ''
);

if(defined('MGOM_DIR')) {	
	register_taxonomy_mgom(); // be sure tax are registered
	$overlay_terms = get_terms('mgom_overlays', 'hide_empty=0');
	
	foreach($overlay_terms as $ol) {
		$overlays[ $ol->term_id ] = $ol->name;	
	}
}



/////////////////////////////////////////////


$panels = array(
	'main' => array(
		'title' 	=> __('Main parameters', MG_ML),
		'opened' 	=> true
	),
	'styling' => array(
		'title' 	=> __('Custom styles', MG_ML),
		'opened' 	=> false
	)
);


// structure
$defaults = array(
	'gid' => array(
		'label'		=> __('Grid', MG_ML),
		'type'		=> 'select',
		'opts'		=> $grids_arr,
		'default' 	=> current(array_keys($grids_arr)),
		'panel'		=> 'main',
	),
	'pag_sys' => array(
		'label'		=> __('Pagination system', MG_ML),
		'type'		=> 'select',
		'opts'		=> $pag_sys,
		'default' 	=> '',
		'panel'		=> 'main',
	),
	'filter' => array(
		'label'		=> __('Enable filters?', MG_ML),
		'type'		=> 'select',
		'opts'		=> $filters,
		'default' 	=> current(array_keys($filters)),
		'panel'		=> 'main',
	),
	'filters_align' => array(
		'label'		=> __('Filters position', MG_ML),
		'type'		=> 'select',
		'opts'		=> array(
			'top' 	=> __('On top', MG_ML),
			'left'	=> __('Left side', MG_ML),
			'right' => __('Right side', MG_ML)
		),
		'default' 	=> 'top',
		'panel'		=> 'main',
		
		'condition' => array(
			'filter' => array(
				'=', 
				array('1')
			)
		)
	),
	'def_filter' => array(
		'label'		=> __('Default filter', MG_ML),
		'type'		=> 'select',
		'opts'		=> $def_filter,
		'default' => current(array_keys($def_filter)),
		'panel'		=> 'main',
		
		'condition' => array(
			'filter' => array(
				'=', 
				array('1')
			)
		)
	),
	'search' => array(
		'label'		=> __('Enable search?', MG_ML),
		'type'		=> 'checkbox',
		'default' 	=> '',
		'panel'		=> 'main',
		
		'condition' => array(
			'filter' => array(
				'=', 
				array('0', '1')
			)
		)
	),
	'hide_all' => array(
		'label'		=> __('Hide "All" filter?', MG_ML),
		'type'		=> 'checkbox',
		'default' 	=> '',
		'panel'		=> 'main',
		
		'condition' => array(
			'filter' => array(
				'=', 
				array('1')
			)
		)
	),
	'overlay' => array(
		'label'		=> __('Overlay', MG_ML),
		'type'		=> 'select',
		'opts'		=> $overlays,
		'default' => current(array_keys($overlays)),
		'panel'		=> 'main',
	),
	'title_under' => array(
		'label'		=> __('Text under items?', MG_ML),
		'type'		=> 'select',
		'opts'		=> array(
			0 => __('No', MG_ML),
			1 => __('Yes - attached to item', MG_ML),
			2 => __('Yes - detached from item', MG_ML)
		),
		'default' 	=> 0,
		'panel'		=> 'main',
	),
    'mf_lightbox' => array(
		'label'		=> __('Media-focused lightbox mode?', MG_ML),
		'type'		=> 'select',
		'opts'		=> array(
			'' => __('as default', MG_ML),
			0  => __('No'),
			1  => __('Yes')
		),
		'default' 	=> '',
		'panel'		=> 'main',
	),
	'mobile_tresh' => array(
		'label'		=> __('Custom mobile threshold', MG_ML),
		'help'		=> __('Overrides global treshold. Use zero to ignore', MG_ML),
		'type'		=> 'slider',
		'min'		=> 0,
		'max'		=> 1000,
		'default' 	=> 0,
		'panel'		=> 'main',
	),
	
	
	'warning1' => array(
		'type'		=> 'warning',
		'html'		=> __('leave empty textual fields to use global values', MG_ML),
		'panel'		=> 'styling',
		'default' 	=> '',
	),
	'cell_margin' => array(
		'label'		=> __('Items margin', MG_ML) .' (px)',
		'type'		=> 'text', // can't use number + empty values because of the fu**ing Guten
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'border_w' => array(
		'label'		=> __('Item borders width', MG_ML) .' (px)',
		'type'		=> 'text',
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'border_col' => array(
		'label'		=> __('Item borders color', MG_ML),
		'type'		=> 'colorpicker',
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'border_rad' => array(
		'label'		=> __('Items border radius', MG_ML) .' (px)',
		'type'		=> 'text',
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'outline' => array(
		'label'		=> __('Display items outline?', MG_ML),
		'type'		=> 'select',
		'opts'		=> array(
			'' => __('as default', MG_ML),
			0  => __('No'),
			1  => __('Yes')
		),
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'outline_col' => array(
		'label'		=> __('Outline color', MG_ML),
		'type'		=> 'colorpicker',
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'shadow' => array(
		'label'		=> __('Display items shadow?', MG_ML),
		'type'		=> 'select',
		'opts'		=> array(
			'' => __('as default', MG_ML),
			0  => __('No'),
			1  => __('Yes')
		),
		'default' 	=> '',
		'panel'		=> 'styling',
	),
	'txt_under_col' => array(
		'label'		=> __('Text under images color', MG_ML),
		'type'		=> 'colorpicker',
		'default' 	=> '',
		'panel'		=> 'styling',
	),
);
register_block_type('lcweb/media-grid', array(
	'editor_script' 	=> 'mg_on_guten',
	'render_callback' 	=> 'mg_guten_handler',
	'attributes' 		=> $defaults
));





wp_localize_script('wp-blocks', 'mg_panels', $panels);
wp_localize_script('wp-blocks', 'mg_defaults', $defaults);
