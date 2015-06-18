<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Template Name: Left Photo Column
 */
 
get_header();
global $woo_options;
$images     = get_field('left_photo_gallery');
$main_class = 'no-gallery col-left';
?> 
  <div id="content" class="photo-page col-full">
  	<?php woo_main_before(); ?>
  	<?php if ($images) { 
    	$main_class = 'col-right'; ?>
      <section id="photo-gallery" class="col-left">
        <ul class="images">
          <?php foreach( $images as $image ): ?>
            <li>
              <div class="image">
                <a class="zoom" itemprop="image" title="<?php echo $image['title']; ?>" href="<?php echo $image['sizes']['large']; ?>" data-rel="prettyPhoto[page-gallery]">
                  <img src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
                </a>
              </div>  
              <p><?php echo $image['caption']; ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
  	<?php } ?>
		<section id="main" class="<?php echo $main_class; ?>">
      <?php if ( have_posts() ) { $count = 0;
        while ( have_posts() ) { the_post(); $count++; ?>                                                             
          <article <?php post_class(); ?>>				
  					<header>
  						<h1><?php the_title(); ?></h1>
  					</header>  
            <section class="entry">
              <?php the_content(); ?>
	          </section>
            <?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>
          </article>                                 
			<?php }
		} else { ?>
		  <article <?php post_class(); ?>>
        <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
      </article>
    <?php } ?>  
		</section>
    <?php woo_main_after(); ?>
  </div>
<?php get_footer(); ?>