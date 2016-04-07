<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : 
  	$order_name    = $order->billing_first_name . ' ' . $order->billing_last_name;
  	$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
  	$order         = wc_get_order( $order->id );
    $delivery      = get_monum_delivery_time($order) .' '.  __('working days', 'monum');
  	if ( sizeof( $order->get_items() ) > 0 ) {
			foreach( $order->get_items() as $item_id => $item ) {
				$_product  = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				$item_meta = new WC_Order_Item_Meta( $item['item_meta'], $_product );
				if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					if ( $_product && ! $_product->is_visible() ) {
						$product_name = apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
					} else {
						$product_name = apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );
					}
				}
			}
		}	?>

		<p><?php printf(
		__( 'Dear %1$s,<br /><br />Thank you for your order at Monum. The order is being processed and the delivery time of your %2$s is within %3$s.<br />You can check the status of the order yourself by logging in to <a href="%4$s">my account</a>. If you have any questions, please feel free to contact us at <a href="mailto:urnen@monum.nl">urnen@monum.nl</a> otherwise we are available by phone from (mo - fr from 10.00 - 19.00)<br /><br />Sincerely, Olaf Wiggers, Cora Roos, Monum (BCR-YCD BV)', 'monum' ) . ' ', $order_name, $product_name, $delivery, $myaccount_url );
		?></p>

		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
