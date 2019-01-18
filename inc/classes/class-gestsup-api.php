<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Helper\Helper;
use function add_action;
use function carbon_get_post_meta;
use function carbon_get_theme_option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Class GestsupAPI
 *
 * @package wpgc\gestsupapi
 */
class GestsupAPI {
	public function __construct() {
		add_action( 'init', array( $this, 'gestsup_mysql' ) );
	}

	/**
	 * @description Connect WP to the GestSup DB
	 * @return string|object return Object wpdb
	 */
	public static function gestsup_mysql() {

		$server   = get_option( '_wpgc_gestsup_host' );
		$db       = get_option( '_wpgc_gestsup_db' );
		$user     = get_option( '_wpgc_gestsup_username' );
		$password = get_option( '_wpgc_gestsup_passwd' );

		if ( empty( $server ) || empty( $db ) || empty ( $user ) || empty( $password ) ) {
			$connect = 'nok';
		} else {

			$connect = new wpdb( $user, $password, $db, $server );

		}

		return $connect;
	}

	public static function wpgc_get_tech() {

		$techs = array();
		$db   = self::gestsup_mysql();
		if ( is_object( $db ) ) {
			$tech = $db->get_results( " SELECT id,firstname, lastname FROM tusers WHERE profile = '4' or profile='0' or profile = '3' ", ARRAY_A );
			foreach ( $tech as $t ){
				$techs[ $t['id'] ] = $t['firstname'] . ' ' .$t['lastname'];
			}
		}

	return $techs;
	}

}

new GestsupAPI();
