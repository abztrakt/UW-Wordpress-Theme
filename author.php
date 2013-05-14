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
                        <?php if ( gravatar_exists( get_the_author_meta('user_email') ) ) :
                        echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'uw_author_bio_avatar_size', 200 ) ); 
                            endif; ?> 
                        <h1 class="author-title"><?php the_author_meta('display_name'); // workaround for the_author() bug ?></h1>
                        <div class="single-author">
                            <div class="affiliation"><small><?php the_author_meta('affiliation') ?></small></div>
                            <div class="email">Email: <a href="mailto:<?php the_author_meta('email') ?>"><?php the_author_meta('email'); ?></a></div>
                            <div class="phone"><?php uw_the_author_meta('phone', 'Phone: '); ?></div>
                            <div class="office"><?php uw_the_author_meta('office', 'Office: '); ?></div>
                        </div>
                        <p class="author-desc"><?php the_author_meta( 'description' ); ?> </p>

                    </div>
                  </div>

              </header><!-- .entry-header -->

              <div class="author-posts">
                  <h2>Recent Articles:</h2>
                  <?php rewind_posts(); ?>
                  <?php while ( have_posts() ): the_post(); ?>

                  <?php if ( uw_check_author() ): ?>

                    <div class="row">
                      <div class="span8">
                        <a href="<?php the_permalink()?>" title="<?php the_title_attribute();?>">
                          <h3><?php the_title(); ?> - <small><?php the_date(); ?></small></h3>
                        </a>
                      </div>
                    </div>

                  <?php endif; ?>
                    
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
