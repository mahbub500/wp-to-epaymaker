<?php
/**
 * Plugin Name: Wp To ePayMaker
 * Description: This plugin is for wordpress to ePayMaker payment system.
 * Plugin URI: mahbubmr500@gmail.com
 * Author: Codexpert, Inc
 * Author URI: mahbubmr500@gmail.com
 * Version: 0.9
 * Text Domain: wp-to-epaymaker
 * Domain Path: /languages
 */

namespace Codexpert\WpToEpaymaker;
use Codexpert\Plugin\Notice;
// use Codexpert\Plugin\Feature;
use Pluggable\Marketing\Survey;
use Pluggable\Marketing\Feature;
use Pluggable\Marketing\Deactivator;
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Mahbub <mahbubmr500@gmail.com>
 */
final class Plugin {
	
	/**
	 * Plugin instance
	 * 
	 * @access private
	 * 
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * The constructor method
	 * 
	 * @access private
	 * 
	 * @since 0.9
	 */
	private function __construct() {
		/**
		 * Includes required files
		 */
		$this->include();

		/**
		 * Defines contants
		 */
		$this->define();

		/**
		 * Runs actual hooks
		 */
		$this->hook();
	}

	/**
	 * Includes files
	 * 
	 * @access private
	 * 
	 * @uses composer
	 * @uses psr-4
	 */
	private function include() {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 * 
	 * @access private
	 * 
	 * @uses get_plugin_data
	 * @uses plugin_basename
	 */
	private function define() {

		/**
		 * Define some constants
		 * 
		 * @since 0.9
		 */
		define( 'WP_TO_PAYMAKER', __FILE__ );
		define( 'WP_TO_PAYMAKER_DIR', dirname( WP_TO_PAYMAKER ) );
		define( 'WP_TO_PAYMAKER_ASSET', plugins_url( 'assets', WP_TO_PAYMAKER ) );
		define( 'WP_TO_PAYMAKER_DEBUG', apply_filters( 'wp-to-epaymaker_debug', true ) );

		/**
		 * The plugin data
		 * 
		 * @since 0.9
		 * @var $plugin
		 */
		$this->plugin					= get_plugin_data( WP_TO_PAYMAKER );
		$this->plugin['basename']		= plugin_basename( WP_TO_PAYMAKER );
		$this->plugin['file']			= WP_TO_PAYMAKER;
		$this->plugin['server']			= apply_filters( 'wp-to-epaymaker_server', 'mahbubmr500@gmail.com/dashboard' );
		$this->plugin['min_php']		= '5.6';
		$this->plugin['min_wp']			= '4.0';
		$this->plugin['icon']			= WP_TO_PAYMAKER_ASSET . '/img/icon.png';
		$this->plugin['depends']		= [ 'woocommerce/woocommerce.php' => 'WooCommerce' ];
		
	}

	/**
	 * Hooks
	 * 
	 * @access private
	 * 
	 * Executes main plugin features
	 *
	 * To add an action, use $instance->action()
	 * To apply a filter, use $instance->filter()
	 * To register a shortcode, use $instance->register()
	 * To add a hook for logged in users, use $instance->priv()
	 * To add a hook for non-logged in users, use $instance->nopriv()
	 * 
	 * @return void
	 */
	private function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 */
			$admin = new App\Admin( $this->plugin );
			$admin->activate( 'install' );
			$admin->action( 'admin_footer', 'modal' );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->action( 'admin_footer_text', 'footer_text' );
			$admin->action( 'int', 'create_custom_post_type' );

			/**
			 * Settings related hooks
			 */
			$settings = new App\Settings( $this->plugin );
			$settings->action( 'plugins_loaded', 'init_menu' );

			/**
			 * Renders different notices
			 * 
			 * @package Codexpert\Plugin
			 * 
			 * @author Mahbub <mahbubmr500@gmail.com>
			 */
			$notice = new Notice( $this->plugin );

			/**
			 * Alters featured plugins
			 * 
			 * @package Codexpert\Plugin
			 * 
			 * @author Mahbub <mahbubmr500@gmail.com>
			 */
			$feature = new Feature( WP_TO_PAYMAKER );

		else : // !is_admin() ?

			/**
			 * Front facing hooks
			 */
			$front = new App\Front( $this->plugin );
			$front->action( 'wp_head', 'head' );
			$front->action( 'wp_footer', 'modal' );
			$front->action( 'wp_enqueue_scripts', 'enqueue_scripts' );
			// $front->action( 'woocommerce_order_status_completed', 'order_complete' );

			/**
			 * Shortcode related hooks
			 */
			$shortcode = new App\Shortcode( $this->plugin );
			$shortcode->register( 'profile', 'profile' );

		endif;

		/**
		 * Cron facing hooks
		 */
		$cron = new App\Cron( $this->plugin );
		$cron->activate( 'install' );
		$cron->deactivate( 'uninstall' );

		/**
		 * Common hooks
		 *
		 * Executes on both the admin area and front area
		 */
		$common = new App\Common( $this->plugin );
		$common->action( 'woocommerce_order_status_completed', 'order_complete' );

		/**
		 * Api hooks
		 *
		 * Executes on both the admin area and front area
		 */
		$api = new App\Api( $this->plugin );
		$api->action( 'woocommerce_order_payment_status_changed', 'send_data_to_api', 10, 1 );

		/**
		 * AJAX related hooks
		 */
		$ajax = new App\AJAX( $this->plugin );
		$ajax->priv( 'store_nid', 'nid' );
	}

	/**
	 * Cloning is forbidden.
	 * 
	 * @access public
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 * 
	 * @access public
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 * 
	 * @access public
	 * 
	 * @return $_instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();