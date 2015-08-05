<?php
/*
Plugin Name: WooCommerce Custom Price Label
Plugin URI: http://coder.fm/items/woocommerce-custom-price-label-plugin
Description: Create any custom price label for any WooCommerce product.
Version: 2.0.0
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Copyright: © 2015 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

// Check if Pro is active, if so then return
if ( in_array( 'woocommerce-custom-price-label-pro/woocommerce-custom-price-label-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

if ( ! class_exists( 'Woocommerce_Custom_Price_Label' ) ) :

/**
 * Main Woocommerce_Custom_Price_Label Class
 *
 * @class Woocommerce_Custom_Price_Label
 */

final class Woocommerce_Custom_Price_Label {

	/**
	 * @var Woocommerce_Custom_Price_Label The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Woocommerce_Custom_Price_Label Instance
	 *
	 * Ensures only one instance of Woocommerce_Custom_Price_Label is loaded or can be loaded.
	 *
	 * @static
	 * @return Woocommerce_Custom_Price_Label - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Woocommerce_Custom_Price_Label Constructor.
	 * @access public
	 */
	public function __construct() {

		$this->custom_tab_group_name = 'simple_is_custom_pricing_label';
		$this->custom_tab_sections = array (
			''           => __( 'Instead of the price', 'woocommerce-custom-price-label' ),
			'_before'    => __( 'Before the price', 'woocommerce-custom-price-label' ),
			'_between'   => __( 'Between regular and sale prices', 'woocommerce-custom-price-label' ),
			'_after'     => __( 'After the price', 'woocommerce-custom-price-label' ),
		);
		$this->custom_tab_section_variations = array (
			'_text'      => '',
			''           => __( 'Enable', 'woocommerce-custom-price-label' ),
			'_home'      => __( 'Hide on home page', 'woocommerce-custom-price-label' ),
			'_products'  => __( 'Hide on products page', 'woocommerce-custom-price-label' ),
			'_single'    => __( 'Hide on single', 'woocommerce-custom-price-label' ),
			'_page'      => __( 'Hide on all pages', 'woocommerce-custom-price-label' ),
			'_cart'      => __( 'Hide on cart page only', 'woocommerce-custom-price-label' ),
			'_variable'  => __( 'Hide for main price', 'woocommerce-custom-price-label' ),
			'_variation' => __( 'Hide for all variations', 'woocommerce-custom-price-label' ),
		);

		// Include required files
		$this->includes();

		add_action( 'init', array( $this, 'init' ), 0 );

		// Settings
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @param mixed $links
	 * @return array
	 */
	public function action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=custom_price_label' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>',
		), $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {

		require_once( 'includes/admin/admin-functions.php' );

		$settings = array();
		$settings[] = require_once( 'includes/admin/class-wc-custom-price-label-settings-general.php' );
		if ( is_admin() ) {
			foreach ( $settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						if ( isset ( $_GET['woocommerce_custom_price_label_admin_options_reset'] ) ) {
							require_once( ABSPATH . 'wp-includes/pluggable.php' );
							if ( is_super_admin() ) {
								delete_option( $value['id'] );
							}
						}
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
		}

		require_once( 'includes/admin/class-wc-custom-price-label-settings-per-product.php' );

		require_once( 'includes/class-wc-custom-price-label.php' );
	}

	/**
	 * Add Woocommerce settings tab to WooCommerce settings.
	 */
	public function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-wc-settings-custom-price-label.php' );
		return $settings;
	}

	/**
	 * Init Woocommerce_Custom_Price_Label when WordPress initialises.
	 */
	public function init() {
		// Set up localisation
		load_plugin_textdomain( 'woocommerce-custom-price-label', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
}

endif;

/**
 * Returns the main instance of Woocommerce_Custom_Price_Label to prevent the need to use globals.
 *
 * @return Woocommerce_Custom_Price_Label
 */
function WCCPL() {
	return Woocommerce_Custom_Price_Label::instance();
}

WCCPL();
