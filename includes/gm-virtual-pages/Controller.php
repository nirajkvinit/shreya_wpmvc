<?php
namespace GM\VirtualPages;

class Controller implements ControllerInterface {

  private $pages;
  private $loader;
  private $matched;
  
  function __construct( TemplateLoaderInterface $loader ) {
    $this->pages = new \SplObjectStorage;
    $this->loader = $loader;
  }
  
  function init() {
    do_action( 'gm_virtual_pages', $this ); 
  }
  
  function addPage( PageInterface $page ) {
    $this->pages->attach( $page );
    return $page;
  }
  
  function dispatch( $bool, \WP $wp ) {
  	
    if ( $this->checkRequest() && $this->matched instanceof Page ) {    	
      $this->loader->init( $this->matched );
      $wp->virtual_page = $this->matched;
      do_action( 'parse_request', $wp );
      $this->setupQuery();
      do_action( 'wp', $wp );
      $this->loader->load();
      $this->handleExit();
    } 
    return $bool;
  }
  
  private function checkRequest() {
    $this->pages->rewind();
		
	$path = trim( parse_url($this->getPathInfo(), PHP_URL_PATH), '/');
	
    while( $this->pages->valid() ) {
      if ( trim( $this->pages->current()->getUrl(), '/' ) === $path ) {
        $this->matched = $this->pages->current();
        return TRUE;
      }
      $this->pages->next();
    }
  }        
  
  private function getPathInfo() {  	
    $home_path = parse_url( home_url(), PHP_URL_PATH );	
    return preg_replace( "#^/?{$home_path}/#", '/', add_query_arg( array() ) );
  }
  
  private function setupQuery() {
    global $wp_query;
	
    $wp_query->init();
	
	$wp_query->query_vars['name']		= '';
	$wp_query->query_vars['year']		= '';
	$wp_query->query_vars['monthnum']	= '';
	$wp_query->query_vars['day']		= '';	
	
    $wp_query->is_page       = TRUE;
    $wp_query->is_singular   = TRUE;
    $wp_query->is_home       = FALSE;
	
    $wp_query->found_posts   = 1;
    $wp_query->post_count    = 1;
    $wp_query->max_num_pages = 1;
    $posts = (array) apply_filters(
      'the_posts', array( $this->matched->asWpPost() ), $wp_query
    );
    $post = $posts[0];
    $wp_query->posts          = $posts;
    $wp_query->post           = $post;
    $wp_query->queried_object = $post;
    $GLOBALS['post']          = $post;
    $wp_query->virtual_page   = $post instanceof \WP_Post && isset( $post->is_virtual )
      ? $this->matched
      : NULL;
	  //fn_schooltonic_temp_log('****************************');
	  //fn_schooltonic_temp_log(print_r($wp_query, TRUE));
  }
  
  public function handleExit() {
    exit();
  }
}