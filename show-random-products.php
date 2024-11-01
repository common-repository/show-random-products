<?php

/*
 * Plugin Name: Show Random Products
 * Plugin URI: http://wordpress.org/extend/plugins/jetpack/
 * Description: A widget + shortcode to show random products in your store
 * Author: Alan Cesarini
 * Version: 1.0.0
 * Author URI: http://alancesarini.com
 * License: GPL2+
 */

class Show_Random_Products {

	private static $_this;

	private static $_version;

	private static $random;

	function __construct() {
	
		if( isset( self::$_this ) )
			wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.', get_class( $this ) ) );
		self::$_this = $this;

		self::$_version = '1.0.0';

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		    add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
			add_shortcode( 'srp_random', array( $this, 'render_random_products' ) );
			include( 'includes/class-random.php' );
			self::$random = new SRP_Random();
			include( 'includes/widget-random.php' );			
		}		

	}
	
	static function this() {
	
		return self::$_this;
	
	}

	function render_random_products( $atts ) {

	    $a = shortcode_atts( array(
	        'n' => 1,
	        'cols' => 1,
	        'cats' => '',
	        'tags' => ''
	    ), $atts );

	    self::$random->render( $a );

	}

	function load_assets() {

		wp_register_style( 'srp-style', plugins_url( 'assets/css/main.css', __FILE__ ), false, self::$_version );
		wp_enqueue_style( 'srp-style' );

	}
	
}

new Show_Random_Products();
