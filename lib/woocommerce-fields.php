<?php
/* ==========================================================================
   WooCommerce custom product fields for variations
   ========================================================================== */

add_action( 'woocommerce_product_after_variable_attributes', 'woo_add_variable_fields', 10, 3 ); // Display Fields
add_action( 'woocommerce_product_after_variable_attributes_js', 'woo_add_variable_fields_js' ); // JS to add fields for new variations
add_action( 'woocommerce_process_product_meta_variable', 'woo_save_variable_fields', 10, 1 ); // Save variation fields

/**
 * Create new fields for variations
 */
function woo_add_variable_fields( $loop, $variation_data, $variation ) { ?>
	<tr>
		<td>
			<?php
			// Number Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_diameter_field['.$loop.']', 
					'label'       => __( 'Diameter (cm):', 'monum' ), 
					'value'       => get_post_meta( $variation->ID, '_diameter_field', true ),
					'custom_attributes' => array(
						'step' 	=> 'any',
						'min'	  => '0'
          ) 
				)
			);
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php
  		// Holding Capacity Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_holding_capacity_field['.$loop.']', 
					'label'       => __( 'Holding capacity (L):', 'monum' ), 
					'value'       => get_post_meta( $variation->ID, '_holding_capacity_field', true )
				)
			); ?>
		</td>
	</tr>
<?php }

/**
 * Create new fields for new variations
 */
function woo_add_variable_fields_js() { ?>
  <tr>
		<td>
			<?php
			// Number Field
			woocommerce_wp_text_input( 
				array( 
					'id'                => '_diameter_field[ + loop + ]', 
					'label'             => __( 'Diameter (cm):', 'monum' ), 
					'value'             => '',
					'custom_attributes' => array(
						'step' 	=> 'any',
						'min'	=> '0'
          ) 
				)
			); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php
			// Text Field
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_holding_capacity_field[ + loop + ]', 
					'label'       => __( 'Holding capacity (L):', 'monum' ),
					'value'       => ''
				)
			); ?>
		</td>
	</tr>
<?php }

/**
 * Save new fields for variations
 */
function woo_save_variable_fields( $post_id ) {
	if (isset( $_POST['variable_sku'] ) ) :

		$variable_sku          = $_POST['variable_sku'];
		$variable_post_id      = $_POST['variable_post_id'];
		
    // Number Field
		$_number_field = $_POST['_diameter_field'];
		for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $_number_field[$i] ) ) {
				update_post_meta( $variation_id, '_diameter_field', stripslashes( $_number_field[$i] ) );
			}
		endfor;
		
		// Text Field
		$_text_field = $_POST['_holding_capacity_field'];
		for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
			$variation_id = (int) $variable_post_id[$i];
			if ( isset( $_text_field[$i] ) ) {
				update_post_meta( $variation_id, '_holding_capacity_field', stripslashes( $_text_field[$i] ) );
			}
		endfor;
		
	endif;
}