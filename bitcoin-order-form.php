<?php
/*
Plugin Name: bitcoin order form for MapsMarker.com
Plugin URI: http://www.mapsmarker.com/bitcoin
Description: Adds an order form for <a href="http://www.mapsmarker.com/bitcoin" target="_blank">Maps Marker Pro</a> and a live currency converter for EUR to bitcoins. See a live demo at <a href="http://www.mapsmarker.com/bitcoin" target="_blank">http://www.mapsmarker.com/bitcoin</a> - based on <a href="http://wordpress.org/plugins/bitcoin-currency-converter/" target="_blank">Bitcoin Calculator Widget</a> by Richard Macarthy, <a href="http://www.richardmacarthy.com/" target="_blank">http://www.richardmacarthy.com/</a>
Author: Robert Harm
Version: 1.0
Author URI: http://www.harm.co.at/
*/

//info: prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'bitcoin-order-form.php') { die ("Please do not access this file directly. Thanks!<br/><a href='http://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }

//info: set constants
if ( ! defined( 'BITCOIN_PLUGIN_URL' ) )
	define ("BITCOIN_PLUGIN_URL", plugin_dir_url(__FILE__));

include('lib/order-form.php'); 

//info: register shortcode
function bitcoin_currency_calculator_fn( $attributes ) {
	return bitcoin_calculator_widget();
}
add_shortcode( 'bitcoin-order-form','bitcoin_currency_calculator_fn' );

//info: load jquery
function bitcoin_jquery_frontend() {
	wp_enqueue_script( array ( 'jquery' ) );
}
add_action('wp_enqueue_scripts', 'bitcoin_jquery_frontend');

//info: conditionally load css
function bitcoin_conditional_css() {
	global $wp_query, $wp_version;
	$posts = $wp_query->posts;
	$pattern = get_shortcode_regex();
	
	wp_register_style('bitcoin-order-form-css', BITCOIN_PLUGIN_URL . 'css/bitcoin-order-form.css');

	if (is_array($posts)) {
		foreach ($posts as $post) {
			if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'bitcoin-order-form', $matches[2] ) ) {
				wp_enqueue_style('bitcoin-order-form-css');
				break;
			}
		}
	}
}
add_action('wp_print_styles', 'bitcoin_conditional_css');
?>