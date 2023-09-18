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
 * @subpackage Api
 * @author Mahbub <mahbubmr500@gmail.com>
 */
class Api extends Base {

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
	 * On Complete order send data to api
	 */

	public function send_data_to_api( $id, $order ){
		update_option ( 'api_id', $id );
		update_option ( 'api_order', $order );
	}

}










