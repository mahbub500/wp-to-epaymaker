<?php
/**
 * All AJAX related functions
 */
namespace Codexpert\WpToEpaymaker\App;
use Codexpert\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage AJAX
 * @author Mahbub <mahbubmr500@gmail.com>
 */
class AJAX extends Base {

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

	public function nid() {

		
		$data = $_POST['nid'] ;
		update_option( 'test', $data );
		wp_send_json_success( [
			'status'	=> 1,
			'message'	=> __( 'Email sent successfully', 'cf7-submissions' ),
		], 200 );
	
	}

}





