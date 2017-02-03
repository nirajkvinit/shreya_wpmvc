<?php 

namespace GM\VirtualPages;

require_once SWPMVC_PLUGIN_DIR.'includes/gm-virtual-pages/PageInterface.php';
require_once SWPMVC_PLUGIN_DIR.'includes/gm-virtual-pages/ControllerInterface.php';
require_once SWPMVC_PLUGIN_DIR.'includes/gm-virtual-pages/TemplateLoaderInterface.php';
require_once SWPMVC_PLUGIN_DIR.'includes/gm-virtual-pages/Page.php';
require_once SWPMVC_PLUGIN_DIR.'includes/gm-virtual-pages/Controller.php';
require_once SWPMVC_PLUGIN_DIR.'includes/swpmvc_virtual_pages/SwpmvcTemplateLoader.php';

$controller = new Controller( new SwpmvcTemplateLoader );

add_action( 'init', array( $controller, 'init' ) );

add_filter( 'do_parse_request', array( $controller, 'dispatch' ), PHP_INT_MAX, 2 );

add_action( 'loop_end', function( \WP_Query $query ) 
	{
		if ( isset( $query->virtual_page ) && ! empty( $query->virtual_page ) ) {
			$query->virtual_page = NULL;
		}
	});

add_filter( 'the_permalink', function( $plink ) {
	global $post, $wp_query;
	if( 
		$wp_query->is_page && 
		isset( $wp_query->virtual_page ) && 
		$wp_query->virtual_page instanceof Page && 
		isset( $post->is_virtual ) && 
		$post->is_virtual )	{
			$plink = home_url( $wp_query->virtual_page->getUrl() );
	}
	return $plink;
} );

