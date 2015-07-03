<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<p class="myaccount_user">
	<?php
	printf(
		__( 'Dear %1$s, from my account it is possible to check your recent orders, manage your shipping and billing addresses and <a href="%2$s">edit your password and account details</a>.', 'monum' ) . ' ', $current_user->display_name, wc_customer_edit_account_url() ); ?>
</p>
<?php printf(
  __( '<a class="button logout" href="%1$s">Sign out</a>', 'monum' ) . ' ', wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) ) );
?>

<?php do_action( 'woocommerce_before_my_account' ); ?>

<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'woocommerce_after_my_account' ); ?>
