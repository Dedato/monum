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


/* ==========================================================================
   Custom functions
   ========================================================================== */
   
// Split content at the more tag and return an array
function split_content() {
  global $post;
  if( strpos( $post->post_content, '<!--more-->' ) ) {
    $content = preg_split('/<span id="more-\d+"><\/span>/i', get_the_content('more'));
    // first content section in column1
    $ret = '<div class="content_excerpt">'. array_shift($content). ' <a class="read-more" title="'. __('Read more', 'monum') .'" data-text-less="'. __('Read less', 'monum') .'">'. __('Read more', 'monum') .'</a></div>';
    // remaining content sections in column2
    if (!empty($content)) $ret .= '<div class="content_more">'. implode($content). '</div>';
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