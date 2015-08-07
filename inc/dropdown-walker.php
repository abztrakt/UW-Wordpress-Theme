<?php

class UW_Dropdowns_Walker_Menu extends Walker_Nav_Menu {

	public $dropdown_enqueued;
  private $count  = 0;
  private $toggle = true;
  private $current = '';

	function UW_Dropdowns_Walker_Menu() {
		$this->dropdown_enqueued = wp_script_is( 'bootstrap-dropdown', 'queue' );
    add_filter('wp_nav_menu', array($this, 'add_role_menubar'));
	}

  function add_role_menubar($html) {
    return str_replace('class="nav"', 'class="nav" role="menubar"', $html);
  }

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		if ( $element->current )
			$element->classes[] = 'active';

		$element->is_dropdown  = ( 0 == $depth ) && ! empty( $children_elements[$element->ID] );

		if ( $element->is_dropdown )
			$element->classes[] = 'dropdown';

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( ! $this->dropdown_enqueued ) {
			wp_enqueue_script( 'bootstrap-dropdown' );
			$this->dropdown_enqueued = true;
		}

		$indent = str_repeat( "\t", $depth );
		$class  = ( 0 == $depth ) ? 'dropdown-menu' : 'unstyled';
		$output .= "\n{$indent}<ul role=\"menu\" id=\"menu-".$this->current."\" aria-expanded=\"false\" class=\"{$class}\"><div class=\"menu-wrap\"><div class=\"inner-wrap\">\n";
	}

	function end_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "</div>$indent</div></div></ul>\n";
    $this->toggle = true;
    $this->count = 0;
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

    $item->attr_title = apply_filters('attribute_escape', $item->post_title);

    $this->current = $item->post_name;

		$item_html = '';
    if( $item->menu_item_parent && $this->count++ % 7 == 0) {
        $item_html = ( $this->toggle ) ? '<div class="menu-block">' : '</div><div class="menu-block">';
        $this->toggle = false;
    }
		parent::start_el( $item_html, $item, $depth, $args );

		if ( $item->is_dropdown && ( 1 != $args->depth ) ) {
			$item_html = str_replace( '<li', '<li role="presentation"', $item_html );
			$item_html = str_replace( '<a', '<a role="menuitem" class="dropdown-toggle" data-toggle="dropdown" tabindex="0" aria-controls="menu-'.$item->post_name.'"', $item_html );
			$item_html = str_replace( '</a>', '<b class="caret"></b></a>', $item_html );
    } else if ( $item->menu_item_parent == 0) {
			$item_html = str_replace( '<li', '<li role="menuitem"', $item_html );
			$item_html = str_replace( '<a', '<a tabindex="0"', $item_html );
    } else {
			$item_html = str_replace( '<a', '<a tabindex="-1"', $item_html );
    }

		$output .= $item_html;
	}

}
