<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bbioon.com
 * @since      1.0.0
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/includes
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-purchase-orders',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
