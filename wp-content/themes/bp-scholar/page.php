<?php get_header() ?>

	<div id="content">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="content-page" id="blog-page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="content-box-outer">
				<div class="h3-background">
					<h3><?php the_title(); ?></h3>
				</div>
			</div>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="content-box-outer">
					<div class="entry">

						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'bp-scholar' ) ); ?>

						<div class="clear"></div>
						<?php wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', 'bp-scholar' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>
						<?php edit_post_link( __( 'Edit this entry.', 'bp-scholar' ), '<p>', '</p>'); ?>

					</div>

				</div>
			<?php endwhile; endif; ?>

			</div><!-- .page -->

			<?php comments_template('', true); ?>

		</div>

		<?php do_action( 'bp_after_blog_page' ) ?>

	</div><!-- #content -->

	<?php get_sidebar('page'); ?>

<?php get_footer(); ?>