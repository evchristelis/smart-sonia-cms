<?php
// declaring menu, custom post type and taxonomy

///////////////////////////////////
// SETTINGS PAGE

function mg_settings_page() {	
	add_submenu_page('edit.php?post_type=mg_items', __('Grid Builder', MG_ML), __('Grid Builder', MG_ML), 'upload_files', 'mg_builder', 'mg_builder');	
	add_submenu_page('edit.php?post_type=mg_items', __('Settings', MG_ML), __('Settings', MG_ML), 'install_plugins', 'mg_settings', 'mg_settings');	
	
	// add-ons sponsor!
	if(!isset($GLOBALS['is_mg_bundle'])) {
		$remaining = mg_static::addons_not_installed();
		if(!empty($remaining)) {
			
			$txt = '<strong class="mg_getaddons_menu"><span class="dashicons dashicons-star-filled"></span> '. __('Get Add-ons!', MG_ML) .'</strong>';
			add_submenu_page('edit.php?post_type=mg_items',$txt , $txt, 'upload_files', 'upload_files', 'mg_addons_adv');	
		}
	}
	
}
add_action('admin_menu', 'mg_settings_page');


function mg_builder() {
	include_once(MG_DIR .'/grid_builder.php');	
}
function mg_settings() {
	include_once(MG_DIR. '/settings/view.php');
}
function mg_addons_adv() {
	include_once(MG_DIR.'/addons_adv.php');
} 





//////////////////////
// GRID TAXONOMY

add_action( 'init', 'register_taxonomy_mg_grids', 1);
function register_taxonomy_mg_grids() {
    $labels = array( 
        'name' => __( 'Grids', MG_ML),
        'singular_name' => __( 'Grid', MG_ML),
        'search_items' => __( 'Search Grids', MG_ML),
        'popular_items' => __( 'Popular Grids', MG_ML),
        'all_items' => __( 'All Grids', MG_ML),
        'parent_item' => __( 'Parent Grid', MG_ML),
        'parent_item_colon' => __( 'Parent Grid:', MG_ML),
        'edit_item' => __( 'Edit Grid', MG_ML),
        'update_item' => __( 'Update Grid', MG_ML),
        'add_new_item' => __( 'Add New Grid', MG_ML),
        'new_item_name' => __( 'New Grid', MG_ML),
        'separate_items_with_commas' => __( 'Separate grids with commas', MG_ML),
        'add_or_remove_items' => __( 'Add or remove Grids', MG_ML),
        'choose_from_most_used' => __( 'Choose from most used Grids', MG_ML),
        'menu_name' => __( 'Grids', MG_ML),
    );

    $args = array( 
        'labels' => $labels,
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => false,
        'show_tagcloud' => false,
        'hierarchical' => false,
        'rewrite' => false,
        'query_var' => true
    );

    register_taxonomy( 'mg_grids', null, $args );
}





////////////////////////////////
// ITEM CUSTOM POST TYPE 

add_action( 'init', 'register_cpt_mg_item' );
function register_cpt_mg_item() {

    $labels = array( 
        'name' => __( 'Items', MG_ML),
        'singular_name' => __( 'Item', MG_ML),
        'add_new' => __( 'Add New Item', MG_ML),
        'add_new_item' => __( 'Add New Item', MG_ML),
        'edit_item' => __( 'Edit Item', MG_ML),
        'new_item' => __( 'New Item', MG_ML),
        'view_item' => __( 'View Item', MG_ML),
        'search_items' => __( 'Search Items', MG_ML),
        'not_found' => __( 'No items found', MG_ML),
        'not_found_in_trash' => __( 'No items found in Trash', MG_ML),
        'parent_item_colon' => __( 'Parent Item:', MG_ML),
        'menu_name' => __( 'Media Grid', MG_ML),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,      
        'taxonomies' => (defined('MGAF_DIR')) ? array() : array('mg_item_categories'),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
		'menu_icon' => MG_URL . '/img/mg_icon_small.png',
        'menu_position' => 52,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
        'capability_type' => 'post'
    );
	
	if(defined('MGOM_DIR')) {
        $args['supports'][] = 'excerpt';
    } // OVERLAYS ADD-ON add excerpt
    
    register_post_type('mg_items', $args );	

	//////
	
	$labels = array( 
        'name' => __( 'Item Categories', MG_ML),
        'singular_name' => __( 'Item Category', MG_ML),
        'search_items' => __( 'Search Item Categories', MG_ML),
        'popular_items' => NULL,
        'all_items' => __( 'All Item Categories', MG_ML),
        'parent_item' => __( 'Parent Item Category', MG_ML),
        'parent_item_colon' => __( 'Parent Item Category:', MG_ML),
        'edit_item' => __( 'Edit Item Category', MG_ML),
        'update_item' => __( 'Update Item Category', MG_ML),
        'add_new_item' => __( 'Add New Item Category', MG_ML),
        'new_item_name' => __( 'New Item Category', MG_ML),
        'separate_items_with_commas' => __( 'Separate item categories with commas', MG_ML),
        'add_or_remove_items' => __( 'Add or remove Item Categories', MG_ML),
        'choose_from_most_used' => __( 'Choose from most used Item Categories', MG_ML),
        'menu_name' => __( 'Item Categories', MG_ML),
    );

    $args = array( 
        'labels' 			=> $labels,
        'public' 			=> false,
        'show_in_nav_menus' => false,
        'show_ui' 			=> true,
        'show_tagcloud'		=> false,
        'hierarchical' 		=> true,
        'rewrite' 			=> false,
        'query_var' 		=> true,
		'update_count_callback' => '_update_generic_term_count'
    );
	
	$assoc_with = (defined('MGAF_DIR')) ? array() : array('mg_items');
    register_taxonomy('mg_item_categories', $assoc_with, $args);
}





//////////////////////////////
// VIEW CUSTOMIZATORS

function mg_updated_messages( $messages ) {
  global $post;

  $messages['mg_items'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => __('Item updated', MG_ML),
    2 => __('Item updated', MG_ML),
    3 => __('Item deleted', MG_ML),
    4 => __('Item updated', MG_ML),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Item restored to revision from %s', MG_ML), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => __('Item published', MG_ML),
    7 => __('Item saved', MG_ML),
    8 => __('Item submitted', MG_ML),
    9 => sprintf( __('Item scheduled for: <strong>%1$s</strong>', MG_ML), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ))),
    10 => __('Item draft updated', MG_ML),
  );

  return $messages;
}
add_filter('post_updated_messages', 'mg_updated_messages');




// alterate item link to preview it in lightbox
function mg_change_item_link($permalink, $post) {
    if($post->post_type == 'mg_items') {
        $permalink = home_url('?mgi_='.$post->ID);
    }
    return $permalink;
}
add_filter('post_type_link', 'mg_change_item_link', 10, 2);




// customize the grid items custom post type table
add_filter('manage_edit-mg_items_columns', 'mg_edit_pt_table_head', 10, 2);
function mg_edit_pt_table_head($columns) {
	$new_cols = array();
	
	$new_cols['cb'] = '<input type="checkbox" />';
	$new_cols['title'] = __('Title', 'column name');
	
	$new_cols['mg_cat'] = __('Categories', MG_ML);
	$new_cols['mg_type'] = __('Type', MG_ML);
	$new_cols['mg_layout'] = __('Lightbox Layout', MG_ML);
	$new_cols['date'] = __('Date', 'column name');
	$new_cols['mg_thumb'] = __('Main Image', MG_ML);
	
	return array_merge($new_cols, $columns);
}


add_action('manage_mg_items_posts_custom_column', 'mg_edit_pt_table_body', 10, 2);
function mg_edit_pt_table_body($column_name, $id) {
	include_once(MG_DIR . '/classes/items_meta_fields.php');
	
	$item_type = get_post_meta($id, 'mg_main_type', true);
	
	switch ($column_name) {
		case 'mg_cat' :
			$cats = get_the_terms($id, 'mg_item_categories');
            if (is_array($cats)) {
				$item_cats = array();
				foreach($cats as $cat) { $item_cats[] = $cat->name;}
				echo implode(', ', $item_cats);
			}
			else {echo '';}
			break;

		case 'mg_type' :
			if($item_type) { echo mg_static::item_types($item_type); }
			else {echo '';}
			break;
			
		case 'mg_layout' :
			$imf = new mg_meta_fields($id, $item_type);
			
			if(in_array('mg_layout', $imf->type_fields() )) {
				
				// lightbox layout - replace SIDE with side_tripartite
				$val = get_post_meta($id, 'mg_layout', true);
				if($val == 'side') {$val = 'side_tripartite';}
				
				echo mg_static::lb_layouts($val);	
			} else {
				echo '';	
			}
			break;	
		
		case 'mg_thumb' :
			echo get_the_post_thumbnail($id, array(110, 110));
			break;
	
		default:
			break;
	}
	return true;
}


//////////////////////////////////////
// ENABLE CPT FILTER BY TAXONOMY

add_action('restrict_manage_posts','mg_items_filter_by_cat');
function mg_items_filter_by_cat() {
    global $typenow;
    global $wp_query;
	
    if ($typenow == 'mg_items') {
        $taxonomy = 'mg_item_categories';
		
		$sel = (isset($wp_query->query['mg_item_categories'])) ? $wp_query->query['mg_item_categories'] : ''; 
		
        wp_dropdown_categories(array(
            'show_option_all' =>  __("Any category", MG_ML),
            'taxonomy'        =>  $taxonomy,
            'name'            =>  'mg_item_categories',
            'orderby'         =>  'name',
            'selected'        =>  $sel,
            'hierarchical'    =>  false,
            'depth'           =>  1,
            'show_count'      =>  false,
            'hide_empty'      =>  true
        ));
    }
}

add_filter('parse_query', 'mg_cat_id_to_cat_term', 999);
function mg_cat_id_to_cat_term($query) {
	global $pagenow;
	
	$post_type = 'mg_items';
	$taxonomy  = 'mg_item_categories';
	
	$q_vars    = &$query->query_vars;
	if($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy]) {
		
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}



///////////////////////////////////////////////////////
// FIX FOR THEMES THAT DON'T SUPPOR FEATURED IMAGE

function mg_add_thumb_support() {
    $supportedTypes = (function_exists('get_theme_support')) ?  get_theme_support( 'post-thumbnails' ) : false;

	if($supportedTypes === false) {
		 add_theme_support( 'post-thumbnails', array( 'mg_items' ) ); 	
	}
    elseif( is_array( $supportedTypes ) ) {
        $supportedTypes[0][] = 'mg_items';
        add_theme_support( 'post-thumbnails', $supportedTypes[0] );
    }
}
add_action('admin_init', 'mg_add_thumb_support', 999);
