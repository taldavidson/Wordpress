<?php
/*
Template Name: 3columns
*/
?>

<?php get_header() ?>
	<?php get_sidebar('3colright'); ?>
	<div id="threecol-maincolumn">

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
				</div>

				<?php comments_template('', true); ?>
			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

	</div><!-- #content -->


	<?php get_sidebar('3colleft'); ?>
	<div class="clear">

<?php get_footer(); ?>