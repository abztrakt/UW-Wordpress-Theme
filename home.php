<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main" class="container">
			
						
			<div class="row show-grid">
				<div class="span8">
				<?php  $featured = wp_get_attachment_image_src( get_post_thumbnail_id(get_option('page_for_posts')), 'Full Width' ) ; ?>
				<span id="arrow-mark" <?php if ( $featured ) { echo 'style="background-image:url(' . $featured[0] . ')"'; }?> ></span>
								
			<?php while ( have_posts() ) : the_post(); ?>

				
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php apply_filters('italics', get_the_title()); ?></a></h2>
					<span class="post-info"><?php the_date('F j, Y', '<p>', '</p>'); ?><p class="author-info">By <?php the_author(); ?></p></span>
				</header><!-- .entry-header -->
			
				<div class="entry-content">
					<?php the_excerpt(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
				</div><!-- .entry-content -->
				<hr>
				<footer class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->
			</article><!-- #post-<?php the_ID(); ?> -->

					<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

				</div>
				<div id="secondary" class="span4 right-bar" role="complementary">
					<div class="stripe-top"></div><div class="stripe-bottom"></div>				
          <div id="sidebar">
          <?php if (is_active_sidebar('homepage-sidebar')) : dynamic_sidebar('homepage-sidebar'); else: dynamic_sidebar('sidebar'); endif; ?>
          </div>
				</div>
 			 </div>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
