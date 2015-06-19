<?php
/* ==========================================================================
   Roots setup
   ========================================================================== */ 
function roots_setup() {
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
  global $more;
  $more = true;
  $content = preg_split('/<span id="more-\d+"><\/span>/i', get_the_content('more'));
  // first content section in column1
  $ret = '<div class="content_excerpt">'. array_shift($content). ' <a class="read-more" title="'. __('Read more', 'monum') .'" data-text-less="'. __('Read less', 'monum') .'">'. __('Read more', 'monum') .'</a></div>';
  // remaining content sections in column2
  if (!empty($content)) $ret .= '<div class="content_more">'. implode($content). '</div>';
  return apply_filters('the_content', $ret);
}
