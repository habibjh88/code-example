<?php 
//Get All text and modify it
add_filter( 'gettext', 'wpdocs_change_login_form_register_keyword' );

/**
 * Change Register link text on the login form
 *
 * @param $text string
 * @return $text string
 * * * * * * * * * * * * * * * * * * */
function wpdocs_change_login_form_register_keyword( $text ) {

	$text = str_ireplace( 'Sign me up for the newsletter!', 'Sign Up Now - Jjj', $text );

	error_log( print_r( $text , true ) . "\n\n" , 3, __DIR__ . '/log.txt' );

	return $text;
}


//TODO: belo code for replace some script in specific screen
//Change text Store -> Agency
add_action( 'current_screen', function ( $screen ) {
    if ( is_object( $screen ) && $screen->post_type == 'rtcl_listing' ) {
        add_filter( 'gettext', [ $this, 'homlisti_store_to_agency' ], 99, 3 );
    }
} );
function homlisti_store_to_agency( $translated_text, $untranslated_text, $domain ) {
    $translated_text = str_replace( [ 'Store', 'Stores' ], [ 'Agency', 'Agencies' ], $translated_text );

    return $translated_text;
}