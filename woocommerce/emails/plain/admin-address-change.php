<?php
/**
 * Admin customer address change email (plain text)
 *
 * @author		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version 	2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Get current user
global $woocommerce;
global $current_user;
get_currentuserinfo();
$countries = new WC_Countries;


echo "= " . $email_heading . " =\n\n";

echo sprintf( __( '%s just updated his billing and/or shipping address:', 'monum' ), $current_user->user_firstname . ' ' . $current_user->user_lastname ) . "\n\n";

echo __( 'Customer details', 'woocommerce' ) . "\n";
echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
echo __('Username','woocommerce') . ": " . $current_user->user_login . "\n";
echo __('Email','woocommerce') . ": " . $current_user->user_email . "\n";
echo __('First name','woocommerce') . ": " . $current_user->user_firstname . "\n";
echo __('Last name','woocommerce') . ": " . $current_user->user_lastname . "\n";

echo __( 'Billing Address', 'woocommerce' ) . "\n";
echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

$billing_country      = get_user_meta( $current_user->ID, 'billing_country', true );
$billing_country_full = ( $billing_country && isset( $countries->countries[ $billing_country ] ) ) ? $countries->countries[ $billing_country ] : $billing_country;

echo __('Country','woocommerce'). ": " . $billing_country_full . "\n";
echo __('First name or initials','monum'). ": " . get_user_meta( $current_user->ID, 'billing_first_name', true ) ."\n";
echo __('Last name','woocommerce') . ": " . get_user_meta( $current_user->ID, 'billing_last_name', true ) . "\n";
echo __('Company Name','woocommerce') . ": " .  get_user_meta( $current_user->ID, 'billing_company', true ) . "\n";
echo __('Address','woocommerce') . ": " . get_user_meta( $current_user->ID, 'billing_address_1', true ) . "\n";
echo __('Postcode','woocommerce') . ": " . get_user_meta( $current_user->ID, 'billing_postcode', true ) . "\n";
echo __('City','woocommerce') . ": " . get_user_meta( $current_user->ID, 'billing_city', true ) . "\n";
echo __('Email','woocommerce') . ": " . get_user_meta( $current_user->ID, 'billing_email', true ) . "\n";
echo __('Telephone','woocommerce') . ": " . get_user_meta( $current_user->ID, 'billing_phone', true ) . "\n";

echo __( 'Shipping Address', 'woocommerce' ) . "\n";
echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

$shipping_country      = $woocommerce->customer->get_shipping_country();
$shipping_country_full = ( $shipping_country && isset( $countries->countries[ $shipping_country ] ) ) ? $countries->countries[ $shipping_country ] : $shipping_country;

echo __('Country','woocommerce'). ": " . $shipping_country_full . "\n";
echo __('First name or initials','monum'). ": " . get_user_meta( $current_user->ID, 'shipping_first_name', true ) ."\n";
echo __('Last name','woocommerce') . ": " . get_user_meta( $current_user->ID, 'shipping_last_name', true ) . "\n";
echo __('Company Name','woocommerce') . ": " . get_user_meta( $current_user->ID, 'shipping_company', true ) . "\n";
echo __('Address','woocommerce') . ": " . get_user_meta( $current_user->ID, 'shipping_address_1', true ) . "\n";
echo __('Postcode','woocommerce') . ": " . get_user_meta( $current_user->ID, 'shipping_postcode', true ) . "\n";
echo __('City','woocommerce') . ": " . get_user_meta( $current_user->ID, 'shipping_city', true ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );