<?php
/**
 * Shreya WP MVC (MVC Framework for Wordpress)
 * This plugin file is the entrypoint to Shreya Wordpress MVC Framework
 * @link 		https://github.com/nirajkvinit/shreya_wpmvc
 * @since 		0.0.2
 * @package 	shreya_wpmvc
 * 
 * @wordpress-plugin
 * Plugin Name:       Shreya WPMVC
 * Plugin URI:        https://github.com/nirajkvinit/shreya_wpmvc
 * Description:       OOPs MVC framework for Wordpress with custom Routing, Models, Views and Controllers 
 * Version:           0.0.1
 * Author:            Niraj Kumar
 * Author URI:        https://github.com/nirajkvinit 
 * Text Domain:       shreya_wpmvc
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
//////////////////////////////////////Constants Section Starts//////////////////////////////
/**
 * SWPMVC Plugin Directory Path Constant
 */
define( "SWPMVC_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );

/**
 * Bower Components Dir Constant
 */
define( "BOWER_ROOT_DIR", SWPMVC_PLUGIN_DIR . 'bower_components' );

/**
 * SWPMVC Plugin URL Constant
 */
define( "SWPMVC_PLUGIN_URL", plugins_url()."/shreya_wpmvc" );

/**
 * SWPMVC Assets Dir URL Constant
 */
define( "SWPMVC_ASSETS_URL", SWPMVC_PLUGIN_URL.'/assets' );

/**
 * Bower Components Dir URL Constant
 */
define( "BOWER_ROOT_URL", SWPMVC_PLUGIN_URL . '/bower_components' );

//////////////////////////////////////Constants Section Ends//////////////////////////////

include_once( SWPMVC_PLUGIN_DIR . 'includes/includes.php' );

/**
 * Set Default Timezone to website's default Timezone for better date/time calculations.
 */
date_default_timezone_set( swpmvc_get_wordpress_timezone_string() );

/**
 * Search and Load PHP Files automatically based on classes name
 */
spl_autoload_register( 'swpmvc_autoload_classes' );


/*****************************************************************************
 *****************************************************************************
 *                    Website Virtual Pages creator (Routes)
 *****************************************************************************
 *****************************************************************************/
/**
 * Application Endpoints (It can be modified)
 * Application would load as www.example.com/apps/...
 */
define( "SWPMVC_ENDPOINT_URL", '/' );

/**
 * Create Application's Routes
 */
$swpmvc_router = new SwpmvcRouter();
unset( $swpmvc_router ); //Clear garbage if any

/**
 * Create Ajax Handler for The Application
 * @todo elaborate and implement this properly
 */
$swpmvc_ajax_handler = new ControllerSwpmvcAjax();