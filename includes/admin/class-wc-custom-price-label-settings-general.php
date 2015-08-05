<?php
/**
 * WooCommerce Custom Price Label - General Section Settings
 *
 * @version 2.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Settings_General' ) ) :

class WC_Custom_Price_Label_Settings_General {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id   = 'general';
		$this->desc = __( 'General', 'woocommerce-custom-price-label' );

		add_filter( 'woocommerce_get_sections_custom_price_label',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_custom_price_label_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_settings.
	 */
	function get_settings() {

		$desc = wccpl_get_pro_message();

		$settings = array(

			array(
				'title'     => __( 'Custom Price Label Options', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'id'        => 'woocommerce_custom_price_label_options',
			),

			array(
				'title'     => __( 'WooCommerce Custom Price Label', 'woocommerce-custom-price-label' ),
				'desc'      => '<strong>' . __( 'Enable', 'woocommerce-custom-price-label' ) . '</strong>',
				'desc_tip'  => __( 'Create any custom price label for any WooCommerce product.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_custom_price_label_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),

			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_custom_price_label_options',
			),

			array(
				'title'     => __( 'Global Custom Price Labels', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you set price labels for all products globally.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_options',
			),

			array(
				'title'     => __( 'Add before the price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to add before all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_add_before_text',
				'default'   => '',
				'type'      => 'textarea',

				'css'       => 'width:30%;min-width:300px;',
			),

			array(
				'title'     => __( 'Add after the price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to add after all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_add_after_text',
				'default'   => '',
				'type'      => 'textarea',
				'css'       => 'width:30%;min-width:300px;',
			),

			array(
				'title'     => __( 'Add between regular and sale prices', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to add between regular and sale prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_between_regular_and_sale_text',
				'default'   => '',
				'type'      => 'textarea',
				'desc'      => $desc,
				'custom_attributes'
				            => array( 'readonly' => 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),

			array(
				'title'     => __( 'Remove from price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to remove from all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_remove_text',
				'default'   => '',
				'type'      => 'textarea',
				'desc'      => $desc,
				'custom_attributes'
				            => array( 'readonly' => 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),

			array(
				'title'     => __( 'Replace in price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to replace in all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_replace_text',
				'default'   => '',
				'type'      => 'textarea',
				'desc'      => $desc,
				'custom_attributes'
				            => array( 'readonly' => 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),

			array(
				'title'     => '',
				'desc_tip'  => __( 'Enter text to replace with. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_replace_with_text',
				'default'   => '',
				'type'      => 'textarea',
				'desc'      => $desc,
				'custom_attributes'
				            => array( 'readonly' => 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),

			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_global_price_labels_options',
			),
		);

		return $settings;
	}

}

endif;

return new WC_Custom_Price_Label_Settings_General();
