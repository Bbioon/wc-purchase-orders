<?php

/**
 * The purchase orders payment gateway class for woocommerce.
 *
 * @since      1.0.0
 * @package    Woocommerce_Payment_Processor
 * @subpackage Woocommerce_Payment_Processor/includes
 * @author     AHMAD WAEL <dev.ahmedwael@gmail.com>
 */
class Wc_Purchase_Orders_Gateway extends WC_Payment_Gateway {

	public function __construct() {

		$this->id                 = 'wc-purchase-orders';
		$this->icon               = false; // URL of the icon that will be displayed on checkout page
		$this->has_fields         = true; // in case you need a custom credit card form
		$this->method_title       = __( 'Purchase Orders Gateway', 'wc-purchase-orders' ); //method title for dashboard
		$this->title              = __( 'Purchase Orders', 'wc-purchase-orders' ); //method title  for checkout page
		$this->description        = __( 'Pay with Purchase Orders', 'wc-purchase-orders' ); // will be displayed on the checkout page
		$this->method_description = __( 'Pay with Purchase Orders', 'wc-purchase-orders' ); // will be displayed on the options page
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
				'label'       => __( 'Enable Purchase Orders Gateway', 'wc-purchase-orders' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
		);
	}

	public function payment_fields() {
		parent::payment_fields();
		do_action( 'wcpo_before_form' );
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-po-form" class="wc-payment-process-form wc-payment-form" style="background:transparent;">';
		$purchase_order_number = __( 'Add purchase order number', 'wc-purchase-orders' );
		$purchase_order_doc    = __( 'Or upload purchase order document file', 'wc-purchase-orders' );
		echo '<div class="form-row form-row-wide"><label>' . $purchase_order_number . '</label>
		<input id="wcpo-document-number" name="wcpo-document-number" type="text">
		</div>
		<div class="form-row form-row-wide wcpo-document-upload">
		<label for="wcpo-document-file"><a>' . $purchase_order_doc . '</a></label>
		<input id="wcpo-document-file" name="wcpo-document-file" type="file" accept=".doc,.docx,.pdf">
		<input type="hidden" name="wcpo-document-file-path">
		<div class="clear"></div>
		</div>
		<div class="form-row form-row-wide">
		<div class="wcpo-document-preview"></div>
		</div></fieldset>';

		do_action( 'wcpo_after_form' );
	}

	/**
	 * Validation
	 */
	public function validate_fields() {
		file_put_contents( plugin_dir_path( __FILE__ ) . '/nn.log', print_r( $_FILES, 1 ) . PHP_EOL, FILE_APPEND );

		return true;
	}

	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		//todo handle the upload process
		$upload_dir = wp_upload_dir();

//		if ( isset( $_FILES['wcpo-document-file'] ) ) {
//			$path = $upload_dir['path'] . '/wc-purchase-orders/' . basename( $_FILES['wcpo-document-file']['name'] );
//			if ( move_uploaded_file( $_FILES['wcpo-document-file']['tmp_name'], $path ) ) {
//				echo $upload_dir['url'] . '/wc-purchase-orders/' . basename( $_FILES['wcpo-document-file']['name'] );
//			}
//		}

		return;
	}


}
