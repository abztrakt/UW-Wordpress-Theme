<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main" class="container">
			
						
			<div class="row">
				<div class="span8">
					<span id="arrow-mark"></span>

          
        <?php if ( have_posts() ) : the_post();  ?>
						
              <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">

                  <div class="row">
                    <div class="span8">
                        <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 200 ) ); ?> 
                        <h1 class="author-title"><?php the_author(); ?></h1>
                        <p><?php the_author_meta( 'description' ); ?> </p>
                        <p><?php the_author_meta('user_email'); ?></p>
                        <p><?php the_author_meta('user_url'); ?></p>
                    </div>
                  </div>

              </header><!-- .entry-header -->

              <div class="author-posts">
                  <h2>Recent Articles:</h2>
                  <?php rewind_posts(); ?>
                  <?php while ( have_posts() ): the_post(); ?>

                    <div class="row">
                      <div class="span8">
                        <a href="<?php the_permalink()?>" title="<?php the_title_attribute();?>">
                          <h3><?php the_title(); ?> - <small><?php the_date(); ?></small></h3>
                        </a>
                      </div>
                    </div>
                    
                  <?php endwhile; ?>
              </div>

              <?php previous_posts_link(); ?>
              <?php next_posts_link(); ?>

            </article><!-- #post-<?php the_ID(); ?> -->

          <?php endif; ?>
				
				
				</div>

				<div id="secondary" class="span4 right-bar" role="complementary">
					<div class="stripe-top"></div><div class="stripe-bottom"></div>				
          <div id="sidebar">
					  <?php dynamic_sidebar('Sidebar'); ?>
          </div>
        </div><!-- .span4 -->

 			 </div>
			</div><!-- #content -->
		</div><!-- #primary -->


<?php get_footer(); ?>
