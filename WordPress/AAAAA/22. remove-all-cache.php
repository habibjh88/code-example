<?php 

//Remove all cache manually
site_cache_clear();
function site_cache_clear() {
    wp_cache_flush();

    $cleared_cache = false;

    if ( function_exists( 'wp_cache_clear_cache' ) ) {
        wp_cache_clear_cache();
        $cleared_cache = true;
    }

    if ( function_exists( 'w3tc_flush_all' ) ) {
        w3tc_flush_all();
        $cleared_cache = true;
    }

    if ( function_exists( 'opcache_reset' ) && ! ini_get( 'opcache.restrict_api' ) ) {
        @opcache_reset();
        $cleared_cache = true;
    }

    if ( function_exists( 'rocket_clean_domain' ) ) {
        rocket_clean_domain();
        $cleared_cache = true;
    }

    if ( function_exists( 'wp_cache_clear_cache' ) ) {
        wp_cache_clear_cache();
        $cleared_cache = true;
    }

    global $wp_fastest_cache;
    if ( method_exists( 'WpFastestCache', 'deleteCache' ) && ! empty( $wp_fastest_cache ) ) {
        $wp_fastest_cache->deleteCache();
        $cleared_cache = true;
    }

    //If your host has installed APC cache this plugin allows you to clear the cache from within WordPress
    if ( function_exists( 'apc_clear_cache' ) ) {
        apc_clear_cache();
        $cleared_cache = true;
    }

    if ( function_exists( 'fvm_purge_all' ) ) {
        fvm_purge_all();
        $cleared_cache = true;
    }

    if ( class_exists( 'autoptimizeCache' ) ) {
        autoptimizeCache::clearall();
        $cleared_cache = true;
    }

    //WPEngine
    if ( class_exists( 'WpeCommon' ) ) {
        if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
            WpeCommon::purge_memcached();
        }
        if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
            WpeCommon::clear_maxcdn_cache();
        }
        if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
            WpeCommon::purge_varnish_cache();
        }

        $cleared_cache = true;
    }

    if ( class_exists( 'Cache_Enabler_Disk' ) && method_exists( 'Cache_Enabler_Disk', 'clear_cache' ) ) {
        Cache_Enabler_Disk::clear_cache();
        $cleared_cache = true;
    }

    //Perfmatters
    if ( class_exists( 'Perfmatters\CSS' ) && method_exists( 'Perfmatters\CSS', 'clear_used_css' ) ) {
        Perfmatters\CSS::clear_used_css();
        $cleared_cache = true;
    }

    if ( defined( 'BREEZE_VERSION' ) ) {
        do_action( 'breeze_clear_all_cache' );
        $cleared_cache = true;
    }

    if ( function_exists( 'sg_cachepress_purge_everything' ) ) {
        sg_cachepress_purge_everything();
        $cleared_cache = true;
    }

    if ( defined( 'FLYING_PRESS_VERSION' ) ) {
        do_action( 'flying_press_purge_everything:before' );

        @unlink( FLYING_PRESS_CACHE_DIR . '/preload.txt' );

        // Delete all files and subdirectories
        FlyingPress\Purge::purge_everything();

        @mkdir( FLYING_PRESS_CACHE_DIR, 0755, true );

        do_action( 'flying_press_purge_everything:after' );

        $cleared_cache = true;
    }

    if ( class_exists( '\LiteSpeed\Purge' ) ) {
        \LiteSpeed\Purge::purge_all();
        $cleared_cache = true;
    }

    return $cleared_cache;

}