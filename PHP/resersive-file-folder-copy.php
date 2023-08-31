<?php 

function custom_copy( $src, $dst, $exclude = [] ) {

	// open the source directory
	$dir = opendir( $src );

	// Make the destination directory if not exist
	@mkdir( $dst );

	// Loop through the files in source directory
	while ( $file = readdir( $dir ) ) {

		if ( in_array( $file, $exclude ) ) {
			continue;
		}

		error_log( print_r( $file , true ) . "\n\n" , 3, __DIR__ . '/log.txt' );

		if ( ( $file != '.' ) && ( $file != '..' ) ) {
			if ( is_dir( $src . '/' . $file ) ) {

				// Recursively calling custom copy function
				// for sub directory
				custom_copy( $src . '/' . $file, $dst . '/' . $file );

			} else {
				copy( $src . '/' . $file, $dst . '/' . $file );
			}
		}
	}

	closedir( $dir );
}
$exclude = [
	'one.png',
	'two'
];
$src = 'C:\Users\DELL\Desktop\test';

$dst = 'C:\Users\DELL\Desktop\dest';

custom_copy( $src, $dst, $exclude );