<?php
// DEFINING MODULE STRUCTURE AND FIELDS


class mg_divi_module extends ET_Builder_Module {

	public $slug       = 'lcmg';
	public $vb_support = 'on';

    
	protected $module_credits = array(
        'module_uri' => 'https://lcweb.it/media-grid',
        'author'     => 'LCweb',
        'author_uri' => 'https://lcweb.it/',
	);

    
    public function get_advanced_fields_config() {
        return unserialize(LC_DIVI_DEF_OPTS_OVERRIDE);
	}

    
	public function init() {
		$this->name               = 'Media Grid';
		$this->icon_path          = $GLOBALS['mg_divi_icon_path'];
		$this->main_css_element   = '%%order_class%%';	
        
        $this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'main'     => esc_html__('Main Options', MG_ML),
                    'styling'  => esc_html__('Styling', MG_ML),
				),
			),
		);
	}
 
    
	public function get_fields() {
        $letud = esc_html__('Leave empty to use default one', MG_ML);
        
        $fields = array(
            'gid' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Grid', MG_ML),
				'type'            => 'select',
                'default'         => 'unset',
				'default_on_front'=> 'unset',
				'options'         => array('unset' => esc_html__('(choose a grid)', MG_ML)) + $GLOBALS['mg_divi_grids'],
				//'description'     => esc_html__( 'Choose whether your linklink opens in a new window or not', 'dicm-divi-custom-modules' ),
			),
            'pag_sys' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Pagination system', MG_ML),
				'type'            => 'select',
                'default'         => current(array_keys($GLOBALS['mg_divi_pag_sys'])),
				'default_on_front'=> current(array_keys($GLOBALS['mg_divi_pag_sys'])),
				'options'         => $GLOBALS['mg_divi_pag_sys'],
			),	
            'filter' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Enable filters?', MG_ML),
				'type'            => 'select',
                'default'         => current(array_keys($GLOBALS['mg_divi_filters'])),
				'default_on_front'=> current(array_keys($GLOBALS['mg_divi_filters'])),
				'options'         => $GLOBALS['mg_divi_filters'],
			),	
            'filters_align' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Filters position', MG_ML),
				'type'            => 'select',
                'default'         => 'top',
				'default_on_front'=> 'top',
				'options'         => array(
                    'top' 	=> __('On top', MG_ML),
                    'left'	=> __('Left side', MG_ML),
                    'right' => __('Right side', MG_ML)
                ),
			),	
            'def_filter' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Default filter', MG_ML),
				'type'            => 'select',
                'default'         => current(array_keys($GLOBALS['mg_divi_def_filter'])),
				'default_on_front'=> current(array_keys($GLOBALS['mg_divi_def_filter'])),
				'options'         => $GLOBALS['mg_divi_def_filter'],
                'description'     => (class_exists('mgaf_static')) ? esc_html__('(only for default categories filter)', MG_ML) : '',
			),	
            'search' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Enable search?', MG_ML),
				'type'            => 'yes_no_button',
                'default'         => 'off',
				'default_on_front'=> 'off',		
				'options'         => array(
					'off' => esc_html__('No', MG_ML),
					'on'  => esc_html__('Yes', MG_ML),
				),
			),	
            'hide_all' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Hide "All" filter?', MG_ML),
				'type'            => 'yes_no_button',
                'default'         => 'off',
				'default_on_front'=> 'off',		
				'options'         => array(
					'off' => esc_html__('No', MG_ML),
					'on'  => esc_html__('Yes', MG_ML),
				),
                'description'     => (class_exists('mgaf_static')) ? esc_html__('(only for default categories filter)', MG_ML) : '',
			),	
            'overlay' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Overlay', MG_ML),
				'type'            => 'select',
                'default'         => current(array_keys($GLOBALS['mg_divi_overlays'])),
				'default_on_front'=> current(array_keys($GLOBALS['mg_divi_overlays'])),
				'options'         => $GLOBALS['mg_divi_overlays'],
			),	
            'title_under' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Text under items?', MG_ML),
				'type'            => 'select',
                'default'         => 'unset',
				'default_on_front'=> 'unset',
				'options'         => array(
                    'unset' => __('No', MG_ML),
                    '1'     => __('Yes - attached to item', MG_ML),
                    '2'     => __('Yes - detached from item', MG_ML)
                ),
			),	
            'mf_lightbox' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Media-focused lightbox mode?', MG_ML),
				'type'            => 'select',
                'default'         => '',
				'default_on_front'=> '',
				'options'         => array(
                    'unset' => __('as default', MG_ML),
                    '0'     => __('No'),
                    '1'     => __('Yes')
                ),
			),	
            'mobile_tresh' => array(
                'toggle_slug'     => 'main',
				'label'           => esc_html__('Custom mobile threshold', MG_ML),
				'type'            => 'range',
                'default'         => 0,
				'default_on_front'=> 0,
				'range_settings'    => array(
					'min'   => 0,
					'max'   => 1000,
                    'step'  => 1
				),
                'validate_unit' => true,
                'description'   => esc_html__('Overrides global threshold. Use zero to ignore', MG_ML),
			),
            
            
            //////////////////
            
            
            'cell_margin' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__("Items margin", MG_ML) .' (px)',
				'type'            => 'text',
                'default'         => '',
				'default_on_front'=> '',
                'description'     => $letud,
			),
            'border_w' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__("Item borders width", MG_ML) .' (px)',
				'type'            => 'text',
                'default'         => '',
				'default_on_front'=> '',
                'description'     => $letud,
			),
            'border_col' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__("Item borders color", MG_ML),
				'type'            => 'color',
                'default'         => '',
				'default_on_front'=> '',
                'description'     => $letud,
			),
            'border_rad' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__("Items border radius", MG_ML) .' (px)',
				'type'            => 'text',
                'default'         => '',
				'default_on_front'=> '',
                'description'     => $letud,
			),
            'outline' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__('Display items outline?', MG_ML),
				'type'            => 'select',
                'default'         => '',
				'default_on_front'=> '',
				'options'         => array(
                    'unset' => __('as default', MG_ML),
                    '0'     => __('No'),
                    '1'     => __('Yes')
                ),
			),	
            'outline_col' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__('Outline color', MG_ML),
				'type'            => 'color',
                'default'         => '',
				'default_on_front'=> '',
                'description'     => $letud,
			),
            'shadow' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__('Display items shadow?', MG_ML),
				'type'            => 'select',
                'default'         => '',
				'default_on_front'=> '',
				'options'         => array(
                    'unset' => __('as default', MG_ML),
                    '0'     => __('No'),
                    '1'     => __('Yes')
                ),
			),	
            'txt_under_col' => array(
                'toggle_slug'     => 'styling',
				'label'           => esc_html__('Text under images color', MG_ML),
				'type'            => 'color',
                'default'         => '',
				'default_on_front'=> '',
                'description'     => $letud,
			),
		);
        
        
        $GLOBALS[ $this->slug .'_divi_field_indexes'] = array_keys($fields);
        return $fields;
	}


    
    public function render($attrs, $content = null, $render_slug) {
        return mg_divi_modules::front_shortcode_render($this->slug, $this->props);  
	}
}

new mg_divi_module;