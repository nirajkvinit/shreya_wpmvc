<?php
	 // If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	
/**
 * Assets Loader for the Pedagoge Web Application
 */
class SwpmvcAssetsLoader {
	
	private $loaded_styles = null;
	private $loaded_scripts_in_header = null;
	private $loaded_scripts_in_footer = null;
	
	
	private $registered_styles = null;
	private $registered_scripts = null;
	
	public function __construct() {
		$this->loaded_styles = array();
		$this->loaded_scripts_in_header = array();
		$this->loaded_scripts_in_footer = array();
		
		$this->registered_scripts = array();
		$this->registered_styles = array();
		$this->fn_register_scripts();
		$this->fn_register_styles();
	}

	private function fn_register_scripts() {
		
		$scripts_list = array();
		
		// Bower Components			
		
		$scripts_list[] = "jquery.min#".BOWER_ROOT_URL."/jquery/dist/jquery.min.js"; //https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js
		$scripts_list[] = "jquery#".BOWER_ROOT_URL."/jquery/dist/jquery.js"; //https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js
		
		$scripts_list[] = "jquery-ui#".BOWER_ROOT_URL."/jquery-ui/jquery-ui.min.js";		
		$scripts_list[] = "bootstrap#".BOWER_ROOT_URL."/bootstrap/dist/js/bootstrap.min.js";		
		
		//insert styles into registered styles array
		foreach( $scripts_list as $scripts_string ) {
			$string_list_arr = explode( "#", $scripts_string );				
			$handle = $string_list_arr[0];
			$location = $string_list_arr[1];
			$this->registered_scripts[ $handle ] = $location;				
		}
	}
	
	private function fn_register_styles() {
		$styles_list = array();
		$styles_list[] = "google_opensans#http://fonts.googleapis.com/css?family=Open+Sans:400,300,600";		
		// Bower Components
		$styles_list[] = "jqueryui#".BOWER_ROOT_URL."/jquery-ui/themes/base/jquery-ui.min.css";		
		$styles_list[] = "bootstrap#".BOWER_ROOT_URL."/bootstrap/dist/css/bootstrap.min.css";
		$styles_list[] = "fontawesome#".BOWER_ROOT_URL."/font-awesome/css/font-awesome.min.css";	
				
		//insert styles into registered styles array
		foreach( $styles_list as $style_string ) {
			$string_list_arr = explode( "#", $style_string );
			$handle = $string_list_arr[0];
			$location = $string_list_arr[1];
			$this->registered_styles[ $handle ] = $location;				
		}
	}
	
	public function fn_load_scripts() {
		$assets_array = array();
		$assets_array[ 'css' ] = $this->registered_styles;
		$assets_array[ 'js' ] = $this->registered_scripts;			
		
		return $assets_array;				
	}		
} // Class ends here
