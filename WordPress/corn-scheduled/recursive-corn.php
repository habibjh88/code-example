<?php 
//1. Need to start the schedular by plugin activator or when you need

register_activation_hook( RTCL_PLUGIN_FILE, 'activate' );

// When you deactivate the plugin you must have to clear all recursive schedule from your plugin
register_deactivation_hook( RTCL_PLUGIN_FILE, 'deactivate' );

//2. 
function activate( $network_wide = null ) {

    //First you need to clear if the schedule exists
    clean_cron_jobs();

    //Then You can add schedule event 
    if ( ! wp_next_scheduled( 'rtcl_cleanup_sessions' ) ) {
        wp_schedule_event( time() + ( 6 * HOUR_IN_SECONDS ), 'twicedaily', 'rtcl_cleanup_sessions' );
    }
    
    if ( ! wp_next_scheduled( 'rtcl_cleanup_temp_listings' ) ) {
        wp_schedule_event( time() + ( 6 * HOUR_IN_SECONDS ), 'twicedaily', 'rtcl_cleanup_temp_listings' );
    }
    
    if ( ! wp_next_scheduled( 'rtcl_hourly_scheduled_events' ) ) {
        wp_schedule_event( time(), 'hourly', 'rtcl_hourly_scheduled_events' );
    }

    if ( ! wp_next_scheduled( 'rtcl_daily_scheduled_events' ) ) {
        $ve = get_option( 'gmt_offset' ) > 0 ? '-' : '+';
        wp_schedule_event( strtotime( '00:00 tomorrow ' . $ve . absint( get_option( 'gmt_offset' ) ) . ' HOURS' ), 'daily', 'rtcl_daily_scheduled_events' );
    }
}

function deactivate() {
    clean_cron_jobs();
}

function clean_cron_jobs() {
    // Un-schedules all previously-scheduled cron jobs
    wp_clear_scheduled_hook( 'rtcl_hourly_scheduled_events' );
    wp_clear_scheduled_hook( 'rtcl_daily_scheduled_events' );
    wp_clear_scheduled_hook( 'rtcl_cleanup_sessions' );
    wp_clear_scheduled_hook( 'rtcl_cleanup_temp_listings' );
}

//See the below path to check schedular callback function reference:
// - plugins/classified-listing/app/Controllers/Admin/Cron.php