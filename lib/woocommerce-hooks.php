<?php
/* ==========================================================================
   WooCommerce function overrides
   ========================================================================== */

// Ensure cart contents update when products are added to the cart via AJAX
add_filter( 'add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
if ( ! function_exists( 'woocommerce_header_add_to_cart_fragment' ) ) {
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		woo_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}
}
// Cart Header
if ( ! function_exists( 'woo_cart_link' ) ) {
	function woo_cart_link() {
		global $woocommerce;
		?>
		<a class="cart-contents" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your order', 'monum' ); ?>"><span class="price"><?php echo $woocommerce->cart->get_cart_total(); ?></span><span class="contents"><?php echo sprintf( _n('%d product', '%d products', $woocommerce->cart->get_cart_contents_count(), 'monum' ), $woocommerce->cart->get_cart_contents_count() );?></span></a>
		<?php
	}
}

// Remove Shop Product Sorting
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove Showing results functionality site-wide
function woocommerce_result_count() {
  return;
}

// Change Add To Cart text on normal product pages
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_cart_button_text' ); // 2.1 +
function woo_custom_single_cart_button_text() {
  return __( 'Order', 'monum' );
}
add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_cart_button_text' ); // 2.1 +
function woo_custom_cart_button_text() {
  return __( 'Order', 'monum' );
}

// Custom Added To Cart Messages
add_filter( 'wc_add_to_cart_message', 'monum_custom_add_to_cart_message' );
function monum_custom_add_to_cart_message() {
  global $woocommerce;
  // Output success messages
	if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) :
		$return_to 	= apply_filters( 'woocommerce_continue_shopping_redirect', wp_get_referer() ? wp_get_referer() : home_url() );
		$message 	= sprintf('<a href="%s" class="button wc-forward">%s</a> %s', $return_to, __( 'Return to assortment', 'monum' ), __('Product successfully added to your order.', 'monum') );
	else :
		$message 	= sprintf('<a href="%s" class="button wc-forward">%s</a> %s', wc_get_page_permalink( 'cart' ), __( 'View order', 'monum' ), __('Product successfully added to your order.', 'monum') );
	endif;
	return $message;
}

// Custom Error Messages
add_filter( 'woocommerce_add_error', 'monum_custom_error_message');
function monum_custom_error_message($error) {
  if (get_locale() == 'nl_NL') {
    $error = str_ireplace('winkelmand', 'bestelling', $error);
  } elseif (get_locale() == 'de_DE') {
    $error = str_ireplace('warenkorb', 'Bestellung', $error);
  } else {
    $error = str_ireplace('cart', 'order', $error);
  }  
  return $error;
}


// Change number of thumbnails per row in product galleries
add_filter ( 'woocommerce_product_thumbnails_columns', 'monum_custom_thumb_cols' );
function monum_custom_thumb_cols() {
  return 5;
}

// Add custom ACF product summary underneath gallery thumbnails
add_action( 'woocommerce_product_thumbnails', 'monum_show_product_summary', 20 );
function monum_show_product_summary( ) {
  global $post;
  $summary = get_field('product_summary', $post->ID);
  if ( $summary ) {
    echo '<div class="product-summary">'. $summary .'</div>';
  }
}

// Remove currency symbol on single product
add_filter('woocommerce_currency_symbol', 'monum_remove_wc_currency_symbols', 10, 2);
function monum_remove_wc_currency_symbols( $currency_symbol, $currency ) {
  if (is_product()) {
    $currency_symbol = '';
  }
  return $currency_symbol;
}

// Remove variable price range everywhere
add_filter( 'woocommerce_variable_sale_price_html', 'monum_remove_variation_price_range', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'monum_remove_variation_price_range', 10, 2 );
function monum_remove_variation_price_range( $price ) {
  $price = '';
  return $price;
}

// Always hide trailing zeros
add_filter( 'woocommerce_price_trim_zeros', 'monum_change_trailing_zeros', 10, 2 );
function monum_change_trailing_zeros( $trim ) {
  return true; 
}

// Add suffix to variation add to cart price
function monum_add_price_suffix($format, $currency_pos) {
	$currency  = get_woocommerce_currency(); // EUR
	$dec_sep   = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ); // ,
	$format    = '%1$s%2$s' . $dec_sep . '-'; // -
	return $format;
}
add_action('woocommerce_price_format', 'monum_add_price_suffix', 10, 2);

// Display variation's price even if min and max prices are the same
add_filter('woocommerce_available_variation', 'monum_show_variation_price', 10, 3);
function monum_show_variation_price( $value, $object = null, $variation = null ) {
  if ($value['price_html'] == '') {
    $value['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
  }
  return $value;
}

// Remove quantity selection in all product types
//add_filter( 'woocommerce_is_sold_individually', 'monum_remove_all_quantity_fields', 10, 2 ); 
function monum_remove_all_quantity_fields( $return, $product ) {
  return true;
}

// Rename default tabs
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
	$tabs['additional_information']['title'] = __( 'Specifications', 'monum' );	// Rename the additional information tab
	return $tabs;
}

// Add package contents tab
add_filter( 'woocommerce_product_tabs', 'monum_new_product_tab' );
function monum_new_product_tab( $tabs ) {
	$tabs['package_contents'] = array(
		'title' 	=> __( 'Package Contents', 'monum' ),
		'priority' 	=> 50,
		'callback' 	=> 'monum_package_contents_tab_content'
	);
	return $tabs;
}
// Add package contents panel
function monum_package_contents_tab_content() {
  global $post;
  $contents = get_field('product_package_contents', $post->ID);
  if ( $contents ) {
    echo $contents;
  }
}

// Add term label above swatches
add_filter( 'woocommerce_swatches_picker_html', 'monum_custom_swatches_picker_html', 10, 2 );
function monum_custom_swatches_picker_html( $picker, $swatch ){
  $tax_slug = $swatch->taxonomy_slug;
  $st_slug  = $swatch->term_slug;
  $st_name  = $swatch->term_label;
  $st_desc  = $swatch->term->description;
  $out      .= '<div id="'. $tax_slug .'_label" class="attribute_'. $tax_slug .'_picker_label swatch-label">'. $st_name .'</div>';
  $out      .= $picker;
  if ($st_desc) 
    $out .= '<span id="'. $st_slug .'_desc" class="attribute_'. $st_slug .'_picker_desc swatch-desc-tooltip" title="'. $st_desc .'"></span>';
  return $out;
}

// Load PrettyPhoto for the whole site
add_action( 'wp_enqueue_scripts', 'frontend_scripts_include_lightbox' );
function frontend_scripts_include_lightbox() {
  global $woocommerce;
  $suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
  $lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;
  if ( $lightbox_en ) {
    wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
    wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
    wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
  }
}

// Add Payment Type to WooCommerce Admin Email
add_action( 'woocommerce_email_after_order_table', 'add_payment_method_to_admin_new_order', 15, 2 ); 
function add_payment_method_to_admin_new_order( $order, $is_admin_email ) {
  if ( $is_admin_email ) {
    echo '<p><strong>Payment Method:</strong> ' . $order->payment_method_title . '</p>';
  }
}