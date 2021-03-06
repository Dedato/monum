<?php
/* ==========================================================================
   Roots setup
   ========================================================================== */ 
function roots_setup() {
  // Add post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');
  // Add custom slider sizes
  add_image_size('slider_large', 1680, 670, true); // desktop - tablet landscape
  add_image_size('slider_medium', 1024, 408, true); // tablet landscape - tablet portrait
  add_image_size('slider_small', 768, 306, true); // tablet portrait - mobile
  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'roots_setup');


/* ==========================================================================
   Wordpress Tweaks
   ========================================================================== */  
  
/* Increase JPG compression */ 
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );
   
/* Custom style Wordpress login page */
function wp_custom_login() { 
	echo '<link rel="stylesheet" type="text/css" href="'. get_stylesheet_directory_uri() .'/assets/css/wp-admin.css" />'; 
}
// Change url logo Wordpress login page
function put_my_url(){
	return (get_home_url());
}
// Custom style Wordpress dashboard
function wp_custom_admin() { 
	echo '<link rel="stylesheet" type="text/css" href="'. get_stylesheet_directory_uri() .'/assets/css/wp-admin.css" />'; 
}
add_action('login_head', 'wp_custom_login');
add_filter('login_headerurl', 'put_my_url');
add_action('admin_head', 'wp_custom_admin'); 

// Allow SVG uploads
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


/* ==========================================================================
   Peddlar Overrides
   ========================================================================== */

function woo_post_meta( ) { ?>
<aside class="post-meta">
	<ul>
		<li class="post-date">
			<span><?php the_time( get_option( 'date_format' ) ); ?></span>
		</li>
		<li class="post-author">
			<?php the_author_posts_link(); ?>
		</li>
		<li class="post-category">
			<?php the_category( ', ') ?>
		</li>
		<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<li class="edit">', '</li>' ); ?>
	</ul>
</aside>
<?php
}

// Override woo_logo
if ( ! function_exists( 'woo_logo' ) ) {
  function woo_logo () {
  	global $woo_options;
  	if ( isset( $woo_options['woo_texttitle'] ) && $woo_options['woo_texttitle'] == 'true' ) return; // Get out if we're not displaying the logo.
  
  	$logo = esc_url( get_template_directory_uri() . '/images/logo.png' );
  	if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
  	if ( is_ssl() ) { $logo = str_replace( 'http://', 'https://', $logo ); }
    ?>
  	<a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
    	<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" onerror="this.src='<?php echo get_stylesheet_directory_uri() .'/assets/img/Monum-logo.png'; ?>'">
  	</a>
  <?php
  }
}
add_action( 'woo_header_inside', 'woo_logo' );

/* 
 * Remove Peddlar Actions
 * Remove this action if you want to use the custom font styling set in the Peddlar theme options
 */
add_action( 'after_setup_theme', 'remove_peddlar_theme_styles', 0);
function remove_peddlar_theme_styles() {
  remove_action('woo_head', 'woo_custom_styling');
  remove_action('woo_head', 'woo_custom_typography'); // Typo settings & Google Webfont
}

/*
 * Override woothemes_wp_head
 * Remove this action if you want to use the custom stylesheets set in the Peddlar theme options
 */
if ( ! function_exists( 'woothemes_wp_head' ) ) {
  function woothemes_wp_head() {
  	do_action( 'woothemes_wp_head_before' );
  	// Output custom favicon
  	if ( function_exists( 'woo_output_custom_favicon' ) )
  		woo_output_custom_favicon();
  	// Output CSS from standarized styling options
  	if ( function_exists( 'woo_head_css' ) )
  		woo_head_css();
  	// Output shortcodes stylesheet
  	if ( function_exists( 'woo_shortcode_stylesheet' ) )
  		woo_shortcode_stylesheet();
  	do_action( 'woothemes_wp_head_after' );
  }
}
add_action( 'wp_head', 'woothemes_wp_head', 10 );


/* ==========================================================================
   Custom functions
   ========================================================================== */
   
// Split content at the more tag and return an array
function split_content() {
  global $post;
  if( strpos( $post->post_content, '<!--more-->' ) ) {
    $content = preg_split('/<span id="more-\d+"><\/span>/i', get_the_content('more'));
    $ret     = '<div class="content_excerpt">'. array_shift($content). '</div>';
    if (!empty($content)) $ret .= '<div class="content_more">'. implode($content). '</div>';
    $ret .= '<div class="more_link"><a class="read-more" title="'. __('Read more', 'monum') .'" data-text-less="'. __('Read less', 'monum') .'">'. __('Read more', 'monum') .'</a></div>';
    return apply_filters('the_content', $ret);
  } else {
    return the_content();
  }  
}


/* ==========================================================================
   Role capabilities editor
   ========================================================================== */
   
$editor       = get_role('editor');
$shopmanager  = get_role('shop_manager');
$editor->add_cap('edit_theme_options');
$shopmanager->add_cap('edit_theme_options');


/* ==========================================================================
   WPML Cleanup
   ========================================================================== */
   
define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);