<div id="navigation-wrapper">
	<div id="navigation-bar">
		<div id="navcontainer">
		<ul class="sf-menu">
				<li<?php if ( is_front_page() ) : ?> class="selected"<?php endif; ?>>
					<a href="<?php echo site_url() ?>" title="<?php _e( 'Home', 'bp-scholar' ) ?>"><?php _e( 'Home', 'bp-scholar' ) ?></a>
				</li>

				<?php
					if ( function_exists( 'wp_nav_menu' ) )
						wp_nav_menu( array('theme_location' => "primary", 'menu_id' => '', 'container' => '', 'container_id' => '', 'items_wrap' => '%3$s', 'fallback_cb' => create_function('', 'return wp_list_pages("title_li=");')) );
					else
						wp_list_pages('title_li=');
				?>
<li><a href="#"><?php _e( 'Categories', 'bp-scholar' ) ?></a>
				<ul>
				<?php wp_list_categories('orderby=name&title_li=');
				$this_category = get_category($cat);
				if (isset($this_category->cat_ID) && get_categories($this_category->cat_ID) != "") {

				wp_list_categories('orderby=id&show_count=0&title_li=
				&use_desc_for_title=1&child_of='.$this_category->cat_ID);

				}
				?>
				</ul>
</li>
<li><a href="#"><?php _e( 'Community', 'bp-scholar' ) ?></a>
	<ul>
				<?php if ( 'activity' != bp_dtheme_page_on_front() && bp_is_active( 'activity' ) ) : ?>
			<li<?php if ( !bp_is_user() && bp_is_current_component( BP_ACTIVITY_SLUG ) ) : ?> class="selected"<?php endif; ?>>
				<a href="<?php echo site_url() ?>/<?php echo BP_ACTIVITY_SLUG ?>/" title="<?php _e( 'Activity', 'bp-scholar' ) ?>"><?php _e( 'Activity', 'bp-scholar' ) ?></a>
			</li>
		<?php endif; ?>

			<li<?php if (  (!bp_is_user() && bp_is_current_component( BP_MEMBERS_SLUG )) || bp_is_user() ) : ?> class="selected"<?php endif; ?>>
				<a href="<?php echo site_url() ?>/<?php echo BP_MEMBERS_SLUG ?>/" title="<?php _e( 'Members', 'bp-scholar' ) ?>"><?php _e( 'Members', 'bp-scholar' ) ?></a>
			</li>

			<?php if ( bp_is_active( 'groups' ) ) : ?>
				<li<?php if ( (!bp_is_user() && bp_is_current_component( BP_GROUPS_SLUG )) || bp_is_group() ) : ?> class="selected"<?php endif; ?>>
					<a href="<?php echo site_url() ?>/<?php echo BP_GROUPS_SLUG ?>/" title="<?php _e( 'Groups', 'bp-scholar' ) ?>"><?php _e( 'Groups', 'bp-scholar' ) ?></a>
				</li>
			<?php endif; ?>

			<?php if ( bp_is_active( 'forums' ) && bp_is_active( 'groups' ) && ( function_exists( 'bp_forums_is_installed_correctly' ) && !(int) bp_get_option( 'bp-disable-forum-directory' ) ) && bp_forums_is_installed_correctly() ) : ?>
				<li<?php if ( !bp_is_user() && bp_is_current_component( BP_FORUMS_SLUG ) ) : ?> class="selected"<?php endif; ?>>
					<a href="<?php echo site_url() ?>/<?php echo BP_FORUMS_SLUG ?>/" title="<?php _e( 'Forums', 'bp-scholar' ) ?>"><?php _e( 'Forums', 'bp-scholar' ) ?></a>
				</li>
			<?php endif; ?>

			<?php if ( bp_is_active( 'blogs' ) && is_multisite() ) : ?>
				<li<?php if ( !bp_is_user() && bp_is_current_component( BP_BLOGS_SLUG ) ) : ?> class="selected"<?php endif; ?>>
					<a href="<?php echo site_url() ?>/<?php echo BP_BLOGS_SLUG ?>/" title="<?php _e( 'Blogs', 'bp-scholar' ) ?>"><?php _e( 'Blogs', 'bp-scholar' ) ?></a>
				</li>
			<?php endif; ?>
	</ul>
</li>
		<?php do_action( 'bp_nav_items' ); ?>

		</ul>
		</div>		<div class="clear"></div>
	</div>
</div>