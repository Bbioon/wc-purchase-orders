<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Define the registration of the purchase orders payment gateway.
 *
 * @since      1.0.0
 * @package    Woocommerce_Payment_Processor
 * @subpackage Woocommerce_Payment_Processor/includes
 * @author     AHMAD WAEL <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders_Gateway_Registration {

	/**
	 * Load the payment gateway class
	 *
	 * @since    1.0.0
	 */
	public function load_payment_gateway_class( $gateways ) {

		$gateways[] = 'Wc_Purchase_Orders_Gateway'; // your class name is here

		return $gateways;

	}

	/**
	 * Load the payment gateway
	 */
	public function load_payment_gateway() {
		if ( class_exists( 'woocommerce' ) ) { //check if woocommerce is installed and activated
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bbpo-purchase-orders-gateway.php';
		}
	}

	/**
	 * Display purchase orders payment gateway only for allowed users.
	 * @param $available_gateways array of payment gateways ids
	 *
	 * @return array of payment gateways ids
	 */
	public function allowed_purchase_order_users( $available_gateways ) {
		$user_id             = get_current_user_id();
		$can_use_purchase_orders = get_user_meta( $user_id, 'wcpo_can_user_purchase_orders', true ) === 'yes';
		if ( isset( $available_gateways['wc-purchase-orders'] ) && ! $can_use_purchase_orders ) {
			unset( $available_gateways['wc-purchase-orders'] );
		}

		return $available_gateways;
	}

}
