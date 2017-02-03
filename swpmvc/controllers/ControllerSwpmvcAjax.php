<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * ControllerSwpmvcAjax Controller class
 *
 * 
 * @package swpmvc_plugin
 * @version 0.2
 * @author Niraj Kumar
 * @todo 
 * 		Check for nonces here. 
 * 		Include and check for registered and visitor for all the ajax calls.
 * 		If there are any security related issues then return proper http code. 
 * 		Return JSON response codes
 */

class ControllerAjax {
	
	public function __construct() {
		add_action( 'wp_ajax_swpmvc_users_ajax_handler', array( $this, 'swpmvc_users_ajax_handler' ) );
		add_action( 'wp_ajax_swpmvc_visitor_ajax_handler', array( $this, 'swpmvc_visitor_ajax_handler' ) );
		add_action( 'wp_ajax_nopriv_swpmvc_visitor_ajax_handler', array( $this, 'swpmvc_visitor_ajax_handler' ) );
	}
	
	public function swpmvc_users_ajax_handler() {
		//Check for nonces
		// check for loggedin users
		//@todo Visitor needs to be logged-in to continue.
		$this->ajax_handler();
	}
	
	public function swpmvc_visitor_ajax_handler() {
		$this->ajax_handler();
	}
	
	private function ajax_handler() {
		//@todo implement and process requests middlewares
		if(isset($_REQUEST['swpmvc_callback_function']) && isset($_REQUEST['swpmvc_callback_class'])) {
			$callback_function = $_REQUEST['swpmvc_callback_function'];
			$callback_class = $_REQUEST['swpmvc_callback_class'];
			$callback_class::$callback_function();
		} else {			
			//@todo implement and send 403 Forbidden request
			die();
		}
	}
}
