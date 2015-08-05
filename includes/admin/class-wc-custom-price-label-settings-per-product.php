<?php
/**
 * WooCommerce Custom Price Label - Per Product Settings
 *
 * @version 2.0.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Settings_Per_Product' ) ) :

class WC_Custom_Price_Label_Settings_Per_Product {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes',    array( $this, 'add_price_label_meta_box' ) );
		add_action( 'save_post_product', array( $this, 'save_custom_price_labels' ), PHP_INT_MAX, 2 );
	}

	/**
	 * save_custom_price_labels.
	 */
	public function save_custom_price_labels( $post_id, $post ) {
		if ( ! isset( $_POST['woocommerce_price_labels_save_post'] ) ) return;
		foreach ( WCCPL()->custom_tab_sections as $custom_tab_section => $custom_tab_section_title ) {
			foreach ( WCCPL()->custom_tab_section_variations as $custom_tab_section_variation => $custom_tab_section_variation_title ) {
				$option_name = WCCPL()->custom_tab_group_name . $custom_tab_section . $custom_tab_section_variation;
				if ( isset( $_POST[ $option_name ] ) ) {
					update_post_meta( $post_id, '_' . $option_name, $_POST[ $option_name ] );
				} else if ( '_text' != $custom_tab_section_variation ) {
					update_post_meta( $post_id, '_' . $option_name, 'off' );
				}
			}
		}
	}

	/*
	 * add_price_label_meta_box.
	 */
	public function add_price_label_meta_box() {
		add_meta_box(
			'wc-custom-price-labels',
			__( 'Custom Price Labels', 'woocommerce-custom-price-label' ),
			array( $this, 'price_label_meta_box' ),
			'product',
			'normal',
			'high'
		);
	}

	/*
	 * price_label_meta_box.
	 */
	public function price_label_meta_box() {

		$current_post_id = get_the_ID();

		$html = '';
		$html .= '<table style="width:100%;">';

		$html .= '<tr>';
		foreach ( WCCPL()->custom_tab_sections as $custom_tab_section => $custom_tab_section_title ) {
			$html .= '<td style="width:25%;"><h4>' . $custom_tab_section_title . '</h4></td>';
		}
		$html .= '</tr>';

		$html .= '<tr>';
		foreach ( WCCPL()->custom_tab_sections as $custom_tab_section => $custom_tab_section_title ) {
			$html .= '<td style="width:25%;">';
			$html .= '<ul>';
			foreach ( WCCPL()->custom_tab_section_variations as $custom_tab_section_variation => $custom_tab_section_variation_title ) {
				$option_name = WCCPL()->custom_tab_group_name . $custom_tab_section . $custom_tab_section_variation;
				if ( $custom_tab_section_variation == '_text' ) {
					$disabled_if_no_plus = ( '' != $custom_tab_section && '_before' != $custom_tab_section ) ? 'readonly' : '';
					$label_text = get_post_meta( $current_post_id, '_' . $option_name, true );
					$label_text = str_replace ( '"', '&quot;', $label_text );
					$html .= '<li>' . $custom_tab_section_variation_title
								. '<textarea style="width:95%;min-width:100px;height:100px;" ' . $disabled_if_no_plus . ' name="' . $option_name . '">'
								. $label_text . '</textarea></li>';
				} else {
					if ( '_home' === $custom_tab_section_variation )
						$html .= '<li><h5>' . __( 'Hide by page type', 'woocommerce-custom-price-label' ) . '</h5></li>';
					if ( '_variable' === $custom_tab_section_variation )
						$html .= '<li><h5>' . __( 'Variable products', 'woocommerce-custom-price-label' ) . '</h5></li>';
					$disabled_if_no_plus = ( '' != $custom_tab_section && '_before' != $custom_tab_section ) ? 'disabled' : '';
					$html .= '<li><input class="checkbox" type="checkbox" '
								. $disabled_if_no_plus . ' name="' . $option_name . '" id="' . $option_name . '" '
								. checked( get_post_meta( $current_post_id, '_' . $option_name, true ), 'on', false ) . ' /> '
								. $custom_tab_section_variation_title . '</li>';
				}
			}
			$html .= '</ul>';
			$html .= '</td>';
		}
		$html .= '</tr>';

		$html .= '<tr>';
		foreach ( WCCPL()->custom_tab_sections as $custom_tab_section => $custom_tab_section_title ) {
			$disabled_if_no_plus = ( '' != $custom_tab_section && '_before' != $custom_tab_section ) ? '<em>' . wccpl_get_pro_message() . '</em>' : '';
			$html .= '<td style="width:25%;">' . $disabled_if_no_plus . '</td>';
		}
		$html .= '</tr>';

		$html .= '</table>';
		$html .= '<input type="hidden" name="woocommerce_price_labels_save_post" value="woocommerce_price_labels_save_post">';
		echo $html;
	}

}

endif;

return new WC_Custom_Price_Label_Settings_Per_Product();
