<?php
// Wordpress migration // wp migration //
function abw_magazine_migrate() {
	if ( isset( $_REQUEST['action'] ) && 'migrate' == $_REQUEST['action'] ) {
		$pages = isset( $_REQUEST['pages'] ) ? $_REQUEST['pages'] : 1;
		$numberposts = 30;
		$offset = ($pages-1) * $numberposts;

		$posts = get_posts( [
			'post_type'      => 'issue-archive',
			'post_status'    => 'any',
			'posts_per_page' => $numberposts,
			'offset'         => $offset
		] );

		//var_dump( count( $posts ) );exit();

		if ( empty( $posts ) ) {
			wp_die( "Done" );
		}

		foreach ( $posts as $key => $post ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts set post_type='issue' WHERE ID=%d", $post->ID ) );
			echo "<pre>Migrated - $post->ID</pre>";
			
			//update_post_meta($post->ID, '_thumbnail_id', '911536');
		}
		$url = add_query_arg( [ 'action' => 'migrate', 'pages' => $pages + 1 ], site_url() );
		?>
		<script>
            window.location = "<?php echo $url;?>";
		</script>
		<?php
		exit();
	}
}
add_action( 'template_redirect', 'abw_magazine_migrate' );

//Only Image Migrated
function es_data_migrate() {
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'migrate' ) {
		$page          = isset( $_REQUEST['pages'] ) ? $_REQUEST['pages'] : 1;
		$post_per_page = 30;
		$offset        = ( $page - 1 ) * $post_per_page;
		$posts         = get_posts( [
			'post_type'      => 'post',
			'post_status'    => 'any',
			'posts_per_page' => $post_per_page,
			'offset'         => $offset
		] );

		if ( empty( $posts ) ) {
			wp_die( "Migrate Done" );
		}

		foreach ( $posts as $post ) {
			update_post_meta( $post->ID, '_thumbnail_id', '273611' );
		}

		$url = add_query_arg( array(
			'action' => 'migrate',
			'pages'   => $page + 1
		), site_url() );
		?>
		<script>
			window.location = "<?php echo $url ?>";
		</script>
		<?php
		exit();
	}
}

add_action( 'init', 'es_data_migrate' );