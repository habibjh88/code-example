<?php 

/*
* Source from Ranna Theme
===============================
* Template Redirect
* Endpoint create - end point - endpoint
* New Author page created
*/

class TemplageRedirect{
    function __construct() {
        add_action( 'pre_get_posts', array( $this, 'ranna_support_author_archive' ) );
        //Endpoint register
        add_action( 'init', [ $this, 'ranna_author_endpoint_register' ] );
        //Template Load under the endpoint
        add_action( 'template_redirect', [ $this, 'ranna_author_template' ] );
    }


    public function ranna_support_author_archive( $query ) {
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }

        $endpoint = RannaTheme::$options['recipe_slug'];

        global $wp;
        $current_page_url = home_url( $wp->request );
        $end_part = explode( '/', rtrim( $current_page_url, '/' ) );
        $page_number    = end( $end_part );
        if(is_numeric($page_number)){
            $paged = $page_number;
        } else {
            $paged = 1;
        }

        if ( is_author() && isset( $query->query_vars[ $endpoint ] ) ) {
            $query->set( 'post_type', 'ranna_recipe' );
            $query->set( 'paged', $paged );
        }
    }

    function ranna_author_endpoint_register() {
        if ( $recipe_slug = RannaTheme::$options['recipe_slug'] ) {
            add_rewrite_endpoint( $recipe_slug, EP_AUTHORS );
        }
    }

    public function ranna_author_template() {
        global $wp_query;
        $endpoint = RannaTheme::$options['recipe_slug'];

        if ( ! ( isset( $wp_query->query_vars[ $endpoint ] ) && is_author() ) ) {
            return;
        }
        //Load Author Template
        get_template_part( 'template-parts/author-archive-recipe' );

        exit;
    }
}
