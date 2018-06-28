<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}
flush_rewrite_rules( false );
Timber::$dirname = array('templates', 'views');
require get_template_directory() . '/inc/version.php';
global $package_version;

class LaunchframeSite extends TimberSite {
	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'title-tag' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		// add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
    	add_action('wp_enqueue_scripts', array( $this, 'lf_cleanup'));
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_stylesheets' ) );
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		parent::__construct();
	}
	function lf_cleanup() {
	// wp_deregister_script('jquery');
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}
	function register_stylesheets() {
	  global $package_version;
	  wp_enqueue_style( 'application-style', get_template_directory_uri() . '/assets/dist/css/application.min.css', true, $package_version );
	}
	function register_scripts() {
	  global $package_version;
	  wp_enqueue_script( 'application-js', get_template_directory_uri() . '/assets/dist/js/script.min.js#async', array('jquery'), $package_version, true );
	}
	function register_post_types() {
		//this is where you can register custom post types

		//Let's make one for Cities!
		$labels = array(
			'name'               => 'Cities',
			'singular_name'      => 'City',
			'menu_name'          => 'City',
			'name_admin_bar'     => 'City',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New City',
			'new_item'           => 'New City',
			'view_item'          => 'View City',
			'all_items'          => 'All Cities',
			'search_items'       => 'Search Cities',			
			'not_found'          => 'No cities found.',
			'not_found_in_trash' => 'No cities found in Trash.'
		);
	
		$args = array( 
			'public'      => true, 
			'labels'      => $labels,
			'has_archive' => true
		);
			register_post_type( 'city', $args );
	}
	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}
	function add_to_context( $context ) {
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}
	function add_to_twig( $twig ) {
		/* this is where you can add your own fuctions to twig */
		//$twig->addExtension( new Twig_Extension_StringLoader() );
		//$twig->addFilter( 'myfoo', new Twig_Filter_Function( 'myfoo' ) );
		//return $twig;
	}

	
}

function my_acf_google_map_api( $api ){
	
	$api['key'] = 'AIzaSyC3VA9YTTl1o20XkmCy9IovzmW-mVDGRkM';
	
	return $api;
	
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

include('inc/utility-functions.php');

new LaunchframeSite();
