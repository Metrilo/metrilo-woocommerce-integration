<?php
/**
 * Plugin Name: Metrilo for WooCommerce
 * Plugin URI: https://www.metrilo.com/woocommerce-analytics
 * Description: One-click WooCommerce integration with Metrilo eCommerce Analytics
 * Version: 2.0.2
 * Author: Metrilo
 * Author URI: https://www.metrilo.com/?ref=wpplugin
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Metrilo_Woo_Analytics' ) ) :

class Metrilo_Woo_Analytics {


	public function __construct() {
		add_action('plugins_loaded', array($this, 'init'));
		add_action('before_woocommerce_init', array($this, 'declare_hpos_compatibility'));
    add_filter('query_vars', array($this, 'add_clear_query_var'), 10, 1);
    add_filter('query_vars', array($this, 'add_endpoint_query_vars'), 10, 1);
	}

	public function init(){
		// Checks if WooCommerce is installed and activated.
		if ( class_exists( 'WC_Integration' ) ) {
			// Include our integration class.
			include_once 'includes/integration.php';

			// Register the integration.
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
		} else {
			// throw an admin error if you like
		}
	}

  public function declare_hpos_compatibility(){
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
      \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
  }

  public function add_clear_query_var($vars){
    $vars[] = 'metrilo_clear';
    return $vars;
  }

  public function add_endpoint_query_vars($vars){
    $vars[] = 'metrilo_endpoint';
    $vars[] = 'req_id';
    $vars[] = 'recent_orders_sync_days';
    $vars[] = 'metrilo_order_ids';
    return $vars;
  }

	public function add_integration($integrations){
		$integrations[] = 'Metrilo_Woo_Analytics_Integration';
		return $integrations;
	}

}

$MetriloWooAnalytics = new Metrilo_Woo_Analytics(__FILE__);


endif;

?>
