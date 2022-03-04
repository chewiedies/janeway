<?php

// MENUS
function _custom_theme_register_menu() {
    register_nav_menus(
        array(
            'menu-main' => __( 'Menu principal' ),
            //'menu-footer' => __( 'Menu footer' ),
        )
    );
}
add_action( 'init', '_custom_theme_register_menu' );


if ( ! function_exists( 'janeway_menu_main' ) ) {
	function janeway_menu_main() {
		wp_nav_menu( array(
			'container'      => false,
			// 'container_id'   => 'navbarMenuMain',
			// 'container_class'=> 'collapse navbar-collapse',
			'menu_class'     => '',
			'items_wrap'     => '<ul id="%1$s" class="navbar-nav %2$s">%3$s</ul>',
			'theme_location' => 'menu-main',
			'depth'          => 3,
			'fallback_cb'    => false,
			'walker'         => new Janeway_Menu_Main_Walker(),
		));
	}
}


if ( ! class_exists( 'Janeway_Menu_Main_Walker' ) ) :
	class Janeway_Menu_Main_Walker extends Walker_Nav_Menu {
		// bootstrap 5 wp_nav_menu walker
		private $current_item;
		private $dropdown_menu_alignment_values = [
			'dropdown-menu-start',
			'dropdown-menu-end',
			'dropdown-menu-sm-start',
			'dropdown-menu-sm-end',
			'dropdown-menu-md-start',
			'dropdown-menu-md-end',
			'dropdown-menu-lg-start',
			'dropdown-menu-lg-end',
			'dropdown-menu-xl-start',
			'dropdown-menu-xl-end',
			'dropdown-menu-xxl-start',
			'dropdown-menu-xxl-end'
		];

		function start_lvl(&$output, $depth = 0, $args = null)
		{
			$dropdown_menu_class[] = '';
			foreach($this->current_item->classes as $class) {
				if(in_array($class, $this->dropdown_menu_alignment_values)) {
					$dropdown_menu_class[] = $class;
				}
			}
			$indent = str_repeat("\t", $depth);
			$submenu = ($depth > 0) ? ' sub-menu' : '';
			$subsubmenu = ($depth >= 2) ? ' sub-sub-menu' : '';
			$arialabel = ($depth > 0) ? 'dropdownMenuButton' . $item->ID : '';
			$output .= "\n$indent<ul aria-labelledby=\"$arialabel\" class=\"dropdown-menu$submenu$subsubmenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
		}

		function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
		{
			$this->current_item = $item;

			$indent = ($depth) ? str_repeat("\t", $depth) : '';

			$li_attributes = '';
			$class_names = $value = '';

			$classes = empty($item->classes) ? array() : (array) $item->classes;

			$classes[] = ($args->walker->has_children) ? 'dropdown' : '';
			$classes[] = 'nav-item';
			$classes[] = 'nav-item-' . $item->ID;
			if ($depth && $args->walker->has_children) {
				// $classes[] = 'dropdown-menu dropdown-menu-end';
				$classes[] = 'dropdown-menu-end';
			}

			$class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
			$class_names = ' class="' . esc_attr($class_names) . '"';

			$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
			$id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

			$output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

			$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
			$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
			$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
			$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

			$active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
			$nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
			$attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . '"' : ' class="'. $nav_link_class . $active_class . '"';
			$dropdown_toggler = ( ($args->walker->has_children) && ($depth <= 0) ) ? '<button type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' : '';
			$subdropdown_toggler = ( ($args->walker->has_children) && ($depth > 0) ) ? '<button type="button" class="dropdown-toggle" id="dropdownMenuButton' . $item->ID . '"></button>' : '';


			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $dropdown_toggler;
			// $item_output .= $subdropdown_toggler;
			$item_output .= $args->after;

			$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		}
	}
endif;


function menu_set_dropdown( $sorted_menu_items, $args ) {
	$last_top = 0;
	foreach ( $sorted_menu_items as $key => $obj ) {
			// it is a top lv item?
			if ( 0 == $obj->menu_item_parent ) {
					// set the key of the parent
					$last_top = $key;
			} else {
					$sorted_menu_items[$last_top]->classes['dropdowno'] = 'dropdown';
			}
	}
	return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'menu_set_dropdown', 10, 2 );

function custom_setup() {
	// IMAGES
	add_theme_support( 'post-thumbnails' );

	// TITLE TAGS
	add_theme_support('title-tag');

	// LANGUAGES
	load_theme_textdomain('textdomaintomodify', get_template_directory() . '/languages');

	// HTML 5 - Example : deletes type="*" in scripts and style tags
	add_theme_support( 'html5', [ 'script', 'style' ] );

	// REMOVE USELESS WP IMAGE SIZES
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );

	// CUSTOM IMAGE SIZES
	// add_image_size( '424x424', 424, 424, true );
	// add_image_size( '1920', 1920, 9999 );
}
add_action('after_setup_theme', 'custom_setup');

// remove default image sizes to avoid overcharging server - comment line if you need size
function remove_default_image_sizes( $sizes) {
	unset( $sizes['large']);
	unset( $sizes['medium']);
	unset( $sizes['medium_large']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'remove_default_image_sizes');

// disabling big image sizes scaled
add_filter( 'big_image_size_threshold', '__return_false' );

// Giving credits
function remove_footer_admin () {
	echo 'Thème crée par <a href="http://www.olivier-guilleux.com" target="_blank">Olivier Guilleux</a>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

// Move Yoast to bottom
function yoasttobottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

// Remove WP Emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// delete wp-embed.js from footer
function my_deregister_scripts() {
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// delete jquery migrate
function dequeue_jquery_migrate( &$scripts){
	if(!is_admin()){
		$scripts->remove( 'jquery');
		$scripts->add('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', null, null, true );
	}
}
add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );

// force all scripts to load in footer
function clean_header() {
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
}
add_action('wp_enqueue_scripts', 'clean_header');

// add SVG to allowed file uploads
function add_file_types_to_uploads($mime_types) {
	$mime_types['svg'] = 'image/svg+xml';

	return $mime_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads', 1, 1);
