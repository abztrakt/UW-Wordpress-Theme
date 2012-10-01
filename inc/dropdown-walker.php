<?php

class UW_Dropdowns_Walker_Menu extends Walker_Nav_Menu {

	public $dropdown_enqueued;
  private $count  = 0;
  private $toggle = true;

	function UW_Dropdowns_Walker_Menu() {
		$this->dropdown_enqueued = wp_script_is( 'bootstrap-dropdown', 'queue' );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		if ( $element->current )
			$element->classes[] = 'active';

		$element->is_dropdown  = ( 0 == $depth ) && ! empty( $children_elements[$element->ID] );

		if ( $element->is_dropdown )
			$element->classes[] = 'dropdown';

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_lvl( &$output, $depth ) {

		if ( ! $this->dropdown_enqueued ) {
			wp_enqueue_script( 'bootstrap-dropdown' );
			$this->dropdown_enqueued = true;
		}

		$indent = str_repeat( "\t", $depth );
		$class  = ( 0 == $depth ) ? 'dropdown-menu' : 'unstyled';
		$output .= "\n{$indent}<ul role='menu' aria-hidden='true' class='{$class}'><div class='menu-wrap'><div class='inner-wrap'>\n";
	}

	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "</div>$indent</div></div></ul>\n";
    $this->toggle = true;
    $this->count = 0;
	}

	function start_el( &$output, $item, $depth, $args ) {

    $item->attr_title = apply_filters('attribute_escape', $item->post_title);

		$item_html = '';
    if( $item->menu_item_parent && $this->count++ % 7 == 0) {
        $item_html = ( $this->toggle ) ? '<div class="menu-block">' : '</div><div class="menu-block">';
        $this->toggle = false;
    }
		parent::start_el( $item_html, $item, $depth, $args );

    $item_html = str_replace('<li', '<li role="menuitem"', $item_html);
		if ( $item->is_dropdown && ( 1 != $args->depth ) ) {
			$item_html = str_replace( '<li', '<li aria-haspopup="true"', $item_html );
			$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown" tabindex="0"', $item_html );
			$item_html = str_replace( '</a>', '<b class="caret"></b></a>', $item_html );
    } else if ( $item->menu_item_parent == 0) {
			$item_html = str_replace( '<a', '<a class="dropdown-toggle" tabindex="0"', $item_html );
    } else {
			$item_html = str_replace( '<a', '<a tabindex="-1"', $item_html );
    }

		$output .= $item_html;
	}
  
}
