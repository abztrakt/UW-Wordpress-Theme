<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main" class="container image-page">
			
						
			<div class="row show-grid">
				<div class="span12 paginated-center">
					
					
			<?php while ( have_posts() ) : the_post(); ?>

      <span id="arrow-mark" <?php the_blogroll_banner_style(); ?> ></span>
				
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>

			
				<div class="entry-content">
					<?php echo wp_get_attachment_image($post->ID, 'full'); ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div><!-- .entry-content -->
				<footer class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->
			</article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile; // end of the loop. ?>

				</div>
				<!--div id="secondary" class="span4 right-bar" role="complementary">
					<div class="stripe-top"></div><div class="stripe-bottom"></div>				
          <div id="sidebar">
          <?php if (is_active_sidebar('homepage-sidebar') && is_front_page()) : dynamic_sidebar('homepage-sidebar'); else: dynamic_sidebar('sidebar'); endif; ?>
          </div>
				</div-->
 			 </div>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
