<?php

/**
 * parent - child post slug set 
 * 1. Parent CPT: book; Child CPT: chapter
 * 1. Child CPT should have = Custom Rewrite Slug: book/%book%/chapter/
 * ====================================================================
 */
function philosophy_cpt_slug_fix($post_link, $id)
{
    $p = get_post($id);
    if (is_object($p) && 'chapter' == get_post_type($id)) {
        $parent_post_id = get_field('parent_book');
        $parent_post = get_post($parent_post_id);
        if ($parent_post) {
            $post_link = str_replace("%book%", $parent_post->post_name, $post_link);
        }
    }
    return $post_link;
}
add_filter('post_type_link', 'philosophy_cpt_slug_fix', 1, 2);


/**
 * Change Single Page Template 
 * ====================================================================
 */

function template_callback($file)
{
    global $post;
    if ($post->post_type == 'book') {
        $file_path = plugin_dir_path(__FILE__) . 'template/single-template.php'; //Actual file path
        $file = $file_path;
    }
    return $file;
}
add_filter('single_template', 'template_callback');

/**
 * Change Archive / Single Page Template 
 * ====================================================================
 */

add_filter( 'template_include',  'el_template_loader_default_file', 99 );

function el_template_loader_default_file( $default_file ) {
    global $wp_query;
    $builder_file = '';
    $file_path = plugin_dir_path(__FILE__) . 'template/single-template.php'; //Actual file path


    if ( self::$single_page_builder_id && is_singular( self::$tpg_post_types ) ) {
        $builder_file = 'single-listing-fullwidth.php';
    } elseif ( self::$archive_builder_id && isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
        $builder_file = 'archive-listing-fullwidth.php';
    } elseif ( self::$category_archive_builder_id && is_category() ) {
        $builder_file = 'archive-listing-fullwidth.php';
    } elseif ( self::$author_archive_builder_id && is_author() ) {
        $builder_file = 'archive-listing-fullwidth.php';
    } elseif ( self::$tags_archive_builder_id && is_tag() ) {
        $builder_file = 'archive-listing-fullwidth.php';
    } elseif ( self::$search_archive_builder_id && is_search() ) {
        $builder_file = 'archive-listing-fullwidth.php';
    } elseif ( self::$date_archive_builder_id && is_date() ) {
        $builder_file = 'archive-listing-fullwidth.php';
    }

    if ( $builder_file ) {
        $default_file = $plugin_path . $builder_file;
    }

    return $default_file;
}



