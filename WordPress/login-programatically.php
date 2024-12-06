<?php
error_reporting(E_ERROR & E_WARNING & E_NOTICE);

//Allow iframes from external domains
header('Access-Control-Allow-Origin: *');

//Don't echo output while logging in
ob_start();

//WP Loader
include('wp-load.php');

$user_login = 'admin'; //User ID to login
$user_id = 1;
$user_logged_in = is_user_logged_in();

if ( !$user_logged_in) {
    $user = get_user_by( 'slug', $user_login);
    if(!$user){
           $user = get_user_by( 'id', $user_id);
    }
    if($user) {
        $user_id = $user->ID;
        //$user_login = $user->user_login;
        wp_set_current_user($user_id, $user_login);
        wp_set_auth_cookie($user_id);
        do_action('wp_login', $user_login);
        $user_logged_in = true;
    }
}

ob_end_clean();

if(!$user_logged_in)
{
    $message = array(
        'status'    => 0,
        'message'   => 'Login Failed',
    );
    header('Content-type: application/json');
    echo json_encode($message);
    exit;
}


//Go to the homepage
$domain = "http://" . $_SERVER['HTTP_HOST'];
header('Location: '.$domain);

