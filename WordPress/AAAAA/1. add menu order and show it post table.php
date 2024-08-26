<?php 
$MY_POST_TYPE = "rtcl_cfg"; // just for a showcase

// the basic support (menu_order is included in the page-attributes)
add_post_type_support($MY_POST_TYPE, 'page-attributes');

// add a column to the post type's admin
// basically registers the column and sets it's title
add_filter('manage_' . $MY_POST_TYPE . '_posts_columns', function ($columns) {
	$columns['menu_order'] = "Order"; //column key => title
	return $columns;
});

// display the column value
add_action( 'manage_' . $MY_POST_TYPE . '_posts_custom_column', function ($column_name, $post_id){
	if ($column_name == 'menu_order') {
		echo get_post($post_id)->menu_order;
	}
}, 10, 2); // priority, number of args - MANDATORY HERE!

// make it sortable
$menu_order_sortable_on_screen = 'edit-' . $MY_POST_TYPE; // screen name of LIST page of posts
add_filter('manage_' . $menu_order_sortable_on_screen . '_sortable_columns', function ($columns){
	// column key => Query variable
	// menu_order is in Query by default so we can just set it
	$columns['menu_order'] = 'menu_order';
	return $columns;
});
