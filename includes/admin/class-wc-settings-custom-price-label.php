<?php
/**
 * WooCommerce Custom Price Label - Settings
 *
 * @version 2.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Settings_Custom_Price_Label' ) ) :

class WC_Settings_Custom_Price_Label extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	function __construct() {

		$this->id    = 'custom_price_label';
		$this->label = __( 'Custom Price Label', 'woocommerce-custom-price-label' );

		parent::__construct();
	}
	
	/**
	 * get_settings.
	 */
	public function get_settings() {
		global $current_section;
		$the_current_section = ( '' != $current_section ) ? $current_section : 'general';
		return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $the_current_section, array() );
	}

	/**
	 * Output sections.
	 */
	public function output_sections() {

	}
}

endif;

return new WC_Settings_Custom_Price_Label();
