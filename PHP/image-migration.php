<?php
// WP Image Migration
// Define the array of image URLs
$images = [
'http://tpg-mytemp.local/wp-content/uploads/2024/11/dainolite-logo.png',
'http://tpg-mytemp.local/wp-content/uploads/2021/06/manufacturer-hero-scaled.jpg',
'http://tpg-mytemp.local/wp-content/uploads/2024/02/FLOS-Logo-Black.png',
'http://tpg-mytemp.local/wp-content/uploads/2024/08/karman-light.png',
'http://tpg-mytemp.local/wp-content/uploads/2024/05/Niche_Logo_Black.png',
'http://tpg-mytemp.local/wp-content/uploads/Onlight-Lighting-use.jpg',
];

// Define the base directories
$srcFolder     = DIR . '/src'; // Source folder containing images
$uploadsFolder = DIR . '/uploads'; // Destination uploads folder

// Iterate over each image URL
foreach ( $images as $imageUrl ) {
    // Parse the URL to extract the path
    $path = parse_url( $imageUrl, PHP_URL_PATH );

    // Get the relative path from 'uploads/' onwards
    $relativePath = str_replace( '/wp-content/uploads/', '', $path );

    // Construct the destination path
    $destinationPath = $uploadsFolder . '/' . $relativePath;

    // Extract the directory and filename
    $destinationDir = dirname( $destinationPath );
    $fileName       = basename( $destinationPath );

    // Ensure the destination directory exists
    if ( ! file_exists( $destinationDir ) ) {
        mkdir( $destinationDir, 0777, true );
    }

    // Check if the source image exists
    $sourceFile = $srcFolder . '/' . $fileName;
    if ( file_exists( $sourceFile ) ) {
        // Move the file to the destination folder
        if ( rename( $sourceFile, $destinationPath ) ) {
            echo "Moved $fileName to $destinationPath\\n";
        } else {
            echo "Failed to move $fileName to $destinationPath\\n";
        }
    } else {
        echo "Source file $sourceFile does not exist\\n";
    }
}