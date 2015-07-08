<?php
/**
 * Customer address change email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Get current user
global $woocommerce;
global $current_user;
get_currentuserinfo();
$countries = new WC_Countries;
?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( '%s just updated his billing and/or shipping address:', 'monum' ), $current_user->user_firstname . ' ' . $current_user->user_lastname ); ?></p>

<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
<table cellspacing="0" cellpadding="4" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<tbody>  	
		<tr class="order_item">  
    	<td width="50%"><?php _e('Username','woocommerce'); ?></td>
      <td width="50%"><?php echo $current_user->user_login; ?></td>
  	</tr>
    <tr class="order_item">  
    	<td width="50%"><?php _e('Email','woocommerce'); ?></td>
      <td width="50%"><?php echo $current_user->user_email; ?></td>
  	</tr>
  	<tr class="order_item">
    	<td width="50%"><?php _e('First name','woocommerce'); ?></td>
      <td width="50%"><?php echo $current_user->user_firstname; ?></td>
  	</tr>
    <tr class="order_item">  
    	<td width="50%"><?php _e('Last Name','woocommerce'); ?></td>
      <td width="50%"><?php echo $current_user->user_lastname; ?></td>
  	</tr>
	</tbody>
</table>

<h2><?php _e( 'Billing Address', 'woocommerce' ); ?></h2>
<table cellspacing="0" cellpadding="4" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<tbody>		
  	<tr class="order_item">	
    	<td width="50%"><?php _e('Country','woocommerce'); ?></td>
      <td width="50%">
        <?php
        $billing_country      = get_user_meta( $current_user->ID, 'billing_country', true );
        $billing_country_full = ( $billing_country && isset( $countries->countries[ $billing_country ] ) ) ? $countries->countries[ $billing_country ] : $billing_country;
      	echo $billing_country_full;
        ?>
      </td>
  	</tr>
  	<tr class="order_item">  
    	<td width="50%"><?php _e('First name or initials','monum'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_first_name', true ); ?></td>
  	</tr>
  	<tr class="order_item">
    	<td width="50%"><?php _e('Last name','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_last_name', true ); ?></td>
  	</tr>
    <tr class="order_item">  
    	<td width="50%"><?php _e('Company Name','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_company', true ); ?></td>
  	</tr>
  	<tr class="order_item">
    	<td width="50%"><?php _e('Address','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_address_1', true ); ?></td>
  	</tr>
    <tr class="order_item">
    	<td width="50%"><?php _e('Postcode','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_postcode', true ); ?></td>
  	</tr>
    <tr class="order_item">
    	<td width="50%"><?php _e('City','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_city', true ); ?></td>
  	</tr>
    <tr class="order_item">
    	<td width="50%"><?php _e('Email','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_email', true ); ?></td>
  	</tr>
    <tr class="order_item">
    	<td width="50%"><?php _e('Telephone','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'billing_phone', true ); ?></td>
  	</tr>
	</tbody>
</table>

<h2><?php _e( 'Shipping Address', 'woocommerce' ); ?></h2>
<table cellspacing="0" cellpadding="4" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<tbody>
  	<tr class="order_item">	
    	<td width="50%"><?php _e('Country','woocommerce'); ?></td>
      <td width="50%">
        <?php
        $shipping_country      = $woocommerce->customer->get_shipping_country();
        $shipping_country_full = ( $shipping_country && isset( $countries->countries[ $shipping_country ] ) ) ? $countries->countries[ $shipping_country ] : $shipping_country;
      	echo $shipping_country_full;
        ?>
      </td>
  	</tr>
  	<tr class="order_item">  
    	<td width="50%"><?php _e('First name or initials','monum'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'shipping_first_name', true ); ?></td>
  	</tr>
  	<tr class="order_item">
    	<td width="50%"><?php _e('Last name','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'shipping_last_name', true ); ?></td>
  	</tr>
    <tr class="order_item">  
    	<td width="50%"><?php _e('Company Name','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'shipping_company', true ); ?></td>
  	</tr>
  	<tr class="order_item">
    	<td width="50%"><?php _e('Address','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'shipping_address_1', true ); ?></td>
  	</tr>
    <tr class="order_item">
    	<td width="50%"><?php _e('Postcode','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'shipping_postcode', true ); ?></td>
  	</tr>
    <tr class="order_item">
    	<td width="50%"><?php _e('City','woocommerce'); ?></td>
      <td width="50%"><?php echo get_user_meta( $current_user->ID, 'shipping_city', true ); ?></td>
  	</tr>
	</tbody>
	<tfoot></tfoot>
</table>

<?php do_action( 'woocommerce_email_footer' ); ?>