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

add_filter('user_contactmethods','additional_contact_fields',10,1);
