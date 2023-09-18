<?php
/**
 * All admin facing functions
 */
namespace Codexpert\WpToEpaymaker\App;
use Codexpert\Plugin\Base;
use Codexpert\Plugin\Metabox;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Mahbub <mahbubmr500@gmail.com>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'wp-to-epaymaker', false, WP_TO_PAYMAKER_DIR . '/languages/' );
	}

	/**
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {

		if( ! get_option( 'wp-to-epaymaker_version' ) ){
			update_option( 'wp-to-epaymaker_version', $this->version );
		}
		
		if( ! get_option( 'wp-to-epaymaker_install_time' ) ){
			update_option( 'wp-to-epaymaker_install_time', time() );
		}
	}

	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'WP_TO_PAYMAKER_DEBUG' ) && WP_TO_PAYMAKER_DEBUG ? '' : '.min';
		
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", WP_TO_PAYMAKER ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", WP_TO_PAYMAKER ), [ 'jquery' ], $this->version, true );
	}

	public function footer_text( $text ) {
		if( get_current_screen()->parent_base != $this->slug ) return $text;

		return sprintf( __( 'Built with %1$s by the folks at <a href="%2$s" target="_blank">Codexpert, Inc</a>.' ), '&hearts;', 'mahbubmr500@gmail.com' );
	}

	public function modal() {
		echo '
		<div id="wp-to-epaymaker-modal" style="display: none">
			<img id="wp-to-epaymaker-modal-loader" src="' . esc_attr( WP_TO_PAYMAKER_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}

	function create_custom_post_type() {
		$args = array(
			'labels' => array(
				'name' => 'Books',
				'singular_name' => 'Book',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'books'),
			'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
		);

		register_post_type( 'book', $args );
	}

	
}