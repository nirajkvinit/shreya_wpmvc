<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $post, $wp_query, $current_user, $wpdb;

$swpmvc_current_page_url = trim( $wp_query->virtual_page->getUrl(), '/' );

$controller_class_name = fn_slug_to_controller_class( $swpmvc_current_page_url );

if ( class_exists( $controller_class_name ) ) {
	$controller_class = new $controller_class_name();
} else {
	//if controller does not exist then load 404		
	$controller_class = new Controller404();
}

$controller_class->fn_construct_class();

$str_return = $controller_class->fn_display();

unset( $controller_class );

echo $str_return;