<?php
/**
 * All Shortcode related functions
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
 * @subpackage Shortcode
 * @author Mahbub <mahbubmr500@gmail.com>
 */
class Shortcode extends Base {

    public $plugin;

    /**
     * Constructor function
     */
    public function __construct( $plugin ) {
        $this->plugin   = $plugin;
        $this->slug     = $this->plugin['TextDomain'];
        $this->name     = $this->plugin['Name'];
        $this->version  = $this->plugin['Version'];
    }

	public function profile() {
		$html = '
		<div class="container">
		<div class="row">
		<form id="nid_submit" >
		
		<div class="col-md-3"></div>	
		<div class="col-md-6">
			<div class="form-group">
			    <label for="wptp_name">Name</label>
			    <input type="text" class="form-control" id="wptp_name" placeholder="Enter name">
			</div>

			<div class="form-group">
				<label for="wptp_f_name">Father Name</label>
			    <input type="text" class="form-control" id="wptp_f_name" placeholder="Enter Father Name">
			</div>
				<div class="form-group">
				<label for="wptp_nid">Nid</label>
				<input type="number" class="form-control" id="wptp_nid" placeholder="Nid">
				</div>
		
				<button  type="submit" class="btn btn-primary">Submit</button>
			</form>
			</div>	
		<div class="col-md-3"></div>
			</div>
		</div>

		';

		return $html;

    }
}