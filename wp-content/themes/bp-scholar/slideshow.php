<?php

// custom slideshow function with a no script friendly option allowing to show just the image if no javaScript

		$slideshowon = get_option('ne_buddyscholar_slideshowon');
		$slideshow_speed = get_option('ne_buddyscholar_slideshow_speed');
		$slideshow_number = get_option('ne_buddyscholar_slideshow_number');
		$photo_category = get_option('ne_buddyscholar_feature_cat');
		if ( $photo_category == 'Select a category:' )
			$photo_category = '';
		$feature_image_display = get_option('ne_buddyscholar_feature_image_size');
?>
<?php if ($slideshowon == "yes"){
	// if no speed specified make default 3000
	if ($slideshow_speed == ""){
		$slideshow_speed = "3000";
	}
?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_inc/js/s3slider.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
	   jQuery.noConflict();
 			jQuery(document).ready(function($){
	       		$('#slider').s3Slider({
	           		timeOut: <?php print($slideshow_speed)?>
	        	});
	    	});
	 		jQuery(document).ready(function($){
	    		$('#scriptHide').addClass('hide');
 				$('#scriptShow').removeClass('hide');
    		});
    });
</script>

<div id="scriptHide">
	<?php query_posts('category_name='. $photo_category . '&showposts=1'); ?>
				  <?php while (have_posts()) : the_post(); ?>
							<div class="content-box-outer">
								<div id="slideshow-wrapper">
		<div class="slideshow-image"><span class="attach-post-image" style="height:250px;display:block;background:url('<?php the_post_image_url($feature_image_display); ?>') center center no-repeat">&nbsp;</span></div>
		<div class="slideshow-text"><h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'bp-scholar' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?>:<?php the_excerpt(); ?></a></h4></div>
			</div>
		</div>
	<?php endwhile; ?>
</div>
<!-- If javascript on show this -->
<div id="scriptShow" class="hide">
				<div class="content-box-outer">
					<div id="slideshow-wrapper">
	   <div id="slider">
	        <ul id="sliderContent">
				<?php if ($slideshow_number == "Unlimited"){
					$slideshow_number == "";
					$slideshow_count = "";
				}
				else {
					$slideshow_count = "&showposts=";
					$slideshow_count .= $slideshow_number;
				}
				?>
				<?php query_posts('category_name='. $photo_category . '' . $slideshow_count .''); ?>
							  <?php while (have_posts()) : the_post(); ?>

											    <li class="sliderImage"><div class="featured-image attach-post-image" style="height:250px;width:730px;display:block;background:url('<?php the_post_image_url($feature_image_display); ?>') center center no-repeat"></div>
													<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'bp-scholar' ) ?> <?php the_title_attribute(); ?>"><span class="bottom"><strong><?php the_title(); ?></strong><?php the_excerpt(); ?></span></a>
												  </li>
			 <?php endwhile; ?>
			        <div class="clear sliderImage"></div>
			</ul>
		</div>
	</div>
</div>
</div>
<?php
}
?>