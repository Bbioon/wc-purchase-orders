<?php

/**
 * The shop orders payment gateway class for woocommerce.
 *
 * @since      1.0.0
 * @package    Woocommerce_Payment_Processor
 * @subpackage Woocommerce_Payment_Processor/includes
 * @author     AHMAD WAEL <dev.ahmedwael@gmail.com>
 */
class Wc_Shop_Orders_Gateway extends WC_Payment_Gateway {

	public function __construct() {

		$this->id                 = 'wc-shop-orders';
		$this->icon               = false; // URL of the icon that will be displayed on checkout page
		$this->has_fields         = true; // in case you need a custom credit card form
		$this->method_title       = __( 'Shop Orders Gateway', 'wc-shop-orders' ); //method title for dashboard
		$this->title              = __( 'Shop Orders', 'wc-shop-orders' ); //method title  for checkout page
		$this->description        = __( 'Pay with Shop Orders', 'wc-shop-orders' ); // will be displayed on the checkout page
		$this->method_description = __( 'Pay with Shop Orders', 'wc-shop-orders' ); // will be displayed on the options page
		//$this->rest_url           = $this->get_option( 'pp_rest_url' );

		$this->init_form_fields();
		$this->init_settings();
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );

	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'       => 'Enable/Disable',
				'label'       => __( 'Enable Payment Processor Gateway', 'wc-shop-orders' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
		);
	}

	public function payment_fields() {

		// display some description before the payment form
		if ( $this->description ) {
			// display the description with <p> tags etc.
			echo wpautop( wp_kses_post( $this->description ) );
		}

		do_action( 'wcso_before_form' );
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-pp-form" class="wc-payment-process-form wc-payment-form" style="background:transparent;">';
		$shop_order_number = __( 'Shop Order number', 'wc-shop-orders' );
		$shop_order_doc    = __( 'Shop Order document file', 'wc-shop-orders' );
		echo '<div class="form-row form-row-wide"><label>' . $shop_order_number . ' <span class="required">*</span></label>
		<input id="wcso-document-number" name="wcso-document-number" type="text" required>
		</div><div class="form-row form-row-wide"><label>' . $shop_order_doc . ' <span class="required">*</span></label>
		<input id="wcso-document-file" name="wcso-document-file" type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
text/plain, application/pdf">
		</div></fieldset>';

		do_action( 'wcso_after_form' );
	}

	/**
	 * Validation
	 */
	public function validate_fields() {
		return true;
	}

	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		// Redirect to the thank you page.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}


}
