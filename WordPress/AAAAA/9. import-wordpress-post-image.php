<?php

// TIPS: 

// increase time_imit by wp-config.php -> set_time_limit(3600);

// Only post and images
//=================================
// 1st step - Export all Media
// 2nd step - Export all post

// 3rd step : open post.xml file - search _thumbnail_id
?>
        <wp:meta_key><![CDATA[_thumbnail_id]]></wp:meta_key>
        <wp:meta_value><![CDATA[9506]]></wp:meta_value> /*Have to change all thumbnail by increase from max post id id [119506]. I added 11 prefix for increase the id */
<?php

// 4th stpe: You have to add same prefix for thumnail.xml file 
?>
    <wp:post_id>119523</wp:post_id> /*Search <wp:post_id> and add 11 prefix for all thumnail id (here post_id)*/
<?php
// 5th step: now 1st import the thumbnail.xml file and then import the post.xml file

