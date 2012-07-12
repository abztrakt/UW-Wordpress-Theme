<?php
/*
Controller name: UW
Controller description: UW theming API for Dropdowns, header and footer
*/

class json_api_uw_controller {

  public function dropdowns() {

    global $json_api;

    $locations = get_nav_menu_locations();
    $nav = wp_get_nav_menu_items($locations['primary']);
    foreach ($nav as $item) {
      if ( $item->menu_item_parent == 0)
        $parents[$item->ID] = $item->post_title; 
    }
    return $parents;


    
  }

  public function header() {

    global $json_api;

    get_header();


  }

    
  protected function posts_object_result($posts, $object) {
    //global $wp_query;
    return array(
      'count' => count($posts),
     // 'pages' => (int) $wp_query->max_num_pages,
      'posts' => $posts
    );
  }
  

}


/*
 * Use custom JSON API
 *
 */

function add_uw_controller($controllers) {
  $controllers[] = 'uw';
  return $controllers;
}
add_filter('json_api_controllers', 'add_uw_controller');

function set_uw_controller_path() {
  return dirname(__FILE__) . "/json-api.php";
}
add_filter('json_api_uw_controller_path', 'set_uw_controller_path');
