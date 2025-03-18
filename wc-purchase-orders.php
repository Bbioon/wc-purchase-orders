<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.bbioon.com
 * @since             1.0.0
 * @package           BBPO_Purchase_Orders
 *
 * @wordpress-plugin
 * Plugin Name:       WC Purchase Orders
 * Plugin URI:        https://github.com/Bbioon/wc-purchase-orders
 * Description:       Support shop orders as a WooCommerce payment gateway and show this payment gateway to allowed users only.
 * Version:           1.0.1
 * Author:            Ahmad Wael
 * Author URI:        https://www.bbioon.com/about
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-purchase-orders
 * Tested up To:      6.6
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BBPO_PURCHASE_ORDERS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bbpo-purchase-orders-activator.php
 */
function bbpo_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bbpo-purchase-orders-activator.php';
	BBPO_Purchase_Orders_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bbpo-purchase-orders-deactivator.php
 */
function bbpo_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bbpo-purchase-orders-deactivator.php';
	BBPO_Purchase_Orders_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'bbpo_activate' );
register_deactivation_hook( __FILE__, 'bbpo_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bbpo-purchase-orders.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function bbpo_run_wc_shop_orders() {

	$plugin = new BBPO_Purchase_Orders();
	$plugin->run();

}
bbpo_run_wc_shop_orders();
