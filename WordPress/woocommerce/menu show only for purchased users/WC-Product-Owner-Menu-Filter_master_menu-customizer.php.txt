<?php
/*
Plugin Name: Menu Customizer
Plugin URI:
Description: Friendly Description
Version: 1.0.0
Author: Plain Text Author Name
Author URI:
License: GPLv2 or later
Text Domain: Text Domain for Translation
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use HasinHayder\D\D;

require_once 'vendor/autoload.php';

function mcp_load() {
    \Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'plugins_loaded', 'mcp_load' );

function mcp_init() {
    if ( !class_exists( 'WooCommerce' ) ) {
        return;
    }
    global $wpdb;
    $products = $wpdb->get_results( "SELECT ID, post_title as name from {$wpdb->prefix}posts WHERE post_status='publish' and post_type='product'", ARRAY_A );
    $_products = array( 0 => 'Always Display' );
    foreach ( $products as $product ) {
        $_products[$product['ID']] = $product['name'];
    }

    //D::print_r($products);

    Container::make( 'nav_menu_item', __( 'Menu Settings' ) )
        ->add_fields( array(
            Field::make( 'select', 'mcp_product', __( 'Display Menu To The Product Owners' ) )
                ->set_options( $_products ),
        ) );
}
add_action( 'carbon_fields_register_fields', 'mcp_init', 999999 );

add_filter( 'wp_get_nav_menu_items', function ( $items ) {
    if ( !class_exists( 'WooCommerce' ) ) {
        return $items;
    }
    $to_hide = array();
    if ( !is_admin() ) {
        foreach ( $items as $key => $item ) {
            $product_id = carbon_get_nav_menu_item_meta( $item->ID, 'mcp_product' );
            if ( $product_id != 0 ) {
                $current_user = wp_get_current_user();
                if ( $current_user ) {
                    $is_owner = wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id );
                    if ( !$is_owner ) {
                        $to_hide[$key] = $item->ID;
                    }
                }
            }
        }
    }
    foreach ( $to_hide as $key => $value ) {
        unset( $items[$key] );
    }
    return $items;
} );

add_action( 'wp_footer', function () {
    /* $products = wc_get_products([
    'posts_per_page'=>-1,
    'post_status'=>'publish'
    ]);
    $_products = [];
    foreach($products as $product){
    $_products[] = [$product->get_name(),$product->get_id()];
    }
    D::print_r($_products); */
    global $wpdb;
    $_posts = $wpdb->get_results("SELECT ID, post_title from {$wpdb->prefix}posts WHERE post_status='publish' and post_type='post'",ARRAY_A);
    $posts = [];
    foreach($_posts as $_post){
        $posts[$_post['ID']] = $_post['post_title'];
    }
    D::print_r($posts);
    D::dumpInConsole();
} );