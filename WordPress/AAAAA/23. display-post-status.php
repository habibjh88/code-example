<?php
// Display Post Status

add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );

public function add_display_post_states( $post_states, $post ) {

    if ( $post && $post->ID == 3941 ) {
        $post_states[]  = "hello gow are you";
    }
  

    return $post_states;
}
