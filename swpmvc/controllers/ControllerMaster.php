<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ControllerMaster {

	protected $title;
	protected $url;
	protected $slug;

	public $parent_title;

	protected $registered_css;
	protected $registered_js;

	/**
	 * Includes CSS files which will be loaded on every pages and generally do not need versioning.
	 * These will be combined and compressed in production.
	 */
	protected $common_css;

	/**
	 * Library Css Files which will be loaded on specific pages, these too do not need versioning.
	 * These will be compressed but not combined in production.
	 */
	protected $css_assets;

	/**
	 * Custom CSS related to a page. These will have versioning system and will never be compressed or combined with any other files.
	 */
	protected $app_css;

	/**
	 * Google Fonts will not have any versioning system.
	 */
	protected $google_fonts;

	/**
	 * Includes JS Files which needs to be included in the header section of an HTML page.
	 */
	protected $header_js;

	/**
	 * Library JS File which will be loaded on specific paged. No versioning required. Individual file compression but no combining.
	 */
	protected $footer_js;

	/**
	 * Includes JS Files which will be loaded on all the pages. Do not need versioning. All will be combined and compressed.
	 */
	protected $footer_common_js;

	/**
	 * Includes Page specific JS Files. Will be versioned but never compressed or combined with any other files.
	 */
	protected $app_js;

	protected $html_class;
	protected $body_class;

	/**
	 * All Library files which will update rarely will have this common version.
	 */
	protected $fixed_version;


	/**
	 * Name of the current Controller Class. Need it for ajax controller which Identifies which class to find and connect to.
	 * This variable is set by the Controller Classes and exposed as javascript variable.
	 */
	protected $swpmvc_callback_class;

	/**
	 * Application variables Container/Register. Views will get/extract data/variables from here.
	 */
	public $app_data;

	public function __construct( )	{
		global $post, $wp_query;

		$this->swpmvc_callback_class = get_class($this);

		$swpmvc_current_page_url = $wp_query->virtual_page->getUrl();
		$swpmvc_parsed_current_url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		//@todo improve this to handle multiple endpoints
		$swpmvc_current_page_slug = str_replace( SWPMVC_ENDPOINT_URL, '', $swpmvc_parsed_current_url_path );

		$this->slug = $swpmvc_current_page_slug;
		$this->url = $swpmvc_current_page_url;
		$this->parent_title = 'SWPMVC ';
		$this->title = $this->parent_title;

		//For versioning all the common styles and scripts.
		$this->fixed_version = '0.0.1';

		//CSS Classes
		$this->html_class = '';
		$this->body_class = '';

		//Registered CSS and JS Universal Container
		$this->registered_css = array();
		$this->registered_js = array();

		//Divide all registered CSS and JS into their own containers.
		//CSS Containers
		$this->common_css = array();
		$this->css_assets = array();
		$this->app_css = array();
		$this->google_fonts = array();

		//JS Containers
		$this->header_js = array();
		$this->footer_js = array();
		$this->footer_common_js = array();
		$this->app_js = array();

		//Application Supports two menu system. Header and Main. 
		$this->header_menu = array();
		$this->main_menu = array();

		//Variables Container. Views will extract data/variables from here.
		$this->app_data = array();

		$this->html_class = 'footer-sticky';

		//Register all the scripts and put them into their specific containers.
		$this->__register_scripts();

		// Put variables to variables Registery $this->app_data
		$this->fn_register_common_variables();

	}

	/**
	 * Loads all the documented scripts.
	 * Extract CSS and JS to put them into their respective Registered Containers.
	 * 	Populates $registered_css array.
	 * 	Populates $registered_js array.
	 */
	private function __register_scripts() {
		// Load all the registered scripts
		$assets_loader = new SwpmvcAssetsLoader();
		$assets = $assets_loader->fn_load_scripts();

		//Extract CSS Assets and put them into Registered CSS Container
		$this->registered_css = $assets[ 'css' ];

		//Extract JS Assets and put them into Registered JS Container
		$this->registered_js = $assets[ 'js' ];

		//Some cleanup
		unset($assets_loader);
		unset($assets);

		//Load all the common CSS and JS which will be required for all the pages.
		$this->fn_load_scripts();
	}

	/**
	 * We wont be needing this. Children classes would be implementing this.
	 * Just fulfilling the contract of the interface.
	 */
	public function fn_construct_class() {
	}

	/**
	 * This function loads all the scripts required for every pages and distributes them into their respective containers.
	 * Other controllers will have their own version of this function to load scripts related to that specific page.
	 */
	private function fn_load_scripts() {

		/**
		 * Load Common CSS and JS for a blank Page
		 */

		$fixed_version = $this->fixed_version;
		$common_css_version = '0.3';
		$common_js_version = '0.1';
		$custom_css_version = '0.4';
		$custom_js_version = '0.4';

		$registered_styles = $this->registered_css;
		$registered_scripts = $this->registered_js;

		/**
		 * Load CSS--------------------------------------------------------------
		 */

        /** Load Google Fonts */
		$this->google_fonts['google_opensans'] = $registered_styles['google_opensans'];

		/** Load CSS Assets @todo Move the components in their containers */
		
		$this->css_assets['bootstrap'] = $registered_styles['bootstrap']."?ver=".$fixed_version;
		$this->css_assets['fontawesome'] = $registered_styles['fontawesome']."?ver=".$fixed_version;
		
		/**
		 * Load JS--------------------------------------------------------------
		 */
		
		/** Load footer js **/
		$this->footer_js['jquery'] = $registered_scripts['jquery']."?ver=".$fixed_version;		
		$this->footer_js['jquery-ui'] = $registered_scripts['jquery-ui']."?ver=".$fixed_version;

		$this->footer_js['bootstrap'] = $registered_scripts['bootstrap']."?ver=".$fixed_version;
		/**let other controllers load their own css/js files **/
	}

	public function fn_get_header() {

		return $this->load_view('header');
	}

	public function fn_get_footer() {

		return $this->load_view('footer');
	}

	public function fn_get_content() {

		return $this->load_view('index');
	}

	public function fn_display() {

		$header = $this->fn_get_header();
		$footer = $this->fn_get_footer();
		$content = $this->fn_get_content();

		return $header.$content.$footer;
	}

	/**
	 * All views are relative to the folder swpmvc/views/
	 * just name the file and not its extensions.
	 */
	public function load_view($view, $template_vars = null) {

		$view_file_name = SWPMVC_PLUGIN_DIR.'views/'.$view.'.html.php';

		$str_return = '';

		if(file_exists($view_file_name)) {

			extract($this->app_data);

			if(isset($template_vars) && !empty($template_vars)) {
				extract($template_vars);
			}

			ob_start();
			include_once($view_file_name);
			$str_return = ob_get_clean();

		} else {
			$str_return = 'Error! Loading view '.$view_file_name;
		}

		return $str_return;
	}

	/**
	 * Function to register common variables to global application variables registry
	 */
	private function fn_register_common_variables() {
		$current_user = wp_get_current_user();
		//perhaps we don't need this variable. still, lets continue with this.
		$this->app_data['swpmvc_current_user'] = $current_user;
		$this->app_data['inline_js'] = '';
	}

}
