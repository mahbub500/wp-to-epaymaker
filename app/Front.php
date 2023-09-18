<?php
/**
 * All public facing functions
 */
namespace Codexpert\WpToEpaymaker\App;
use Codexpert\Plugin\Base;
use Codexpert\WpToEpaymaker\Helper;
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author Mahbub <mahbubmr500@gmail.com>
 */
class Front extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	public function head() {
		$order_id 		= get_option( 'Test_order' );
		$order 			= new \WC_Order( $order_id );

		$order_total 	= $order->get_subtotal();
		$currency 		= $order->get_currency(); 
		$billing_f_name = $order->get_billing_first_name(); 
		$billing_l_name = $order->get_billing_last_name(); 
		$billing_location 	= $order->get_billing_address_1(); 
		$billing_email 		= $order->get_billing_email(); 
		$billing_postcode 	= $order->get_billing_postcode(); 

		$endpoint = 'https://epaymaker.com/api/check/purchase';

		$body = [ 
			'totalAmount' 	=> $order_total,
			'currency' 		=> $currency,
			'firstName' 	=> $billing_f_name,
			'lastName' 		=> $billing_l_name,
			'address1' 		=> $billing_location,
			'postalCode' 	=> $billing_postcode,
			'email' 		=> $billing_email,
		];

		$body = wp_json_encode( $body );

		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'method'      => 'POST',
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];

		$response = wp_remote_post( $endpoint, $options );

		// Helper::pri( $response );
// 		{
//     "txnReferenceID": "txnReferenceID",
//     "merchantId": "EPM53962246683",
//     "number": "4242424242424242",
//     "mode": "test",
//     "expirationMonth": "08",
//     "expirationYear": "2025",
//     "securityCode": "039",
//     "totalAmount": "60",
//     "currency": "USD",
//     "firstName": "hsblco",
//     "lastName": "solution",
//     "address1": "1 Market St",
//     "locality": "dhaka",
//     "postalCode": "94105",
//     "country": "US",
//     "email": "hsblco-user@gmail.com",
//     "merchantPassword": "$2y$10$z8c.3HobTEbvw9EwMk6Dz.eGdovmbsPxrtBQCU/dw0squKcnfDz7W"
// }

		// wp_send_post( '' )
		// Helper::pri( $billing_email );
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'WP_TO_PAYMAKER_DEBUG' ) && WP_TO_PAYMAKER_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", WP_TO_PAYMAKER ), '', $this->version, 'all' );

		wp_enqueue_style( 'boostrap', 'https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css', '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", WP_TO_PAYMAKER ), [ 'jquery' ], $this->version, true );
		
		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'	=> wp_create_nonce(),
		];
		wp_localize_script( $this->slug, 'WP_TO_PAYMAKER', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function modal() {
		echo '
		<div id="wp-to-epaymaker-modal" style="display: none">
			<img id="wp-to-epaymaker-modal-loader" src="' . esc_attr( WP_TO_PAYMAKER_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}

	
}