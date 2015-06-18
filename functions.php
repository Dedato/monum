<?php
/* ==========================================================================
   Load the child theme-specific files
   ========================================================================== */
   
$includes = array(
	'lib/child-theme-options.php',   // Custom child theme functions
	'lib/woocommerce-hooks.php', 		 // Woocommerce hooks and filters
	'lib/woocommerce-fields.php', 	 // Custom Woocommerce fields
	'lib/roots-config.php',          // Roots Configuration
	'lib/roots-scripts.php',         // Roots Scripts and stylesheets
);
// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'monum_includes', $includes );
foreach ( $includes as $i ) {
	locate_template( $i, true );
}