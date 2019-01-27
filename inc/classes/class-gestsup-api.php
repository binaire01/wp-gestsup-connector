<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Helper\Helper;
use function add_action;
use function carbon_get_post_meta;
use function carbon_get_theme_option;

namespace WPGC\GestSupAPI;

use function array_push;
use function is_object;

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
		add_action( 'plugins_loaded', array( $this, 'gestsup_mysql' ) );
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

			$connect = new \wpdb( $user, $password, $db, $server );

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

	/**
	 * @return mixed
	 *
	 */
	public static function get_categories(){
		$db   = self::gestsup_mysql();
		if ( is_object( $db ) ) {
			$categories = $db->get_results( " SELECT * FROM tcategory ", ARRAY_A );
			foreach ( $categories as $cat ){
				$cats[ $cat['id']] = $cat['name'];
			}
		}
		return $cats;

	}

	/**
	 * @since 1.5.2
	 * @return mixed
	 *
	 */
	public static function wpgc_get_state(){
		$db = self::gestsup_mysql();
		if ( is_object( $db ) ){
			$states = $db->get_results( " SELECT * FROM tstates ", ARRAY_A );
			/*foreach ( $s as $state ){
				$states[ $state['id']] = $state['name'];
			}*/
		}
		if ( ! empty( $states ) ) {
			return $states;
		}
	}

	/**
	 * @since 1.5.2
	 * @return mixed
	 *
	 */
	public static function wpgc_get_ticket( $state ){
		$db = self::gestsup_mysql();
		if ( is_object( $db ) ){
			$tickets = $db->get_results( "SELECT * FROM tincidents WHERE state LIKE $state AND disable LIKE 0", ARRAY_A );
		}

		if ( ! empty( $tickets ) ) {
			return $tickets;
		}
	}



}

new GestsupAPI();
