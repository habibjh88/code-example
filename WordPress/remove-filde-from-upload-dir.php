<?php 
//Remove any file from uplaod directory on demand 


function delete_cache_folder( $filename ) {
	$cache_folder = WP_CONTENT_DIR . '/' . $filename;

	if ( is_dir( $cache_folder ) ) {
		delete_folder_recursive( $cache_folder );
	}
}

function delete_folder_recursive( $folder ) {
	if ( ! is_dir( $folder ) ) {
		return;
	}

	$files = array_diff( scandir( $folder ), [ '.', '..' ] );
	foreach ( $files as $file ) {
		$file_path = $folder . DIRECTORY_SEPARATOR . $file;
		if ( is_dir( $file_path ) ) {
			delete_folder_recursive( $file_path );
		} else {
			unlink( $file_path ); // Delete file
		}
	}

	rmdir( $folder ); // Remove empty folder
}

//add_action('admin_init', 'delete_cache_folder');

if ( ! empty( $_GET['clear_cache'] ) && current_user_can( 'manage_options' ) ) {
	delete_cache_folder( $_GET['clear_cache'] );
	echo 'Cache folder deleted!';
}