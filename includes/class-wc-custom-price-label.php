<?php
/**
 * WooCommerce Custom Price Label
 *
 * @version 2.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label' ) ) :

class WC_Custom_Price_Label {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( 'yes' === get_option( 'woocommerce_custom_price_label_enabled' ) ) {
			add_filter( 'woocommerce_cart_product_price',        array( $this, 'custom_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_get_price_html',            array( $this, 'custom_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_get_variation_price_html',  array( $this, 'custom_price' ), PHP_INT_MAX, 2 );
		}
	}

	/*
	 * front end.
	 */
	function custom_price( $price, $product ) {
		if ( is_admin() ) return $price;
		$price = $this->apply_global_price_labels( $price );
		$price = $this->apply_local_price_labels( $price, $product );
		return do_shortcode( $price );
	}

	/*
	 * apply_global_price_labels.
	 */
	private function apply_global_price_labels( $price ) {
		if ( '' != ( $label = get_option( 'woocommerce_global_price_labels_add_before_text' ) ) ) {
			$price = $label . $price;
		}
		if ( '' != ( $label = get_option( 'woocommerce_global_price_labels_add_after_text' ) ) ) {
			$price = $price . $label;
		}
		return $price;
	}

	/*
	 * apply_local_price_labels.
	 */
	private function apply_local_price_labels( $price, $product ) {
		foreach ( WCCPL()->custom_tab_sections as $custom_tab_section => $custom_tab_section_title ) {
			$labels_array = array();
			foreach ( WCCPL()->custom_tab_section_variations as $custom_tab_section_variation => $custom_tab_section_variation_title ) {
				$option_name = WCCPL()->custom_tab_group_name . $custom_tab_section . $custom_tab_section_variation;
				$labels_array[ 'variation' . $custom_tab_section_variation ] = get_post_meta( $product->post->ID, '_' . $option_name, true );
			}
			if ( 'on' === $labels_array[ 'variation' ] ) {
				$current_filter_name = current_filter();
				if (
					( 'on' === $labels_array['variation_home']      && is_front_page() ) ||
					( 'on' === $labels_array['variation_products']  && is_archive() ) ||
					( 'on' === $labels_array['variation_single']    && is_single() ) ||
					( 'on' === $labels_array['variation_page']      && is_page() ) ||
					( 'on' === $labels_array['variation_cart']      && 'woocommerce_cart_product_price' === $current_filter_name ) ||
					( 'on' === $labels_array['variation_variation'] && 'woocommerce_get_variation_price_html' === $current_filter_name ) ||
					( 'on' === $labels_array['variation_variable']  && 'woocommerce_get_price_html' === $current_filter_name && $product->is_type( 'variable' ) )
				) {
					continue;
				}

				$price = $this->customize_price( $price, $custom_tab_section, $labels_array['variation_text'] );
			}
		}
		return $price;
	}

	/*
	 * customize_price - per product.
	 */
	public function customize_price( $price, $custom_tab_section, $custom_label ) {
		switch ( $custom_tab_section ) {
			case '':
				$price = $custom_label;
				break;
			case '_before':
				$price = $custom_label . $price;
				break;
		}
		return str_replace( 'From: ', '', $price );
	}
}

endif;

return new WC_Custom_Price_Label();
