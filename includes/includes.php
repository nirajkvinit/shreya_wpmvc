<?php
 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/***************************Includes Section******************************/
include_once( SWPMVC_PLUGIN_DIR.'vendor/autoload.php' ); //Composer Autoloader

include_once( SWPMVC_PLUGIN_DIR.'includes/helpers/SwpmvcLogWriter.php' );
//include helper utilities file
include_once( SWPMVC_PLUGIN_DIR.'includes/helpers/utilities.php' );

//include virtual page constructors
include_once( SWPMVC_PLUGIN_DIR.'includes/swpmvc_virtual_pages/swpmvc_virtual_page_constructor.php' );
include_once( SWPMVC_PLUGIN_DIR.'includes/SwpmvcRouter.php' );
