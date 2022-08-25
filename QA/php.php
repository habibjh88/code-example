<?php
// global wpdb example 
// 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object
global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_id = 1", OBJECT );


//Using prepared statement
$metakey   = 'your_meta_key';
$metavalue = "WordPress' database interface is like Sunday Morning: Easy.";
 
$wpdb->query(
   $wpdb->prepare(
      "INSERT INTO $wpdb->postmeta
      ( post_id, meta_key, meta_value )
      VALUES ( %d, %s, %s )",
      10,
      $metakey,
      $metavalue
   )
);

//Get Single value as a variable 
$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
echo "<p>User count is {$user_count}</p>";

//Get rows 
$mylink = $wpdb->get_row( "SELECT * FROM $wpdb->links WHERE link_id = 10", ARRAY_A );


//Get post by get_results
$fivesdrafts = $wpdb->get_results( 
    "
        SELECT ID, post_title 
        FROM $wpdb->posts
        WHERE post_status = 'draft'
        AND post_author = 5
    "
);
 
foreach ( $fivesdrafts as $fivesdraft ) {
    echo $fivesdraft->post_title;
}


/**
 * 
 * Insert into table
 */

$wpdb->insert( 
    'table', 
    array( 
        'column1' => 'value1', 
        'column2' => 123, 
    ), 
    array( 
        '%s', 
        '%d', 
    ) 
);


/**
 * 
 * Delete example
 */

$wpdb->query( 
    $wpdb->prepare( 
        "
                    DELETE FROM $wpdb->postmeta
            WHERE post_id = %d
            AND meta_key = %s
        ",
            13, 'gargle'
        )
);
