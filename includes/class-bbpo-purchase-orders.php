<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bbioon.com
 * @since      1.0.0
 *
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    BBPO_Purchase_Orders
 * @subpackage BBPO_Purchase_Orders/includes
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class BBPO_Purchase_Orders {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      BBPO_Purchase_Orders_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WC_PURCHASE_ORDERS_VERSION' ) ) {
			$this->version = BBPO_PURCHASE_ORDERS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wc-purchase-orders';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_payment_class();
		$this->user_profile_settings();
		$this->handle_file_uploads();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Purchase_Orders_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Purchase_Orders_i18n. Defines internationalization functionality.
	 * - Wc_Purchase_Orders_Admin. Defines all hooks for the admin area.
	 * - Wc_Purchase_Orders_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bbpo-purchase-orders-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bbpo-purchase-orders-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bbpo-purchase-orders-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bbpo-purchase-orders-public.php';

		/**
		 * Load the payment processor registration class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bbpo-purchase-orders-registration.php';

		/**
		 * Load user profile functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bbpo-purchase-orders-user-profile.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bbpo-purchase-orders-files.php';

		$this->loader = new BBPO_Purchase_Orders_Loader();

	}

	private function handle_file_uploads() {

		$file_uploads = new BBPO_Purchase_Orders_Files();

		$this->loader->add_filter( 'wp_check_filetype_and_ext', $file_uploads, 'check_filetypes', 10, 5 );
		$this->loader->add_action( 'admin_notices', $file_uploads, 'dir_writable_admin_notice' );
		$this->loader->add_action( 'wp_ajax_wcpo_dismiss_admin_notice', $file_uploads, 'set_admin_notice_dismissed' );
		$this->loader->add_action( 'wp_ajax_wcpo_upload_purchase_order', $file_uploads, 'file_upload' );
		$this->loader->add_action( 'wp_ajax_wcpo_delete_purchase_order_file', $file_uploads, 'delete_file' );

	}

	private function register_payment_class() {

		$payment_class = new BBPO_Purchase_Orders_Gateway_Registration();

		$this->loader->add_filter( 'woocommerce_payment_gateways', $payment_class, 'load_payment_gateway_class' );
		$this->loader->add_filter( 'woocommerce_available_payment_gateways', $payment_class, 'allowed_purchase_order_users' );
		$this->loader->add_action( 'plugins_loaded', $payment_class, 'load_payment_gateway' );

	}

	private function user_profile_settings() {

		$user_profile = new BBPO_Purchase_Orders_User_Profile();

		$this->loader->add_action( 'show_user_profile', $user_profile, 'user_purchase_orders_enable' );
		$this->loader->add_action( 'edit_user_profile', $user_profile, 'user_purchase_orders_enable' );
		$this->loader->add_action( 'personal_options_update', $user_profile, 'save_purchase_orders_enable' );
		$this->loader->add_action( 'edit_user_profile_update', $user_profile, 'save_purchase_orders_enable' );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Purchase_Orders_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new BBPO_Purchase_Orders_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new BBPO_Purchase_Orders_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin, 'purchase_order_data_admin' );
		$this->loader->add_action( 'woocommerce_email_order_meta', $plugin_admin, 'purchase_order_data_email', 10, 3 );
		$this->loader->add_action( 'woocommerce_order_details_after_order_table_items', $plugin_admin, 'purchase_order_data_order_details' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new BBPO_Purchase_Orders_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    BBPO_Purchase_Orders_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
