<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Template Name: Sections
 */
 
get_header();
global $woo_options;
?> 
  <div id="content" class="tmpl-sections col-full">
  	<?php woo_main_before(); ?>
  	
    <?php if ( have_posts() ) : ?>
      <section id="main" name="main" class="page-section">
        <?php while ( have_posts() ): the_post(); ?>                                                       
          <article <?php post_class(); ?>>				
  					<header>
  						<h1><?php the_title(); ?></h1>
  					</header>  
            <section class="entry">
              <?php the_content(); ?>
              <?php if( have_rows('payment_methods_logos') ): ?>
                <ul class="payment-methods">
            		  <?php while( have_rows('payment_methods_logos') ): the_row();
                		$payment_title = get_sub_field('payment_method_name');
                		$payment_slug  = sanitize_title($payment_title);
                		$payment_logo  = get_sub_field('payment_method_logo'); ?>
                		<li class="<?php echo $payment_slug; ?>"><img src="<?php echo $payment_logo['url']; ?>" alt="<?php echo $payment_title; ?>" /></li>
                  <?php endwhile; ?>
                </ul>  
              <?php endif; ?>
	          </section>
            <?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>
          </article>                            
        <?php endwhile; ?>
      </section>  
		<?php endif; ?>
		
		<?php if( have_rows('page_sections') ):
		  while( have_rows('page_sections') ): the_row();
    		$section_title    = get_sub_field('page_section_title');
    		$section_slug     = sanitize_title($section_title);
    		$section_content  = get_sub_field('page_section_content'); ?>
    		<section id="<?php echo $section_slug; ?>" name="<?php echo $section_slug; ?>" class="page-section">
          <article <?php post_class($section_slug); ?>>				
  					<header>
  						<h2><?php echo $section_title; ?></h2>
  					</header>  
            <section class="entry">
              <?php echo $section_content; ?>
	          </section>
          </article>
        </section>
      <?php endwhile;
    endif; ?>
    <?php woo_main_after(); ?>
  </div>
<?php get_footer(); ?>