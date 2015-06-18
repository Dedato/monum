<?php
/* ==========================================================================
   LESS compiler
   ========================================================================== */
  
function autoCompileLess() {
  
  // include lessc.inc
  require_once( get_stylesheet_directory() .'/assets/less/lessc.inc.php' );
  
  /*$parentFiles = array(    
    'input'   => get_template_directory().'/styles/monum.less',
    'output'  => get_template_directory().'/styles/monum.css'
  );*/
  $childFiles = array(    
    'input'   => get_stylesheet_directory().'/assets/less/main.less',
    'output'  => get_stylesheet_directory().'/assets/css/main.css'
  );
  $compileFiles = array($childFiles);
  
  foreach ($compileFiles as $compileFile) {
    
    // load the cache
    $cacheFile = $compileFile['input'].'.cache';
    if (file_exists($cacheFile)) {
      $cache = unserialize(file_get_contents($cacheFile));
    } else {
      $cache = $compileFile['input'];
    }
    
    // create a new cache object, and compile
    $less = new lessc;
    $less->setFormatter('compressed');
    try {
      $newCache = $less->cachedCompile($cache);
    } catch (Exception $ex) {
      echo "lessphp fatal error: ".$ex->getMessage();
    }

    // output a LESS file, and cache file only if it has been modified since last compile
    if (!is_array($cache) || $newCache['updated'] > $cache['updated']) {
      file_put_contents($cacheFile, serialize($newCache));
      file_put_contents($compileFile['output'], $newCache['compiled']);
    }
  }
  
}
/* Only compile when logged in    
if ( is_user_logged_in() ) {
  add_action('init', 'autoCompileLess');
}*/


/* ==========================================================================
   Wordpress Tweaks
   ========================================================================== */  
  
/* Increase JPG compression */ 
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );

/* Enqueue custom script */
//wp_enqueue_script('monum_js', get_stylesheet_directory_uri() .'/assets/js/monum.js', array(), null, true);



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
