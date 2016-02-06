<?php
/**
 * Shreya WP MVC (MVC Framework for Wordpress)
 * This plugin file is the entrypoint to Shreya Wordpress MVC Framework
 * @link 		https://github.com/nirajkvinit/shreya_wpmvc
 * @since 		0.0.1
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

/**
 * SWPMVC Plugin URL Constant
 */
define( "SWPMVC_PLUGIN_URL", plugins_url()."/shreya_wpmvc" );

/**
 * SWPMVC Assets Dir URL Constant
 */
define( "SWPMVC_ASSETS_URL", SWPMVC_PLUGIN_URL.'/assets' );

/**
 * SWPMVC Plugin Directory Path Constant
 */
define( "SWPMVC_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );


/**
 * SWPMVC Application Endpoint (It can be modified)
 * Application would load as www.example.com/apps/...
 * 
 * @todo Load this from wordpress options (Create a Plugin Settings Page in Wordpress Control Panel) 
 * This plugin settings page will store the application configurations from wordpress options table.
 * Store everything in json. Also provide another option to save configurations in a XML configuration file.
 * Try the logger function.
 */
define("SWPMVC_APPS_URL", '/swpmvc');
