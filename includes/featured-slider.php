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

/* Media settings Large */
$media_width     = apply_filters( 'peddlar_media_width', '1680' );
$media_height    = apply_filters( 'peddlar_media_height', '670' );
$media_settings  = array( 'width' => $media_width, 'height' => $media_height );

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
		
		$url = get_post_meta( get_the_ID(), 'url', true );
		$layout = get_post_meta( get_the_ID(), '_layout', true );
		$title = get_the_title();
		if ( $title != '' && $url != '' ) {
			//$title = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '">' . $title . '</a>';
		}

		$css_class = 'slide-number-' . esc_attr( $count );

		$slide_media = '';
		$embed = woo_embed( 'width=' . intval( $media_settings['width'] ) . '&height=' . intval( $media_settings['height'] ) . '&class=slide-video' );
		if ( '' != $embed ) {
			$css_class .= ' has-video';
			$slide_media = $embed;
		} else {
			$image       = woo_image( 'width=' . $media_width . '&noheight=true&class=slide-image&link=img&return=true' );
      $img_alt     = get_post_meta( get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
			$img_lg      = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider_large', true);
			$img_md      = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider_medium', true);
			$img_sm      = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider_small', true);
      $img_lg_src  = $img_lg[0];
      $img_md_src  = $img_md[0];
			$img_sm_src  = $img_sm[0];
			// Retina Images
  		if (function_exists('wr2x_get_retina_from_url')) {
  			$img_sm_2x_src 	= wr2x_get_retina_from_url($img_sm_src);
  			$img_md_2x_src 	= wr2x_get_retina_from_url($img_md_src);
  			$img_lg_2x_src 	= wr2x_get_retina_from_url($img_lg_src);
  		}
			if ( '' != $image ) {
				$css_class .= ' has-image no-video';
				$slide_media = $image;
				ob_start();
				  if ($url != '') { ?><a href="<?php echo esc_url($url); ?>" title="<?php echo esc_attr($title); ?>"><?php } ?>
          <picture>
        		<!--[if IE 9]><video style="display: none;"><![endif]-->
        		<source srcset="<?php if ($img_lg_2x_src) { echo $img_lg_2x_src . ' 2x, '; } echo $img_lg_src .' 1x'; ?>" media="(min-width:1024px)">
            <source srcset="<?php if ($img_md_2x_src) { echo $img_md_2x_src . ' 2x, '; } echo $img_md_src .' 1x'; ?>" media="(min-width:768px)">
            <source srcset="<?php if ($img_sm_2x_src) { echo $img_sm_2x_src . ' 2x, '; } echo $img_sm_src .' 1x'; ?>">
        		<!--[if IE 9]></video><![endif]-->
        		<img class="<?php echo $css_class; ?>" srcset="<?php if ($img_md_2x_src) { echo $img_md_2x_src . ' 2x, '; } echo $img_md_src .' 1x'; ?>" alt="<?php echo $img_alt; ?>" />
        	</picture>
        	<?php if ($url != '') { ?></a><?php } ?>
        <?php 
        $slide_media = ob_get_clean();
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
					echo '<div class="slide-media">' . $slide_media . '</div>' . "\n";
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