<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bbioon.com
 * @since      1.0.0
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/public
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders_Public {

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
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-purchase-orders-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-purchase-orders-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'wcpo_object', [
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'icons_url'     => plugin_dir_url( __FILE__ ) . 'icons/',
			'nonce'         => wp_create_nonce( 'wcpo-nonce' ),
			'max_file_size' => esc_html__( 'Max file size for the shop order document is 2MB', 'wc-purchase-orders' ),
			'upload_error'  => esc_html__( 'Failed to upload the file, please try again!', 'wc-purchase-orders' ),
		] );

	}

}
