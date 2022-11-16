<?php

/**
 * Define the registration of the shop order payment gateway.
 *
 * @since      1.0.0
 * @package    Woocommerce_Payment_Processor
 * @subpackage Woocommerce_Payment_Processor/includes
 * @author     AHMAD WAEL <dev.ahmedwael@gmail.com>
 */
class Wc_Shop_Orders_Gateway_Registration {

	/**
	 * Load the payment gateway class
	 *
	 * @since    1.0.0
	 */
	public function load_payment_gateway_class( $gateways ) {

		$gateways[] = 'Wc_Shop_Orders_Gateway'; // your class name is here

		return $gateways;

	}

	/**
	 * Load the payment gateway
	 */
	public function load_payment_gateway() {
		if ( class_exists( 'woocommerce' ) ) { //check if woocommerce is installed and activated
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-shop-orders-gateway.php';
		}
	}

	/**
	 * Display shop orders payment gateway only for allowed users.
	 * @param $available_gateways array of payment gateways ids
	 *
	 * @return array of payment gateways ids
	 */
	public function allowed_shop_order_users( $available_gateways ) {
		$user_id             = get_current_user_id();
		$can_use_shop_orders = get_user_meta( $user_id, 'wcso_can_user_shop_orders', true ) === 'yes';
		if ( isset( $available_gateways['wc-shop-orders'] ) && ! $can_use_shop_orders ) {
			unset( $available_gateways['wc-shop-orders'] );
		}

		return $available_gateways;
	}

}
