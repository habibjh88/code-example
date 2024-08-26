<?php

// wp video | wordpress video embed

//Uploadded Video
echo wp_video_shortcode( [ 'src' => wp_get_attachment_url( $dir_list['direction_self_video'] ) ] );

//Video from URL / Youtube video 
echo wp_oembed_get( $dir_list['direction_url'] );
