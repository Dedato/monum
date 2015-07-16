<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $woocommerce;

?><!DOCTYPE html>
<!--[if lt IE 7]>  <html class="no-js ie ie6 lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>     <html class="no-js ie ie7 lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>     <html class="no-js ie ie8 lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>     <html class="no-js ie ie9 lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '-'); ?></title>
<?php woo_meta(); ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<?php
wp_head();
woo_head();
?>
<script type="text/javascript" src="https://s3-eu-west-1.amazonaws.com/assets.cookieconsent.silktide.com/1.0.10/plugin.min.js"></script>
<script type="text/javascript">
// <![CDATA[
cc.initialise({
	cookies: {
		analytics: {},
		necessary: {}
	},
	settings: {
		consenttype: "implicit",
		hideprivacysettingstab: true,
		style: "light",
		onlyshowbanneronce: true,
		disableallsites: true,
		useSSL: false,
		refreshOnConsent: true
	},
	strings: {
		notificationTitleImplicit: 'Monum.nl maakt gebruik van cookies om je bezoek aan onze website zo optimaal mogelijk te maken. Deze cookies slaan geen persoonlijke gegevens op. <a href="http://monum.dd-webtest.com/privacy-en-cookies/">Meer informatie</a>.',
	}
});
// ]]>
</script>
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div class="wrapper">

    <?php woo_header_before(); ?>

	<header id="header" class="col-full">
		<?php woo_header_inside(); ?>

	    <hgroup>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php if ( is_woocommerce_activated() && isset( $woo_options['woocommerce_header_cart_link'] ) && 'true' == $woo_options['woocommerce_header_cart_link'] ) { ?>
	        	<nav class="nav cart">
	        		<?php woo_cart_link(); ?>
	       		</nav>
	        <?php } ?>
			<span class="nav-toggle"><a href="#navigation"><span><?php _e( 'Navigation', 'woothemes' ); ?></span></a></span>
		</hgroup>

        <?php woo_nav_before(); ?>

	</header><!-- /#header -->

</div><!--/.wrapper-->

<nav id="navigation" class="col-full" role="navigation">

	<div class="main-nav-inner">

		<?php
		if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav', 'theme_location' => 'primary-menu' ) );
		} else {
		?>
	    <ul id="main-nav" class="nav">
			<?php if ( is_page() ) $highlight = 'page_item'; else $highlight = 'page_item current_page_item'; ?>
			<li class="<?php echo $highlight; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
			<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
		</ul><!-- /#nav -->
	    <?php } ?>

	    <?php woo_nav_after(); ?>

	</div><!--/.main-nav-inner-->

</nav><!-- /#navigation -->

<?php woo_content_before(); ?>

<div class="wrapper">