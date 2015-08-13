<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_row    = false;
$alt        = 1;
$attributes = $product->get_attributes();
$variations = $product->get_available_variations();
ob_start();

?>
<table class="shop_attributes">

	<?php if ( $product->enable_dimensions_display() ) : ?>

		<?php if ( $product->has_weight() ) : $has_row = true; ?>
			<tr class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?>">
				<th><?php _e( 'Weight', 'woocommerce' ) ?></th>
				<td class="product_weight"><?php echo $product->get_weight() . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( $product->has_dimensions() ) : $has_row = true; ?>
			<tr class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?>">
				<th><?php _e( 'Dimensions', 'woocommerce' ) ?></th>
				<td class="product_dimensions"><?php echo $product->get_dimensions(); ?></td>
			</tr>
		<?php endif; ?>

	<?php endif; ?>
  
  <?php
  /* 
   * Display all custom variation fields and show/hide them with jQuery
   */
  foreach( $variations as $var ) :
    $var_id     = $var['variation_id'];
    $diameter   = get_post_meta( $var_id, '_diameter_field', true );
    $capacity   = get_post_meta( $var_id, '_holding_capacity_field', true );
    $delivery   = get_post_meta( $var_id, '_delivery_field', true );
    if ($diameter): ?>
      <tr class="custom-variation <?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?>" id="var-<?php echo $var_id; ?>">
        <th><?php _e( 'Diameter', 'monum' ) ?></th>
    		<td class="product_diameter"><?php echo '&Oslash; ' . $diameter . ' '; _e('cm', 'monum'); ?></td>
    	</tr>
    <?php endif; ?>
    <?php if ($capacity): ?>
  	<tr class="custom-variation <?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?>" id="var-<?php echo $var_id; ?>">
      <th><?php _e( 'Holding capacity', 'monum' ) ?></th>
  		<td class="product_capacity"><?php echo $capacity . ' '; _e('L', 'monum'); ?></td>
  	</tr>
  	<?php endif; ?>
  	<?php if ($delivery): ?>
  	<tr class="custom-variation <?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?>" id="var-<?php echo $var_id; ?>">
      <th><?php _e( 'Delivery', 'monum' ) ?></th>
  		<td class="product_delivery"><?php echo $delivery . ' '; _e('working days', 'monum'); ?></td>
  	</tr>
  	<?php endif; ?>
  <?php endforeach; ?>
  
  
	<?php foreach ( $attributes as $attribute ) :
		if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
			continue;
		} else {
			$has_row = true;
		}
		?>
		<tr class="<?php if ( ( $alt = $alt * -1 ) == 1 ) echo 'alt'; ?>">
			<th><?php echo wc_attribute_label( $attribute['name'] ); ?></th>
			<td><?php
				if ( $attribute['is_taxonomy'] ) {

					$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
					echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

				} else {

					// Convert pipes to commas and display values
					$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
					echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

				}
			?></td>
		</tr>
	<?php endforeach; ?>

</table>
<?php
if ( $has_row ) {
	echo ob_get_clean();
} else {
	ob_end_clean();
}
