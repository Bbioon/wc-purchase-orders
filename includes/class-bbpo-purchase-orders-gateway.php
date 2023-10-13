<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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
		$this->method_title       = esc_html__( 'Purchase Orders Gateway', 'wc-purchase-orders' ); //method title for dashboard
		$this->title              = $this->get_option( 'title' ); //method title  for checkout page
		$this->description        = $this->get_option( 'description' ); // will be displayed on the checkout page
		$this->method_description = esc_html__( 'Pay with Purchase Orders', 'wc-purchase-orders' ); // will be displayed on the options page
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
				'label'       => esc_html__( 'Enable Purchase Orders Gateway', 'wc-purchase-orders' ),
				'type'        => 'checkbox',
				'description' => '',
			),
			'title'   => array(
				'title'       => esc_html__( 'Title', 'wc-purchase-orders' ),
				'type'        => 'text',
				'default'     => 'Purchase Orders',
			),
			'description'   => array(
				'title'       => esc_html__( 'Description', 'wc-purchase-orders' ),
				'type'        => 'textarea',
			),
		);
	}

	public function payment_fields() {
		parent::payment_fields();
		do_action( 'wcpo_before_form' );
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-po-form" class="wc-payment-process-form wc-payment-form" style="background:transparent;">';
		wp_nonce_field('payment_form','purchase-order-nonce');
		echo '<div class="form-row form-row-wide wcpo-document-upload">
		<label for="wcpo-document-file"><a>' . esc_html__( 'Upload purchase order document file', 'wc-purchase-orders' ) . '</a></label>
		<input id="wcpo-document-file" name="wcpo-document-file" type="file" accept=".doc,.docx,.pdf">
		<input type="hidden" name="wcpo-document-file-path">
		<div class="clear"></div>
		</div>
		<div class="form-row form-row-wide">
		<div class="wcpo-document-preview"></div>
		</div>
		<div class="form-row form-row-wide"><label>' . esc_html__( 'Add purchase order number (optional)', 'wc-purchase-orders' ) . '</label>
		<input id="wcpo-document-number" name="wcpo-document-number" type="text">
		</div></fieldset>';

		do_action( 'wcpo_after_form' );
	}

	/**
	 * Validation
	 */
	public function validate_fields() {


		return true;
	}

	private function rename_document( $file_path, $order_id ) {
		$file_name     = basename( $file_path );
		$ext           = pathinfo( $file_name, PATHINFO_EXTENSION );
		$path          = dirname( $file_path );
		$new_file_name = 'purchase-order-' . $order_id . '.' . $ext;
		rename( wp_upload_dir()['basedir'] . $file_path, wp_upload_dir()['basedir'] . $path . DIRECTORY_SEPARATOR . $new_file_name );

		return $path . DIRECTORY_SEPARATOR . $new_file_name;
	}

	public function process_payment( $order_id ) {
		global $woocommerce;
		$order      = wc_get_order( $order_id );
		$upload_dir = wp_upload_dir();

		$nonce = sanitize_text_field( $_POST['purchase-order-nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'payment_form' ) ) {
			wc_add_notice( esc_html__( 'Nonce verification failed. Please try again.', 'wc-purchase-orders' ), 'error' );

			return;
		}

		if ( ! empty( $_POST['wcpo-document-file-path'] ) ) {
			$document_file_path = sanitize_text_field( $_POST['wcpo-document-file-path'] );
			if ( file_exists( $upload_dir['basedir'] . $document_file_path ) ) {
				$new_file_name = $this->rename_document( $document_file_path, $order_id );
				$order->update_meta_data( '_purchase_order_file_path', sanitize_text_field( $new_file_name ) );
			} else {
				wc_add_notice( esc_html__( 'Unable to locate the purchase order file, please try uploading it again', 'wc-purchase-orders' ), 'error' );

				return;
			}
		} else {
			wc_add_notice( esc_html__( 'Purchase order file is required', 'wc-purchase-orders' ), 'error' );

			return;
		}

		if ( ! empty( $_POST['wcpo-document-number'] ) ) {
			$order->update_meta_data( '_purchase_order_number', sanitize_text_field( $_POST['wcpo-document-number'] ) );
		}
		$order->update_status( 'on-hold', esc_html__( 'Awaiting purchase order review', 'wc-purchase-orders' ) );

		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			$order->reduce_order_stock();
		} else {
			wc_reduce_stock_levels( $order_id );
		}

		$woocommerce->cart->empty_cart();

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}
}
