<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bbioon.com
 * @since      1.0.0
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/admin
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Purchase_Orders_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Purchase_Orders_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-purchase-orders-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Purchase_Orders_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Purchase_Orders_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-purchase-orders-admin.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'wcpo_object', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'wcpo-nonce' ),
		) );
	}

	/**
	 * Display pruchase order information.
	 *
	 * @param $order
	 *
	 * @return void
	 */
	public function purchase_order_data_admin( $order ) {
		$payment_gateway = $order->get_payment_method();
		if ( $payment_gateway === 'wc-purchase-orders' ) {
			$upload_dir = wp_upload_dir();
			?>
            <br class="clear"/>
            <h3><?php esc_html_e( 'Purchase Order Details', 'wc-purchase-orders' ) ?></h3>
			<?php
			$file_path = $order->get_meta( '_purchase_order_file_path' );
			if ( $file_path ) {
				$url = $upload_dir['baseurl'] . $file_path;
				?>
                <div class="purchase-order-document-file">
                    <a href="<?php echo esc_url( $url ) ?>" download>
						<?php esc_html_e( 'Download purchase order attachment', 'wc-purchase-orders' ) ?>
                    </a>
                </div>
				<?php
			}
			$purchase_order_number = $order->get_meta( '_purchase_order_number' );
			if ( $purchase_order_number ) {
				?>
                <br/>
                <div class="purchase-order-number">
                    <strong><?php esc_html_e( 'Purchase Order Number', 'wc-purchase-orders' ) ?>: </strong>
					<?php echo esc_html( $purchase_order_number ) ?>
                </div>
				<?php
			}
		}
	}

	/**
	 * Display the purchase order on order details on email.
	 *
	 * @param $order
	 * @param $sent_to_admin
	 * @param $plain_text
	 *
	 * @return void
	 */
	public function purchase_order_data_email( $order, $sent_to_admin, $plain_text ) {
		$payment_gateway = $order->get_payment_method();
		if ( $payment_gateway === 'wc-purchase-orders' ) {
			$upload_dir = wp_upload_dir();
			?>
            <br class="clear"/>
            <h3><?php esc_html_e( 'Purchase Order Details', 'wc-purchase-orders' ) ?></h3>
			<?php
			$file_path = $order->get_meta( '_purchase_order_file_path' );
			if ( $file_path ) {
				$url = $upload_dir['baseurl'] . $file_path;
				?>
                <div class="purchase-order-document-file">
                    <a href="<?php echo esc_url( $url ) ?>" download>
						<?php esc_html_e( 'Download purchase order attachment', 'wc-purchase-orders' ) ?>
                    </a>
                </div>
				<?php
			}
			$purchase_order_number = $order->get_meta( '_purchase_order_number' );
			if ( $purchase_order_number ) {
				?>
                <br/>
                <div class="purchase-order-number">
                    <strong><?php esc_html_e( 'Purchase Order Number', 'wc-purchase-orders' ) ?>: </strong>
					<?php echo esc_html( $purchase_order_number ) ?>
                </div>
				<?php
			}
		}
	}

	/**
	 * Display the purchase order on order details page.
	 *
	 * @param $order
	 *
	 * @return void
	 */
	public function purchase_order_data_order_details( $order ) {
		$payment_gateway = $order->get_payment_method();
		if ( $payment_gateway === 'wc-purchase-orders' ) {
			$upload_dir = wp_upload_dir();
			$file_path  = $order->get_meta( '_purchase_order_file_path' );
			if ( $file_path ) {
				$url = $upload_dir['baseurl'] . $file_path;
				?>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Purchase Order Details', 'wc-purchase-orders' ) ?>:</th>
                    <td>
                        <a href="<?php echo esc_url( $url ) ?>" download>
							<?php esc_html_e( 'Download purchase order attachment', 'wc-purchase-orders' ) ?>
                        </a>
                    </td>
                </tr>
				<?php
			}
			$purchase_order_number = $order->get_meta( '_purchase_order_number' );
			if ( $purchase_order_number ) {
				?>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Purchase Order Number', 'wc-purchase-orders' ) ?>:</th>
                    <td><?php echo esc_html( $purchase_order_number ) ?></td>
                </tr>
				<?php
			}
		}
	}
}
