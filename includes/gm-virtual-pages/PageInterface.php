<?php
namespace GM\VirtualPages;

interface PageInterface {

  function getUrl();
  
  function getTemplate();
  
  function getTitle();
  
  function setTitle( $title );
  
  function setContent( $content );
  
  function setTemplate( $template );
  
  /**
  * Get a WP_Post build using viryual Page object
  *
  * @return \WP_Post
  */
  function asWpPost();
}