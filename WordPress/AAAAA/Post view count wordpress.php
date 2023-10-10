<?php

/**
 * Post view count (The post grid)
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

// Remove transient after every 24 hours
add_action('rttpg_daily_scheduled_events', function(){
	try {
		global $wpdb;
		$expired = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout%' AND option_value < UNIX_TIMESTAMP()" );

		foreach ( $expired as $transient ) {
			$key = str_replace('_transient_timeout_tpg_cache_', 'tpg_cache_', $transient);
			delete_transient( $key );
		}

	} catch ( \Exception $e ) {

	}
});

/**
 * Post View count By TT
 * ============================================================================
 */

 //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
// Set post view on single page
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

//---------------------------------------------------------------------
// Set Post view Count
//---------------------------------------------------------------------
// 1


// Set post count 
// 2 
if ( ! function_exists( 'set_post_view_count' ) ) :

    function set_post_view_count( $contents ) {
        if ( is_single() ) {
			$postID = get_the_ID();
			$count_key = 'tt_post_views_count';
			$count = get_post_meta($postID, $count_key, true);
			if($count==''){
				$count = 0;
				delete_post_meta($postID, $count_key);
				add_post_meta($postID, $count_key, '0');
			}else{
				$count++;
				update_post_meta($postID, $count_key, $count);
			}
        }

        return $contents;
    }

    add_filter( 'the_content', 'set_post_view_count', 9999 );

endif;


// Get post view number 
// 3
if (!function_exists('get_post_views')) {
	function get_post_views($postID){
		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			return 0;
		}
		return $count;
	}
}



/**
 * Post view count PostX
 * ====================================================================
 * it saved the meta for oneday in client site by cookies
 */


add_action('wp', 'popular_posts_tracker_callback');
function popular_posts_tracker_callback($post_id) {
	if (!is_single()) { return; }
	global $post;
	$post_id = isset($post->ID) ? $post->ID : '';
	$isEnable = apply_filters('ultp_view_cookies', true);
	// add_filter( 'ultp_view_cookies', '__return_false' ); 
	$cookies_disable = ultimate_post()->get_setting('disable_view_cookies');
	if ($post_id && $isEnable && $cookies_disable != 'yes') {
		$has_cookie = isset( $_COOKIE['ultp_view_'.$post_id] ) ? sanitize_text_field($_COOKIE['ultp_view_'.$post_id]) : false;
		if (!$has_cookie) {
			$count = (int)get_post_meta( $post_id, '__post_views_count', true );
			update_post_meta($post_id, '__post_views_count', $count ? (int)$count + 1 : 1 );
			setcookie( 'ultp_view_'.$post_id, 1, time() + 86400, COOKIEPATH ); // 1 days cookies
		}
	}
}