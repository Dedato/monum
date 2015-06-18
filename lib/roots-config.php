<?php
/**
 * Configuration values
 */
define('GOOGLE_ANALYTICS_ID', 'UA-4455192-22'); // UA-XXXXX-Y (Note: Universal Analytics only, not Classic Analytics)

if (!defined('WP_ENV')) {
  define('WP_ENV', 'production');  // scripts.php checks for values 'production' or 'development'
}