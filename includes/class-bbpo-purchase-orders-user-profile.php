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
class BBPO_Purchase_Orders_User_Profile {

	/**
	 * Display user profile fields for enable/disable purchase orders.
	 *
	 * @param $user wp_user object
	 *
	 * @return void
	 */
	public function user_purchase_orders_enable( $user ) {
		?>
        <h3><?php esc_html_e( 'Allow Purchase Orders', 'wc-purchase-orders' ); ?></h3>
        <table class="form-table" role="presentation">
            <tr class="enable-purchase-orders">
                <th scope="row"><?php esc_html_e( 'Purchase Orders', 'wc-purchase-orders' ); ?></th>
                <td>
                    <label for="enable-purchase-orders">
                        <input name="enable-purchase-orders" type="checkbox" id="enable-purchase-orders" value="yes"
							<?php checked( get_user_meta( $user->ID, 'wcpo_can_user_purchase_orders', true ), 'yes' ) ?>>
						<?php esc_html_e( 'Enable purchase orders', 'wc-purchase-orders' ); ?> </label><br>
                </td>
            </tr>
        </table>
		<?php
	}

	/**
	 * Save user profile fields for enable/disable purchase orders
	 *
	 * @param $user_id int user ID
	 *
	 * @return void
	 */
	public function save_purchase_orders_enable( $user_id ) {
		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-user_' . $user_id )
		) {
			return;
		}

		if ( ! empty( $_POST['enable-purchase-orders'] ) && $_POST['enable-purchase-orders'] === 'yes' && current_user_can( 'manage_options' ) ) {
			update_user_meta( $user_id, 'wcpo_can_user_purchase_orders', 'yes' );
			do_action( 'wcpo_user_purchase_orders_enabled', $user_id );
		} else {
			delete_user_meta( $user_id, 'wcpo_can_user_purchase_orders' );
			do_action( 'wcpo_user_purchase_orders_disabled', $user_id );
		}
	}
}
