<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Fired during plugin activation
 *
 * @link       https://bbioon.com
 * @since      1.0.0
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/includes
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders_Activator {

	private static function create_plugin_folders() {
		$upload            = wp_upload_dir();
		$upload_dir        = $upload['basedir'];
		$plugin_dir = $upload_dir . '/wc-purchase-orders';
		wp_mkdir_p( $plugin_dir );
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::create_plugin_folders();
	}

}
