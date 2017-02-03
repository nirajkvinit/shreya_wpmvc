<?php namespace GM\VirtualPages;
/*
Copyright (C) 2014 Giuseppe Mazzapica

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
 
require_once 'PageInterface.php';
require_once 'ControllerInterface.php';
require_once 'TemplateLoaderInterface.php';
require_once 'Page.php';
require_once 'Controller.php';
require_once 'TemplateLoader.php';

$controller = new Controller ( new TemplateLoader );

add_action( 'init', array( $controller, 'init' ) );

add_filter( 'do_parse_request', array( $controller, 'dispatch' ), PHP_INT_MAX, 2 );

add_action( 'loop_end', function( \WP_Query $query ) {
  if ( isset( $query->virtual_page ) && ! empty( $query->virtual_page ) ) {
    $query->virtual_page = NULL;
  }
} );

add_filter( 'the_permalink', function( $plink ) {
  global $post, $wp_query;
  if (
    $wp_query->is_page
    && isset( $wp_query->virtual_page )
    && $wp_query->virtual_page instanceof Page
    && isset( $post->is_virtual )
    && $post->is_virtual
  ) {
    $plink = home_url( $wp_query->virtual_page->getUrl() );
  }
  return $plink;
} );
