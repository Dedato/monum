<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Featured Slider Template
 *
 * Here we setup all HTML pertaining to the featured slider.
 *
 * @package WooFramework
 * @subpackage Template
 */

/* Retrieve the settings and setup query arguments. */
$settings = array(
				'featured_entries' => '3',
				'featured_order' => 'DESC',
				'featured_slide_group' => '0',
				'featured_videotitle' => 'true'
				);

$settings = woo_get_dynamic_values( $settings );

$query_args = array(
				'limit' => $settings['featured_entries'],
				'order' => $settings['featured_order'],
				'term' => $settings['featured_slide_group']
				);

/* Retrieve the slides, based on the query arguments. */
$slides = woo_featured_slider_get_slides( $query_args );

/* Media settings */
$media_width = apply_filters( 'peddlar_media_width', '1024' );
$media_height = apply_filters( 'peddlar_media_height', '768' );
$media_settings = array( 'width' => $media_width, 'height' => $media_height );

if ( 'true' != $settings['featured_videotitle'] ) {
	$media_settings['width'] = $media_width;
	$media_settings['height'] = $media_height;
}

/* Begin HTML output. */
if ( false != $slides ) {
	$count = 0;

	$container_css_class = 'flexslider';

	if ( 'true' == $settings['featured_videotitle'] ) {
		$container_css_class .= ' default-width-slide';
	} else {
		$container_css_class .= ' full-width-slide';
	}
?>
<div id="featured-slider" class="flexslider <?php echo esc_attr( $container_css_class ); ?>">
	<ul class="slides">
<?php
	foreach ( $slides as $k => $post ) {
		setup_postdata( $post );
		$count++;
		
		$urnimage = get_field('slide_urn_image', get_the_ID() );
		$url = get_post_meta( get_the_ID(), 'url', true );
		$layout = get_post_meta( get_the_ID(), '_layout', true );
		$title = get_the_title();
		if ( $title != '' && $url != '' ) {
			$title = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '">' . $title . '</a>';
		}

		$css_class = 'slide-number-' . esc_attr( $count );

		$slide_media = '';
		$embed = woo_embed( 'width=' . intval( $media_settings['width'] ) . '&height=' . intval( $media_settings['height'] ) . '&class=slide-video' );
		if ( '' != $embed ) {
			$css_class .= ' has-video';
			$slide_media = $embed;
		} else {
			$image = woo_image( 'width=' . $media_width . '&noheight=true&class=slide-image&link=img&return=true' );
			if ( '' != $image ) {
				$css_class .= ' has-image no-video';
				$slide_media = $image;
			} else {
				$css_class .= ' no-image';
			}
		}
		if ( $layout )  {
			$css_class .= ' ' . $layout;
		}
?>
		<li class="slide <?php echo esc_attr( $css_class ); ?>">
			<?php
				if ( '' != $slide_media ) {
					echo '<div class="slide-media">' . $slide_media . '</div><!--/.slide-media-->' . "\n";
				}
			?>
			<?php if ( '' == $embed || ( '' != $embed && 'true' == $settings['featured_videotitle'] ) ) { ?>
			<div class="wrapper">
  			<div class="slide-content">
  				<?php if ( $title) : ?>
  					<header><h1><?php echo $title; ?></h1></header>
  				<?php endif; ?>
  				<?php if ( get_the_content() != '' ) { ?>
  				<div class="entry"><?php the_content(); ?></div><!--/.entry-->
  				<?php } ?>
  			</div><!--/.slide-content-->
			</div>
			<?php if ($urnimage) { ?>
				<div class="slide-featured">
					<img src="<?php echo $urnimage['sizes']['medium']; ?>" alt="<?php echo $urnimage['alt']; ?>" />
				</div>
			<?php } ?>
			
			<?php } ?>
		</li>
<?php } wp_reset_postdata(); ?>
	</ul>
</div><!--/#featured-slider-->
<?php
} else {
	echo do_shortcode( '[box type="info"]' . __( 'Please add some slides in the WordPress admin to show in the Featured Slider.', 'woothemes' ) . '[/box]' );
}
?>