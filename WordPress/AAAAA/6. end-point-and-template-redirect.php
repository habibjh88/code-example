<?php
/**
 * PostSubmissionController Controller class.
 *
 * @package RT_TPG
 */

namespace RT\ThePostGrid\Controllers;

// Do not allow directly accessing this file.
use RT\ThePostGrid\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class PostSubmissionController {

	public static $endpoint = 'my-account';
	/**
	 * PageTemplateController constructor
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'add_booking_endpoint' ] );
		add_action( 'template_redirect', [ __CLASS__, 'booking_confirmation_template' ], 99999 );
	}


	public static function add_booking_endpoint() {

		if ( self::$endpoint ) {
			add_rewrite_endpoint( self::$endpoint, EP_PERMALINK );
		}
		flush_rewrite_rules();
	}

	public static function booking_confirmation_template() {
		global $wp_query;

		if ( self::$endpoint ) {

			if ( ! isset( $wp_query->query_vars['name'] ) || self::$endpoint !== $wp_query->query_vars['name'] ) {
				return;
			}

			$data = [
				'layout'  => 'dashboard',
				'post_id' => get_the_ID(),
				'user_id' => get_current_user_id(),
			];

			// Functions::get_template_html( 'booking/confirmation-form', $data, '', rtclBooking()->get_plugin_template_path() );
			// Fns::get_template( 'my-account/dashboard', $data );

			if ( ! is_user_logged_in() ) {
				wp_login_form();
			} else {
				Fns::tpg_template( $data, 'my-account' );
			}
		}

		exit;
	}
}
