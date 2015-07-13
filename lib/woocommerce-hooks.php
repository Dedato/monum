<?php
/* ==========================================================================
   WooCommerce function overrides
   ========================================================================== */


/* ==========================================================================
   Header
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
		$currency  = get_woocommerce_currency_symbol(); // €
    $dec_sep   = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ); // ,
		?>
		<a class="cart-contents" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your order', 'monum' ); ?>">
  		<span class="price"><?php echo $currency .' '. $woocommerce->cart->subtotal . $dec_sep .'-'; ?></span>
  		<span class="contents"><?php echo sprintf( _n('%d product', '%d products', $woocommerce->cart->get_cart_contents_count(), 'monum' ), $woocommerce->cart->get_cart_contents_count() );?></span>
    </a>
		<?php
	}
}


/* ==========================================================================
   Products
   ========================================================================== */
   
// Remove Shop Product Sorting
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove Showing results functionality site-wide
function woocommerce_result_count() {
  return;
}


/* ==========================================================================
   Single Product
   ========================================================================== */
   
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

// Replace trailing zeros with dash
add_filter( 'wc_price', 'monum_change_trailing_zeros', 10, 2 );
function monum_change_trailing_zeros($price, $args) {
  $dec_sep = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );
  $search  = $dec_sep . '00';
  $replace = $dec_sep . '-';
  $price = preg_replace('/'. $search .'/', $replace, $price);
  return $price;
}

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


/* ==========================================================================
   Checkout
   ========================================================================== */

// Customizing checkout fields
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
  /*$fields['billing']['billing_gender'] = array(
    'type'      => 'radio',
		'options'   => array('Male' => __('Male', 'monum'), 'Female' => __('Female', 'monum')),    
    'label'     => __('Gender', 'monum'),
    'required'  => true,
    'class'     => array('form-row-wide', 'radio-field'),
    'clear'     => true
  );*/
  $fields['order']['order_comments']['placeholder'] = __('For example special notes for delivery.', 'monum');
  $fields['order']['order_comments']['label'] = __('Comments on the order', 'monum');
  return $fields;
}
// Re-order checkout fields
add_filter('woocommerce_checkout_fields', 'order_fields');
function order_fields($fields) {
  $order = array(
    'billing_first_name', 
    'billing_last_name', 
    //'billing_gender',
    'billing_company', 
    'billing_address_1',
    'billing_postcode',
    'billing_city',
    'billing_country', 
    'billing_email', 
    'billing_phone'
  );
  foreach($order as $field) {
    $ordered_fields[$field] = $fields['billing'][$field];
  }
  $fields['billing'] = $ordered_fields;
  return $fields;
}
// Customizing default address fields
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );
function custom_override_default_address_fields( $address_fields ) {
  $address_fields['first_name']['label'] = __('First name or initials', 'monum'); // Edit label
  unset($address_fields['address_2']); // Remove 2nd address
  return $address_fields;
}
/* Update the order meta with field value
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
  if ( ! empty( $_POST['billing_gender'] ) ) {
    update_post_meta( $order_id, __('Gender', 'monum'), esc_attr( $_POST['billing_gender'] ) );
  }
}
// Display field value on the order edit page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta ( $order ) {
  echo '<p><strong>'.__('Gender', 'monum').':</strong> ' . get_post_meta( $order->id, __('Gender', 'monum'), true ) . '</p>';
}*/


/* ==========================================================================
   Emails
   ========================================================================== */

// Add custom styling to email templates
add_action('woocommerce_email_header', 'add_css_to_email', 10, 1);
function add_css_to_email() {
  echo '
  <style type="text/css">
    #template_header_image {width:600px; text-align:left;}
    #template_header_image img {width:270px; height:auto; }
    #template_header h1 {font-size:21px; padding:15px 48px;}
    #body_content_inner .order_item td {vertical-align:top !important;}
    #body_content_inner .order_item td img {vertical-align:top !important; display:block; margin-bottom:10px;}
    #template_footer #credit {text-align:left;}
  </style>';
}

// Add a custom email action to the list of emails WooCommerce should load 
add_filter( 'woocommerce_email_actions', 'add_customer_address_change_woocommerce_email_actions' ); // WC 2.3+
function add_customer_address_change_woocommerce_email_actions( $email_actions ) {
  $email_actions[] = 'woocommerce_customer_save_address';
  return $email_actions;
}
// Add a custom email class to the list of emails WooCommerce should load
add_filter( 'woocommerce_email_classes', 'add_customer_address_change_woocommerce_email_classes' );
function add_customer_address_change_woocommerce_email_classes( $email_classes ) {
	require_once( get_stylesheet_directory() . '/includes/class-wc-customer-address-change-email.php' );
	$email_classes['WC_Customer_Address_Change_Email'] = new WC_Customer_Address_Change_Email();
	return $email_classes;
}

// Send notification email when customer saves address using custom extended WC_Email class
add_action( 'woocommerce_customer_save_address','notify_admin_customer_address_change', 10, 2);
function notify_admin_customer_address_change( $user_id ) {
  global $woocommerce;
	$mailer   = WC()->mailer();
	$WC_Mail  = new WC_Email();
	$My_Class = new WC_Customer_Address_Change_Email; // retrieve custom extended class 
  $mailer->send( $My_Class->get_recipient(), $My_Class->get_subject(), $My_Class->get_content(), $My_Class->get_headers() );
}
