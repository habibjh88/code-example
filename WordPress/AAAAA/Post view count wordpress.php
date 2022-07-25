<?php

/**
 * Post view count
 * ====================================================================
 */
add_filter( 'wp_head', 'update_post_views_count');
function update_post_views_count( $post_id ) {
	if ( ! $post_id ) {
		return;
	}

	$user_ip = $_SERVER['REMOTE_ADDR']; // retrieve the current IP address of the visitor
	$key     = 'tpg_cache_' . $user_ip . '_' . $post_id;
	$value   = [ $user_ip, $post_id ];
	$visited = get_transient( $key );
	if ( false === ( $visited ) ) {
		set_transient( $key, $value, HOUR_IN_SECONDS * 12 ); // store the unique key, Post ID & IP address for 12 hours if it does not exist

		// now run post views function
		$count_key = 'tpg-post-view-count';
		$count     = get_post_meta( $post_id, $count_key, true );
		if ( '' == $count ) {
			update_post_meta( $post_id, $count_key, 0 );
		} else {
			$count = absint( $count );
			$count ++;
			update_post_meta( $post_id, $count_key, $count );
		}
	}
}