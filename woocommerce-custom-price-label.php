<?php
/*
Plugin Name: WooCommerce Custom Price Label
Plugin URI: http://www.algoritmika.com/shop/wordpress-woocommerce-custom-price-label-plugin/
Description: This plugin extends the WooCommerce e-commerce plugin by allowing to create custom price labels for products (like 'Call for Price' etc.).
Version: 1.0.0
Author: Algoritmika Ltd.
Author URI: http://www.algoritmika.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
?>
<?php
if ( ! class_exists( 'woo_cpl_plugin' ) ) {
	class woo_cpl_plugin{
		public function __construct(){
		
			add_action( 'add_meta_boxes', array($this, 'add_price_label_meta_box'));
			add_filter( 'woocommerce_price_html', array($this, 'custom_price'), 99, 2);
			add_filter( 'woocommerce_empty_price_html', array($this, 'custom_price'), 99, 2);
			add_filter( 'woocommerce_sale_price_html', array($this, 'custom_price'), 99, 2);
			add_action( 'save_post', array($this, 'save_custom_price_labels'), 10, 2);
			add_action( 'admin_head-post.php', array($this, 'add_my_script'), 100);			
		}	
		
		public function add_my_script()
		{
			?><script type="text/javascript">
			function toggle_visibility(id) {
			   var e = document.getElementById(id);
			   if(e.style.display == 'block')
				  e.style.display = 'none';
			   else
				  e.style.display = 'block';
			}
			</script><?php
		}
		
		public function save_custom_price_labels($post_id, $post)
		{
			$product = get_product( $post );
			//if ( $product->is_type('simple') || $product->is_type('external') )
			//{
				if ( isset( $_POST['simple_is_custom_pricing_label'] ) ) 
					update_post_meta( $post_id, '_simple_is_custom_pricing_label', $_POST['simple_is_custom_pricing_label']  );
				else
					update_post_meta( $post_id, '_simple_is_custom_pricing_label', 'off'  );
					
				if ( isset( $_POST['simple_is_custom_pricing_label_home'] ) ) 
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_home', $_POST['simple_is_custom_pricing_label_home']  );
				else
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_home', 'off'  );
					
				if ( isset( $_POST['simple_is_custom_pricing_label_products'] ) ) 
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_products', $_POST['simple_is_custom_pricing_label_products']  );
				else
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_products', 'off'  );
					
				if ( isset( $_POST['simple_is_custom_pricing_label_single'] ) ) 
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_single', $_POST['simple_is_custom_pricing_label_single']  );
				else
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_single', 'off'  );					
					
				if ( isset( $_POST['simple_is_custom_pricing_label_text'] ) ) 
					update_post_meta( $post_id, '_simple_is_custom_pricing_label_text',  $_POST['simple_is_custom_pricing_label_text'] );
			//}
		}		
		
		public function add_price_label_meta_box() {
			add_meta_box( 'woocommerce-price-label', 'Custom Price Label', array($this, 'woocommerce_price_label'), 'product', 'normal', 'high' );
		}
		
		public function woocommerce_price_label()
		{
			$current_post_id = get_the_ID();
			$is_label = get_post_meta($current_post_id, '_simple_is_custom_pricing_label', true);
			$is_label_home = get_post_meta($current_post_id, '_simple_is_custom_pricing_label_home', true);
			$is_label_products = get_post_meta($current_post_id, '_simple_is_custom_pricing_label_products', true);
			$is_label_single = get_post_meta($current_post_id, '_simple_is_custom_pricing_label_single', true);
			$label_text = get_post_meta($current_post_id, '_simple_is_custom_pricing_label_text', true);
			$label_text = str_replace ('"', '&quot;', $label_text);
			$checked = '';
			$checked_home = '';
			$checked_products = '';
			$checked_single = '';
			if ($is_label === 'on') $checked = 'checked';
			if ($is_label_home === 'on') $checked_home = 'checked';
			if ($is_label_products === 'on') $checked_products = 'checked';
			if ($is_label_single === 'on') $checked_single = 'checked';
			?>
			<p>Label:&nbsp;<input style="width:250px;" type="text" name="simple_is_custom_pricing_label_text" id="simple_is_custom_pricing_label_text" value="<?=$label_text?>" <?php/*=$disabled*/?>/>&nbsp;Use this label instead of price?&nbsp;<input class="checkbox" type="checkbox" <?php/*onclick="toggleLabelText('simple_is_custom_pricing_label', 'simple_is_custom_pricing_label_text')"*/?> name="simple_is_custom_pricing_label" id="simple_is_custom_pricing_label" <?=$checked?> /></p>
			<p>Hide this label on:&nbsp;<em>Home page</em>&nbsp;<input class="checkbox" type="checkbox" name="simple_is_custom_pricing_label_home" id="simple_is_custom_pricing_label_home" <?=$checked_home?> />&nbsp;<em>Products page</em>&nbsp;<input class="checkbox" type="checkbox" name="simple_is_custom_pricing_label_products" id="simple_is_custom_pricing_label_products" <?=$checked_products?> />&nbsp;<em>Single product</em>&nbsp;<input class="checkbox" type="checkbox" name="simple_is_custom_pricing_label_single" id="simple_is_custom_pricing_label_single" <?=$checked_single?> /></p>
			<p><a href="#" onclick="toggle_visibility('custom_label_ao');">Show/Hide Advanced Options</a></p>
			<div id="custom_label_ao" style="display:none;">
			<p>*You will need <a href="http://www.algoritmika.com/shop/wordpress-woocommerce-custom-price-label-pro/">WooCommerce Custom Price Label Pro plugin</a> to change settings below.</p>
			<hr />			
			<p>Label:&nbsp;<input style="width:250px;" type="text" name="simple_is_custom_pricing_label_text_before" id="simple_is_custom_pricing_label_text_before" disabled />&nbsp;Use this label before price?&nbsp;<input class="checkbox" type="checkbox" disabled name="simple_is_custom_pricing_label_before" id="simple_is_custom_pricing_label_before" /></p>
			<p>Hide this label on:&nbsp;<em>Home page</em>&nbsp;<input class="checkbox" type="checkbox" disabled />&nbsp;<em>Products page</em>&nbsp;<input class="checkbox" type="checkbox" disabled />&nbsp;<em>Single product</em>&nbsp;<input class="checkbox" type="checkbox" disabled /></p>
			<hr />
			<p>Label:&nbsp;<input style="width:250px;" type="text" name="simple_is_custom_pricing_label_text_between" id="simple_is_custom_pricing_label_text_between" disabled />&nbsp;Use this label between regular and sale price?&nbsp;<input class="checkbox" type="checkbox" disabled name="simple_is_custom_pricing_label_between" id="simple_is_custom_pricing_label_between" /></p>
			<p>Hide this label on:&nbsp;<em>Home page</em>&nbsp;<input class="checkbox" type="checkbox" disabled />&nbsp;<em>Products page</em>&nbsp;<input class="checkbox" type="checkbox" disabled />&nbsp;<em>Single product</em>&nbsp;<input class="checkbox" type="checkbox" disabled /></p>
			<hr />
			<p>Label:&nbsp;<input style="width:250px;" type="text" name="simple_is_custom_pricing_label_text_after" id="simple_is_custom_pricing_label_text_after" disabled />&nbsp;Use this label before price?&nbsp;<input class="checkbox" type="checkbox" disabled name="simple_is_custom_pricing_label_after" id="simple_is_custom_pricing_label_after" /></p>
			<p>Hide this label on:&nbsp;<em>Home page</em>&nbsp;<input class="checkbox" type="checkbox" disabled />&nbsp;<em>Products page</em>&nbsp;<input class="checkbox" type="checkbox" disabled />&nbsp;<em>Single product</em>&nbsp;<input class="checkbox" type="checkbox" disabled /></p>
			<hr />
			</div>
			<?php
		}
		
		public function custom_price($price, $product)
		{
			//if ( $product->is_type('simple') || $product->is_type('external') )
			//{
				$is_label = get_post_meta($product->post->ID, '_simple_is_custom_pricing_label', true);
				$is_label_home = get_post_meta($product->post->ID, '_simple_is_custom_pricing_label_home', true);
				$is_label_products = get_post_meta($product->post->ID, '_simple_is_custom_pricing_label_products', true);
				$is_label_single = get_post_meta($product->post->ID, '_simple_is_custom_pricing_label_single', true);
				$label_text = get_post_meta($product->post->ID, '_simple_is_custom_pricing_label_text', true);
				
				if ($is_label === 'on')
				{				
					if (($is_label_home == 'off') && (is_front_page())) return $label_text;
					if (($is_label_products == 'off') && (is_archive())) return $label_text;
					if (($is_label_single == 'off') && (is_single())) return $label_text;					
					//return $label_text;
				}
			//}
			return $price;
		}		
						
	}
}

$woo_cpl_plugin = &new woo_cpl_plugin();