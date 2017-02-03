<?php
namespace GM\VirtualPages;
require_once SWPMVC_PLUGIN_DIR.'includes/gm-virtual-pages/TemplateLoaderInterface.php';

class SwpmvcTemplateLoader implements TemplateLoaderInterface {

	public function init( PageInterface $page ) {
		$this->templates = wp_parse_args(
			array( 'page.php' ), (array) $page->getTemplate()
		);
	}
  
	public function load() {
		do_action( 'template_redirect' );		
		$template = $this->swpmvc_locate_views(array_filter($this->templates), TRUE);
	}
	
	/**
	 * Function will locate template name in the templates section of this plugin.
 	 * Here template name means root Controller which will load related Page Controllers.
 	 * @todo Expand this function so that other directory paths can also be used to load templates.
 	 */
 	
	function swpmvc_locate_views($template_names, $load = false) {
		$require_once = true;
		$located = '';
		/**
		 * At present we are directly including the root controller 
		 * (aka template for the GM virtual pages). 
		 */	
		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name ) {
				continue;
			}			
			if ( file_exists(SWPMVC_PLUGIN_DIR . 'includes/swpmvc_virtual_pages/' . $template_name)) {
				$located = SWPMVC_PLUGIN_DIR . 'includes/swpmvc_virtual_pages/' . $template_name;
				break;
			}
		}
	
		if ( $load && '' != $located )
			load_template( $located, $require_once );
	
		return $located;
		
	}
}

