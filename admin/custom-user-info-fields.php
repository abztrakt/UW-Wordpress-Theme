<?php
if (!function_exists('additional_contact_fields')) :

  function additional_contact_fields( $contactmethods ) {
    // Add Twitter, Facebook and Affiliation
    $contactmethods['affiliation'] = 'Affiliation';
    $contactmethods['phone'] = 'Phone Number';
    $contactmethods['office'] = 'Office';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['facebook'] = 'Facebook';
    unset( $contactmethods['yim'] );
    unset( $contactmethods['aim'] );
    unset( $contactmethods['jabber'] );
    
    return $contactmethods;
  }

endif;

function uw_filter_guest_author_fields( $fields_to_return, $groups ) {
 
	if ( in_array( 'all', $groups ) || in_array( 'name', $groups ) ) {
		$fields_to_return[] = array(
					'key'      => 'affiliation',
					'label'    => 'Affiliation',
					'group'    => 'name',
				);
	} 
 
	return $fields_to_return;
}


add_filter( 'user_contactmethods','additional_contact_fields',10,1);
add_filter( 'coauthors_guest_author_fields', 'uw_filter_guest_author_fields', 10, 2 );
