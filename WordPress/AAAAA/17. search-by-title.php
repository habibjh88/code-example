<?php 
// Search by title wordpress
// multiple words search 
// search by multiple words

//Code example from The Post Grid Pro

//1. First, we have to add a "posts_where" filter just before of the WP_Query and also have to remove that filter just after the WP_Query class.1
add_filter( 'posts_where', 'rttpg_title_keyword_filter', 10, 2 );

$query = new WP_Query( apply_filters( 'tpg_sc_query_args', $el_query ) );

remove_filter( 'posts_where', 'rttpg_title_keyword_filter', 10, 2 );

//2. Now just added the callback function for the 'posts_where' filter 
function rttpg_title_keyword_filter( $where, $wp_query ) {
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
        if ( strpos( trim( $search_term ), ' ' ) !== false ) {
            $search_term2 = explode( ' ', $search_term );
            foreach ( $search_term2 as $title ) {
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $title ) ) . '%\'';
            }
        } else {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
        }
    }

    return $where;
}
