<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The purchase orders file handler.
 *
 * @since      1.0.0
 * @package    Woocommerce_Payment_Processor
 * @subpackage Woocommerce_Payment_Processor/includes
 * @author     AHMAD WAEL <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders_Files {

	/**
	 * Check if the plugin directory exists and is writable.
	 * Display admin notice if the plugin directory is not writable.
	 * Plugin directory: wp-content/uploads/wc-purchase-orders/
	 * @return void
	 */
	public function dir_writable_admin_notice() {
		$upload_dir = wp_upload_dir();
		$writable   = wp_is_writable( $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'wc-purchase-orders' );
		if ( $writable ) {
			return;
		}

		$user_id = get_current_user_id();
		if ( get_user_meta( $user_id, 'wcpo_dismiss_dir_check_notice' ) ) {
			return;
		}

		$class   = 'wc-purchase-orders notice notice-warning is-dismissible';
		$message = __( 'Please make sure this directory is writable to let purchase orders plugin working properly!', 'wc-purchase-orders' ) . ' <code>wp-content/uploads/wc-purchase-orders/</code>';

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Save dismissed notice state into current user.
	 * Used on ajax request.
	 * @return void
	 */
	public function set_admin_notice_dismissed() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wcpo-nonce' ) ) {
			$user_id = get_current_user_id();
			update_user_meta( $user_id, 'wcpo_dismiss_dir_check_notice', 1 );
			wp_send_json_success( [
				'message'     => esc_html__( 'Notice dismissed!', 'wc-purchase-orders' ),
				'notice_type' => 'dir_check'
			] );
		}
		wp_send_json_error( [
			'code'    => 'nonce_failed',
			'message' => esc_html__( 'Failed to pass security check!', 'wc-purchase-orders' )
		] );
	}

	/**
	 * Handle ajax request to upload a purchase order file
	 * @return void
	 */
	public function file_upload() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wcpo-nonce' ) ) {
			$current_year  = date( "Y" );
			$current_month = date( "m" );
			$allowed       = array(
				"application/msword"                                                      => 'doc',
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document" => 'docx',
				"application/pdf"                                                         => 'pdf'
			);
			if ( ! isset( $_FILES['wcpo-document-file'] ) ) {
				wp_send_json_error(
					[
						'code'    => 'file_not_provided',
						'message' => esc_html__( 'This file you are trying to upload is missing!', 'wc-purchase-orders' )
					]
				);
			}
			if ( ! in_array( $_FILES['wcpo-document-file']['type'], array_keys( $allowed ), true ) ) {
				wp_send_json_error(
					[
						'code'    => 'file_not_allowed',
						'message' => esc_html__( 'This file is not allowed!', 'wc-purchase-orders' )
					]
				);
			}
			if ( $_FILES['wcpo-document-file']['size'] > 2097152 ) { // 2 MB (size is also in bytes)
				wp_send_json_error(
					[
						'code'    => 'file_too_big',
						'message' => esc_html__( 'Max file size is 2MB', 'wc-purchase-orders' )
					]
				);
			}
			$upload_dir = wp_upload_dir();
			$dir        = DIRECTORY_SEPARATOR . 'wc-purchase-orders' . DIRECTORY_SEPARATOR . $current_year . DIRECTORY_SEPARATOR . $current_month . DIRECTORY_SEPARATOR;
			$file_name  = md5( date( 'Y-m-d H:i:s:u' ) ) . '.' . pathinfo( basename( sanitize_text_field( $_FILES['wcpo-document-file']['name'] ) ), PATHINFO_EXTENSION );
			$path = $upload_dir['basedir'] . $dir;
			wp_mkdir_p( $path );
			$file = wp_handle_upload( $_FILES['wcpo-document-file'], [
				'test_type' => true,
				'action'    => 'wcpo_upload_purchase_order',
				'mimes'     => array(
					'pdf'  => 'application/pdf',
					'doc'  => 'application/msword',
					'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				),
			] );
			if ( $file && ! isset( $file['error'] ) ) {
				$new_file_path = $path . $file_name;
				rename( $file['file'], $new_file_path );
				wp_send_json_success( [
					'file_url'  => $upload_dir['baseurl'] . $dir . $file_name,
					'file_path' => $dir . $file_name,
					'file_type' => sanitize_text_field( $allowed[ $_FILES['wcpo-document-file']['type'] ] )
				] );
			}
			wp_send_json_error(
				[
					'code'    => 'file_error',
					'message' => esc_html( $file['error'] ),
				]
			);
		}
		wp_send_json_error(
			[
				'code'    => 'nonce_failed',
				'message' => esc_html__( 'Failed to pass security check!', 'wc-purchase-orders' )
			]
		);
	}

	/**
	 * Handle ajax request to delete uploaded purchase order file
	 * @return void
	 */
	public function delete_file() {
		if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wcpo-nonce' ) ) {
			if ( empty( $_POST['file_path'] ) ) {
				wp_send_json_error(
					[
						'code'    => 'file_path_required',
						'message' => esc_html__( 'File path is required', 'wc-purchase-orders' )
					]
				);
			}
			if ( file_exists( wp_upload_dir()['basedir'] . sanitize_text_field( $_POST['file_path'] ) ) ) {
				//delete file
				unlink( wp_upload_dir()['basedir'] . sanitize_text_field( $_POST['file_path'] ) );
				wp_send_json_success( [
					'message' => esc_html__( 'File deleted', 'wc-purchase-orders' )
				] );
			} else {
				wp_send_json_error(
					[
						'code'    => 'file_not_found',
						'message' => esc_html__( 'File requested is not exists!', 'wc-purchase-orders' )
					]
				);
			}
		} else {
			wp_send_json_error(
				[
					'code'    => 'nonce_failed',
					'message' => esc_html__( 'Failed to pass security check!', 'wc-purchase-orders' )
				]
			);
		}
	}

	/**
	 * Let WordPress know about DOCX and DOC files.
	 *
	 * @return mixed
	 */
	function check_filetypes( $data, $file, $filename, $mimes, $real_mime ) {
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}
		$wp_file_type = wp_check_filetype( $filename, $mimes );
		if ( 'docx' === $wp_file_type['ext'] ) {
			$data['ext']  = 'docx';
			$data['type'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		} elseif ( 'doc' === $wp_file_type['ext'] ) {
			$data['ext']  = 'doc';
			$data['type'] = 'application/msword';
		}

		return $data;
	}
}
