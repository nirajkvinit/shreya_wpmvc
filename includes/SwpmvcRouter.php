<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SwpmvcRouter {
	protected $pages_list = null;
	
	public function __construct() {
		
		$this->pages_list = array();
		$this->create_routes();
	}	
	
	public function fn_return_menu_list() {		
		return $this->pages_list;
	}
	
	public function fn_create_pages() {
		add_action( 'gm_virtual_pages', array( $this, 'swpmvc_page_factory' ) );
	}
	
	public function swpmvc_page_factory( $controller ) {
		$pages_list = $this->pages_list;	
		foreach( $pages_list as $page) {			
			$page_url = $page['url'];			
			$page_title = $page['title'];
			$controller->addPage( new \GM\VirtualPages\Page( $page_url ) )
		        ->setTitle( $page_title )
		        ->setTemplate( 'swpmvc_controller_loader.php' );
		}
	}
	
	public function fn_create_master_page() {		
		//Main Application Root Page
		$swpmvc_app_root_page = array();
		$swpmvc_app_root_page['url'] = SWPMVC_ENDPOINT_URL;
		$swpmvc_app_root_page['title'] = 'Welcome to SWPMVC';
		$this->pages_list[] = $swpmvc_app_root_page;
	}
	
	public function fn_create_dynamic_pages( $page_slug ) {
		$dynamic_page = array();
		$url = $page_slug;		
		$dynamic_page['url'] = $url;
		$dynamic_page['title'] = '404 Module Not Found!';
		$this->pages_list[] = $dynamic_page;		
	}
	
	/**
	 * Create other Application Virtual Pages after a defined route dynamically.
	 * 
	 * @param SwpmvcRouter $swpmvc_router (instance of SwpmvcRouter)
	 * @param string $endpoint  - defined route
	 * 
	 * @return void
	 */
	function create_routes() {	
		$routes = $this->get_routes();
		/**
		 * Current Page URL 
		 */
		$swpmvc_current_page_url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$wordpress_home_url = trim(home_url());
		
		/**
		 * Removing wordpress home url part from current url so that we can get the url path to work with.
		 * This technique had to be implemented because earlier implementation did not work for localhost.
		 */
		$swpmvc_replaced_url = str_replace($wordpress_home_url, '', $swpmvc_current_page_url);
		
		/**
		 * Seperate the url to path and query and just take url path.
		 */
		$swpmvc_processed_url_path = parse_url($swpmvc_replaced_url, PHP_URL_PATH);
		
		$route_found = FALSE;
		
		/**
		 * Search if current url starts with any defined route found in the routes array.
		 */
		foreach($routes as $route) {
			$swpmvc_app_url_position = strpos( $swpmvc_processed_url_path, $route );
			if($swpmvc_app_url_position !== false && $swpmvc_app_url_position == 0) {
				$route_found = TRUE;
				break;
			}
		}
		
		//if route is found then create the dynamic page.
		if($route_found) {
			$this->fn_create_dynamic_pages( $swpmvc_processed_url_path );
		}	
	}
	
	function get_routes() {
		$routes = array (
			'/workshop',
		);
		return $routes;
	}
}
