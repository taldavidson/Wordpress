<?php
define('TEMPLATE_DOMAIN', 'bp-business');
//@TODO Check the customizer code
// WP 3.4 Theme Customizer
global $scholar_use_customizer_type, $scholar_use_customizer_id;
$shortname = 'ne';
$shortprefix = '_buddyscholar_';
$short_prefix = 'buddyscholar_';

$scholar_use_customizer_type = array('colorpicker', 'colourpicker');
$scholar_use_customizer_id = array(
	/*$shortname . $shortprefix  . "slideshowon",
	$shortname . $shortprefix  . "slideshow_speed",
	$shortname . $shortprefix  . "slideshow_number",
	$shortname . $shortprefix  . "feature_cat",
	$shortname . $shortprefix  . "feature_image_size",
	$shortname . $shortprefix  . "news_cat",
	$shortname . $shortprefix  . "news_image_size",
	$shortname . $shortprefix  . "news_number",
	$shortname . $shortprefix  . "spotlight_cat",
	$shortname . $shortprefix  . "spotlight_image_size",
	$shortname . $shortprefix  . "spotlight_number",*/
	$shortname . $shortprefix  . "welcome_title",
	$shortname . $shortprefix  . "welcome_message",
	/*$shortname . $shortprefix  . "followuson",
	$shortname . $shortprefix  . "twitter_url",
	$shortname . $shortprefix  . "facebook_url",
	$shortname . $shortprefix  . "flickr_url",
	$shortname . $shortprefix  . "youtube_url",*/
	//$shortname . $shortprefix  . "header_image",
	//$shortname . $shortprefix  . "header_logo",
	//$shortname . $shortprefix  . "header_image_square",
	//$shortname . $shortprefix  . "header_logo_square",
	$shortname . $shortprefix  . "header_title",
	$shortname . $shortprefix  . "header_description_on",
	$shortname . $shortprefix  . "header_description",
	$shortname . $shortprefix  . "site_message_on",
	$shortname . $shortprefix  . "site_message",
	//$shortname . $shortprefix  . "header_advert",
	//$shortname . $shortprefix  . "header_advert_title",
	$shortname . $shortprefix  . "body_font",
	$shortname . $shortprefix  . "headline_font",
	$shortname . $shortprefix  . "h1_colour",
	$shortname . $shortprefix  . "h1_header_colour",
	$shortname . $shortprefix  . "h2_colour",
	$shortname . $shortprefix  . "h3_colour",
	$shortname . $shortprefix  . "h3_title_colour",
	$shortname . $shortprefix  . "h3_title_background_colour",
	$shortname . $shortprefix  . "h3_title_border_colour",
	$shortname . $shortprefix  . "h4_colour",
	$shortname . $shortprefix  . "h4_title_colour",
	$shortname . $shortprefix  . "h4_title_background_colour",
	$shortname . $shortprefix  . "h4_title_background_image",
	$shortname . $shortprefix  . "h4_title_image_repeat",
	$shortname . $shortprefix  . "text_colour",
	$shortname . $shortprefix  . "blockquote_colour",
	$shortname . $shortprefix  . "blockquote_border_colour",
	$shortname . $shortprefix  . "list_colour",
	$shortname . $shortprefix  . "link_colour",
	$shortname . $shortprefix  . "link_hover_colour",
	$shortname . $shortprefix  . "link_visited_colour",
	$shortname . $shortprefix  . "navigation_link_colour",
	$shortname . $shortprefix  . "navigation_hover_colour",
	$shortname . $shortprefix  . "navigation_background_hover_colour",
	$shortname . $shortprefix  . "navigation_background_drop_colour",
	//$shortname . $shortprefix  . "user_link_colour",
	//$shortname . $shortprefix  . "user_link_hover_colour",
	$shortname . $shortprefix  . "header_background_image",
	$shortname . $shortprefix  . "header_image_repeat",
	$shortname . $shortprefix  . "header_background_colour",
	$shortname . $shortprefix  . "header_border_colour",
	$shortname . $shortprefix  . "navigation_background_image",
	$shortname . $shortprefix  . "navigation_image_repeat",
	$shortname . $shortprefix  . "navigation_background_colour",
	$shortname . $shortprefix  . "navigation_border_colour",
	$shortname . $shortprefix  . "content_colour",
	$shortname . $shortprefix  . "content_border_colour",
	$shortname . $shortprefix  . "footer_background_colour",
	$shortname . $shortprefix  . "footer_border_colour",
	$shortname . $shortprefix  . "footer_links_border_colour",
	$shortname . $shortprefix  . "information_background_colour",
	$shortname . $shortprefix  . "information_border_colour",
	$shortname . $shortprefix  . "box_background_colour",
	$shortname . $shortprefix  . "box_border_colour",
	$shortname . $shortprefix  . "login_background_colour",
	$shortname . $shortprefix  . "login_border_colour",
	$shortname . $shortprefix  . "login_text_colour",
	$shortname . $shortprefix  . "widget_text_colour",
	$shortname . $shortprefix  . "widget_background_colour",
	$shortname . $shortprefix  . "widget_border_colour",
	//$shortname . $shortprefix  . "widget_list_colour",
	$shortname . $shortprefix  . "slideshow_background_colour",
	$shortname . $shortprefix  . "slideshow_text_colour",
	$shortname . $shortprefix  . "slideshow_text_background_colour",
	$shortname . $shortprefix  . "alt_background_colour",
	$shortname . $shortprefix  . "alt_border_colour",
	$shortname . $shortprefix  . "table_colour",
	$shortname . $shortprefix  . "table_border_colour",
	$shortname . $shortprefix  . "table_sticky_colour",
	$shortname . $shortprefix  . "table_sticky_border_colour",
	$shortname . $shortprefix  . "table_unread_colour",
	$shortname . $shortprefix  . "table_unread_border_colour",
	$shortname . $shortprefix  . "unread_colour",
	$shortname . $shortprefix  . "unread_text_colour",
	$shortname . $shortprefix  . "messages_border_colour",
	$shortname . $shortprefix  . "button_text",
	$shortname . $shortprefix  . "button_colour",
	$shortname . $shortprefix  . "button_border",
	$shortname . $shortprefix  . "button_hover_text",
	$shortname . $shortprefix  . "button_hover_background",
	$shortname . $shortprefix  . "submit_background_colour",
	$shortname . $shortprefix  . "submit_border_colour",
	$shortname . $shortprefix  . "submit_text_colour",
	$shortname . $shortprefix  . "form_background_colour",
	$shortname . $shortprefix  . "form_border_colour",
	$shortname . $shortprefix  . "form_text",
	$shortname . $shortprefix  . "label_text_colour",
	$shortname . $shortprefix  . "comment_list_border_colour",
	$shortname . $shortprefix  . "comment_meta_colour",
	//$shortname . $shortprefix  . "comment_meta_colour",
	$shortname . $shortprefix  . "comment_odd_colour",
	$shortname . $shortprefix  . "comment_odd_border_colour",
	$shortname . $shortprefix  . "comment_even_colour",
	$shortname . $shortprefix  . "comment_even_border_colour",
);
$scholar_use_customizer_not_id = array(
	//$shortname . $shortprefix  . "bg_colour",
);
$options1 = array ();

/*
 * Custom control class
 *
 * Add description on control
 * */
if ( class_exists('WP_Customize_Control') ) {
class WPMUDEV_Customize_Control extends WP_Customize_Control {

	public $description = '';

	protected function render_content() {
		switch( $this->type ) {
			default:
				return parent::render_content();
			case 'text':
				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( isset($this->description) && !empty($this->description) ): ?>
					<span class="customize-control-description"><?php echo $this->description ?></span>
					<?php endif ?>
					<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				</label>
				<?php
				break;
			case 'radio':
				if ( empty( $this->choices ) )
					return;

				$name = '_customize-radio-' . $this->id;

				?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if ( isset($this->description) && !empty($this->description) ): ?>
				<span class="customize-control-description"><?php echo $this->description ?></span>
				<?php endif ?>
				<?php
				foreach ( $this->choices as $value => $label ) :
					?>
					<label>
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
						<?php echo esc_html( $label ); ?><br/>
					</label>
					<?php
				endforeach;
				break;
			case 'select':
				if ( empty( $this->choices ) )
					return;

				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( isset($this->description) && !empty($this->description) ): ?>
					<span class="customize-control-description"><?php echo $this->description ?></span>
					<?php endif ?>
					<select <?php $this->link(); ?>>
						<?php
						foreach ( $this->choices as $value => $label )
							echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
						?>
					</select>
				</label>
				<?php
				break;
			// Handle textarea
			case 'textarea':
				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( isset($this->description) && !empty($this->description) ): ?>
					<span class="customize-control-description"><?php echo $this->description ?></span>
					<?php endif ?>
					<textarea rows="10" cols="40" <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
				</label>
				<?php
				break;
		}
	}

}
}

if ( class_exists('WP_Customize_Color_Control') ) {
class WPMUDEV_Customize_Color_Control extends WP_Customize_Color_Control {

	public $description = '';

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( isset($this->description) && !empty($this->description) ): ?>
			<span class="customize-control-description"><?php echo $this->description ?></span>
			<?php endif ?>
			<div class="customize-control-content">
				<div class="dropdown">
					<div class="dropdown-content">
						<div class="dropdown-status"></div>
					</div>
					<div class="dropdown-arrow"></div>
				</div>
				<input class="color-picker-hex" type="text" maxlength="7" placeholder="<?php esc_attr_e('Hex Value'); ?>" />
			</div>
			<div class="farbtastic-placeholder"></div>
		</label>
		<?php
	}
}
}
function scholar_customize_register( $wp_customize ) {
	global $options, $options3, $shortname, $shortprefix, $bp_existed, $scholar_use_customizer_type, $scholar_use_customizer_id;
	$options_list = array_merge($options, $options3);
	$sections = array(
		array(
			'section' => 'homepage',
			'title' => __("Front page settings", TEMPLATE_DOMAIN),
			'priority' => 30
		),array(
			'section' => 'header',
			'title' => __("Header settings", TEMPLATE_DOMAIN),
			'priority' => 31
		),array(
			'section' => 'headers',
			'title' => __("Text styling", TEMPLATE_DOMAIN),
			'priority' => 32
		),array(
			'section' => 'links',
			'title' => __("Link styling", TEMPLATE_DOMAIN),
			'priority' => 33
		),array(
			'section' => 'navlinks',
			'title' => __("Navigation styling", TEMPLATE_DOMAIN),
			'priority' => 34
		),array(
			'section' => 'layout',
			'title' => __("Layout styling", TEMPLATE_DOMAIN),
			'priority' => 35
		),array(
			'section' => 'form',
			'title' => __("Form styling", TEMPLATE_DOMAIN),
			'priority' => 36
		)
	);
	// Add sections
	foreach ( $sections as $section ) {
		$wp_customize->add_section( $shortname . $shortprefix . $section['section'], array(
			'title' => $section['title'],
			'priority' => $section['priority']
		) );
	}
	// Add settings and controls
	foreach ( $options_list as $o => $option ) {
		if ( ! scholar_option_in_customize($option) )
			continue;
		if ( $option['inblock'] == 'content-layout' )
			$option['inblock'] = 'layout';
		$wp_customize->add_setting( $option['id'], array(
			'default' => $option['std'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage'
		) );
		$control_param = array(
			'label' => strip_tags($option['name']),
			'description' => ( isset($option['description']) && !empty($option['description']) ? $option['description'] : '' ),
			'section' => $shortname . $shortprefix . $option['inblock'],
			'settings' => $option['id'],
			'priority' => $o // make sure we have the same order as theme options :)
		);
		if ( $option['type'] == 'colorpicker' || $option['type'] == 'colourpicker' || ( isset($option['custom']) && ( $option['custom'] == 'colorpicker' || $option['custom'] == 'colourpicker' ) ) ) {
			$wp_customize->add_control( new WPMUDEV_Customize_Color_Control( $wp_customize, $option['id'].'_control', $control_param ) );
		}
		else if ( $option['type'] == 'text' || $option['type'] == 'textarea' ) {
			$control_param['type'] = $option['type'];
			$wp_customize->add_control( new WPMUDEV_Customize_Control( $wp_customize, $option['id'].'_control', $control_param) );
		}
		else if ( $option['type'] == 'select' || $option['type'] == 'select-preview' ) {
			$control_param['type'] = 'select';
			// @TODO choices might get removed in future
			$choices = array();
			foreach ( $option['options'] as $choice )
				$choices[$choice] = $choice;
			$control_param['choices'] = $choices;
			$wp_customize->add_control( new WPMUDEV_Customize_Control( $wp_customize, $option['id'].'_control', $control_param) );
		}
	}

	// Support Wordpress custom background
	$wp_customize->get_setting('background_color')->transport = 'postMessage';
	$wp_customize->get_setting('background_image')->transport = 'postMessage';
	$wp_customize->get_setting('background_repeat')->transport = 'postMessage';
	$wp_customize->get_setting('background_position_x')->transport = 'postMessage';
	$wp_customize->get_setting('background_attachment')->transport = 'postMessage';
	$wp_customize->get_setting('header_image')->transport = 'postMessage';
	$wp_customize->get_setting('blogname')->transport = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport = 'postMessage';

	// Add transport script
	if ( $wp_customize->is_preview() && ! is_admin() )
		add_action('wp_footer', 'scholar_customize_preview', 21);
}
add_action('customize_register', 'scholar_customize_register');

function scholar_customize_preview() {
	global $options, $options1, $shortname, $shortprefix;
	$options_list = array_merge($options, $options1);
	?>
	<div id="theme-customizer-css"></div>

	<script type="text/javascript">
		var theme_customizer_css = [];
		function theme_update_css(){
			var css = '';
			for ( s in theme_customizer_css ){
				css += theme_customizer_css[s].selector + '{';
				for ( p in theme_customizer_css[s].properties ){
					var property = theme_customizer_css[s].properties[p];
					for ( v in property ){
						if ( v == 0 || v == 1 ) continue;
						css += property[0] + ':' + property[v] + property[1] + ';';
					}
				}
				css += '}';
			}
			jQuery('#theme-customizer-css').html('<style type="text/css">'+css+'</style>');
		}
		function theme_change_style( selector_list, property, values, priority ){
			if ( !priority ) priority = '';
			var prop = [property, priority];
			if ( typeof values == 'string' ) prop.push(values);
			else {
				for ( v in values ) prop.push(values[v]);
			}
			var add_selector = true, add_property = true;
			for ( s in theme_customizer_css ){
				if ( theme_customizer_css[s].selector == selector_list ){
					add_selector = false;
					for ( p in theme_customizer_css[s].properties ){
						if ( theme_customizer_css[s].properties[p][0] == property ){
							theme_customizer_css[s].properties[p] = prop;
							add_property = false;
							break;
						}
					}
					if ( add_property ) theme_customizer_css[s].properties.push(prop)
				}
			}
			if ( add_selector ){
				theme_customizer_css.push({
					selector: selector_list,
					properties: [prop]
				});
			}
			theme_update_css();
		}
		function theme_change_font_family( selector, value, priority ){
			// load font from Google Fonts API
			var fonts = value.split(',');
			var font = fonts[0];
			var supported_fonts = ["Cantarell", "Cardo", "Crimson Text", "Droid Sans", "Droid Serif", "IM Fell DW Pica",
				"Josefin Sans Std Light", "Lobster", "Molengo", "Neuton", "Nobile", "OFL Sorts Mill Goudy TT",
				"Reenie Beanie", "Tangerine", "Old Standard TT", "Volkorn", "Yanone Kaffessatz", "Just Another Hand",
				"Terminal Dosis Light", "Ubuntu"];
			var load_external = false;
			for ( i in supported_fonts ){
				if ( font == supported_fonts[i] ){
					load_external = true;
					break;
				}
			}
			if ( load_external ){
				if ( font == 'Ubuntu' ) font += ":light,regular,bold";
				font = font.replace(' ', '+');
				jQuery('body').append("<link href='http://fonts.googleapis.com/css?family="+font+"' rel='stylesheet' type='text/css'/>");
			}
			theme_change_style(selector, 'font-family', value, priority);
		}
		function theme_color_creator(color, per){
			color = color.toString().substring(1);
			rgb = '';
			per = per/100*255;
			if  (per < 0 ){
		        per =  Math.abs(per);
		        for (x=0;x<=4;x+=2)
		        {
		        	c = parseInt(color.substring(x, x+2), 16) - per;
		        	c = Math.floor(c);
		            c = (c < 0) ? "0" : c.toString(16);
		            rgb += (c.length < 2) ? '0'+c : c;
		        }
		    }
		    else{
		        for (x=0;x<=4;x+=2)
		        {
		        	c = parseInt(color.substring(x, x+2), 16) + per;
		        	c = Math.floor(c);
		            c = (c > 255) ? 'ff' : c.toString(16);
		            rgb += (c.length < 2) ? '0'+c : c;
		        }
		    }
		    return '#'+rgb;
		}

		window.onload = function(){
			wp.customize( 'blogname', function(value) {
				value.bind(function(to){
					if ( wp.customize('ne_buddyscholar_header_title').get() == '' )
						jQuery('#logo h1 a').text(to);
				})
			});
			wp.customize( 'blogdescription', function(value) {
				value.bind(function(to){
					if ( wp.customize('ne_buddyscholar_header_description').get() == '' )
						jQuery('#logo div.description').text(to);
				})
			});
			wp.customize( 'ne_buddyscholar_welcome_title', function(value) {
				value.bind(function(to){
					if ( jQuery('#welcome_box').size() == 0 && jQuery('#front-maincolumn').size() > 0 )
						jQuery('#front-maincolumn').prepend('<div class="content-box-outer" id="welcome_box"><div class="h3-background"><h3></h3></div><div class="content-box-inner"><div class="entry"><p></p></div></div></div>');
					jQuery('#welcome_box h3').text(to);
				})
			});
			wp.customize( 'ne_buddyscholar_welcome_message', function(value) {
				value.bind(function(to){
					if ( jQuery('#welcome_box').size() == 0 && jQuery('#front-maincolumn').size() > 0 )
						jQuery('#front-maincolumn').prepend('<div class="content-box-outer" id="welcome_box"><div class="h3-background"><h3></h3></div><div class="content-box-inner"><div class="entry"><p></p></div></div></div>');
					jQuery('#welcome_box div.entry p').text(to);
				})
			});
			wp.customize( 'ne_buddyscholar_header_title', function(value) {
				value.bind(function(to){
					jQuery('#logo h1 a').text(to);
				})
			});
			wp.customize( 'ne_buddyscholar_header_description_on', function(value) {
				value.bind(function(to){
					if ( to == 'yes' && jQuery('#logo div.description').size() == 0 )
						jQuery('#logo').append('<div class="description">' + ( wp.customize('ne_buddyscholar_header_description').get() ? wp.customize('ne_buddyscholar_header_description').get() : wp.customize('blogdescription').get() ) + '</div>');
					else
						jQuery('#logo div.description').remove();
				})
			});
			wp.customize( 'ne_buddyscholar_header_description', function(value) {
				value.bind(function(to){
					jQuery('#logo div.description').text(to);
				})
			});
			wp.customize( 'ne_buddyscholar_site_message_on', function(value) {
				value.bind(function(to){
					if ( to == 'yes' && jQuery('#info-bar h2').size() == 0 )
						jQuery('#info-bar').html('<h2>' + wp.customize('ne_buddyscholar_site_message').get() + '</h2>');
					else
						jQuery('#info-bar h2').remove();
				})
			});
			wp.customize( 'ne_buddyscholar_site_message', function(value) {
				value.bind(function(to){
					jQuery('#info-bar h2').text(to);
				})
			});
			wp.customize( 'ne_buddyscholar_body_font', function(value) {
				value.bind(function(to){
					theme_change_font_family('body', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_headline_font', function(value) {
				value.bind(function(to){
					theme_change_font_family('.entry h1, .entry h2, .entry h3, .entry h4, .entry h5, .entry h6, h1, h1 a, h1 a:link, h1 a:visited, h1:hover, #header h1, #header h1 a, #header h1 a:link, #header h1 a:visited, #header h1:hover, h2, .h3-background h3, h3, h4, .h4-background h4', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h1_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry h1, h1, h1 a, h1 a:link, h1 a:visited, h1:hover', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h1_header_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#header h1, #header h1 a, #header h1 a:link, #header h1 a:visited, #header h1:hover', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h2_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry h2, h2', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h3_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry h3, h3', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h3_title_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.h3-background h3', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h3_title_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.h3-background', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h3_title_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.h3-background', 'border-bottom', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h4_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry h4, .entry h5, .entry h6, h4', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h4_title_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.h4-background h4', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h4_title_background_colour', function(value) {
				value.bind(function(to){
					if ( wp.customize('ne_buddyscholar_h4_title_background_image').get() )
						theme_change_style('.h4-background', 'background-color', to, '');
					else
						theme_change_style('.h4-background', 'background', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_h4_title_background_image', function(value) {
				value.bind(function(to){
					theme_change_style('.h4-background', 'background-image', 'url(' + to + ')', '');
					theme_change_style('.h4-background', 'background-repeat', wp.customize('ne_buddyscholar_h4_title_image_repeat').get(), '');
				})
			});
			wp.customize( 'ne_buddyscholar_h4_title_image_repeat', function(value) {
				value.bind(function(to){
					theme_change_style('.h4-background', 'background-repeat', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('body, p.description', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_blockquote_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry blockquote', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_blockquote_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry blockquote', 'border-left', '2px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_list_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.entry ul, .entry ol, .widget-wrapper li, .footer-block li, .footer-block-end li', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_link_colour', function(value) {
				value.bind(function(to){
					theme_change_style('a, a:link', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_link_hover_colour', function(value) {
				value.bind(function(to){
					theme_change_style('a:hover', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_link_visited_colour', function(value) {
				value.bind(function(to){
					theme_change_style('a:visited', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_link_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.sf-menu a, .sf-menu a:visited', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_hover_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.sf-menu li:hover, .sf-menu li.current, .sf-menu li.current a:visited, .sf-menu li.current_page_item, .sf-menu li.current_page_item a:visited, .sf-menu li.sfHover, .sf-menu a:focus, .sf-menu a:hover, .sf-menu a:active, .sf-menu .selected a', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_background_hover_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.sf-menu li:hover, .sf-menu li.current, .sf-menu li.current a:visited, .sf-menu li.current_page_item, .sf-menu li.current_page_item a:visited, .sf-menu li.sfHover, .sf-menu a:focus, .sf-menu a:hover, .sf-menu a:active, .sf-menu .selected a', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_background_drop_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.sf-menu li li, .sf-menu li li li', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_header_background_image', function(value) {
				value.bind(function(to){
					theme_change_style('#header-wrapper', 'background-image', 'url(' + to + ')', '');
					theme_change_style('#header-wrapper', 'background-repeat', wp.customize('ne_buddyscholar_header_image_repeat').get(), '');
				})
			});
			wp.customize( 'ne_buddyscholar_header_image_repeat', function(value) {
				value.bind(function(to){
					theme_change_style('#header-wrapper', 'background-repeat', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_header_background_colour', function(value) {
				value.bind(function(to){
					if ( wp.customize('ne_buddyscholar_header_background_image').get() )
						theme_change_style('#header-wrapper', 'background-color', to, '');
					else
						theme_change_style('#header-wrapper', 'background', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_header_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#header-wrapper', 'border-top', '2px solid ' + to, '');
					theme_change_style('#header-wrapper', 'border-bottom', '2px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_background_image', function(value) {
				value.bind(function(to){
					theme_change_style('#navigation-wrapper', 'background-image', 'url(' + to + ')', '');
					theme_change_style('#navigation-wrapper', 'background-repeat', wp.customize('ne_buddyscholar_navigation_image_repeat').get(), '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_image_repeat', function(value) {
				value.bind(function(to){
					theme_change_style('#navigation-wrapper', 'background-repeat', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_background_colour', function(value) {
				value.bind(function(to){
					if ( wp.customize('ne_buddyscholar_navigation_background_image').get() )
						theme_change_style('#navigation-wrapper', 'background-color', to, '');
					else
						theme_change_style('#navigation-wrapper', 'background', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_navigation_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#navigation-wrapper', 'border-top', '1px solid ' + to, '');
					theme_change_style('#navigation-wrapper', 'border-bottom', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_content_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#container-wrapper', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_content_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#container-wrapper', 'border-top', '2px solid ' + to, '');
					theme_change_style('#container-wrapper', 'border-bottom', '2px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_footer_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#footer-wrapper, body', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_footer_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#footer-wrapper', 'border-top', '2px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_footer_links_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#footer-links', 'border-top', '2px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_information_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.info, .error, #whats-new-form, .ac-form', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_information_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.info, .error, #whats-new-form, .ac-form', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_box_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.content-box-inner, .content-box-outer, #item-header, .content-box-outer-activity, #front-sidebar, div.item-list-tabs, #sidebar, #sidebar-right, .sub-navigation-box', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_box_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.activity-comments ul', 'border-top', '1px solid ' + to, '');
					theme_change_style('.activity-comments li, .content-box-inner, .entry-image, .forum, img, .sub-navigation-box', 'border', '1px solid ' + to, '');
					theme_change_style('hr', 'background-color', to, '');
					theme_change_style('ul.item-list li, .post-meta-data', 'border-bottom', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_login_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#info-wrapper, #login-wrapper', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_login_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#info-wrapper, #login-wrapper', 'border-bottom', '2px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_login_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.description, #info-wrapper, #login-wrapper, #login-wrapper label, #login-wrapper a, #login-wrapper a:link, #login-wrapper a:hover, #login-wrapper a:visited', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_widget_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.widget-error, .widget-wrapper', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_widget_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.widget-error, .widget-wrapper', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_widget_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.widget-error, .widget-wrapper', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_slideshow_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#slideshow-image', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_slideshow_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#sliderImage', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_slideshow_text_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#sliderImage span.bottom', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_alt_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.alt', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_alt_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('.alt', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_table_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#th-title, #th-poster, #th-group, #th-postcount, #th-freshness, table.forum tr:first-child', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_table_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#th-title, #th-poster, #th-group, #th-postcount', 'border-bottom', '1px solid ' + to, '');
					theme_change_style('#th-title, #th-poster, #th-group, #th-postcount', 'border-top', '1px solid ' + to, '');
					theme_change_style('#th-freshness', 'border-bottom', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_table_sticky_colour', function(value) {
				value.bind(function(to){
					theme_change_style('table.forum tr.sticky td', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_table_sticky_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('table.forum tr.sticky td', 'border-bottom', '1px solid ' + to, '');
					theme_change_style('table.forum tr.sticky td', 'border-top', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_table_unread_colour', function(value) {
				value.bind(function(to){
					theme_change_style('table#message-threads tr.unread td', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_table_unread_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('table#message-threads tr.unread td', 'border-bottom', '1px solid ' + to, '');
					theme_change_style('table#message-threads tr.unread td', 'border-top', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_unread_colour', function(value) {
				value.bind(function(to){
					theme_change_style('li span.unread-count, tr.unread span.unread-count', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_unread_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('li span.unread-count, tr.unread span.unread-count', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_messages_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('#message-threads tr', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_button_text', function(value) {
				value.bind(function(to){
					theme_change_style('div#item-header h3 span.highlight span, .activity-list div.activity-meta a, div.generic-button a, div.comment-options a, .activity-list div.activity-meta a.acomment-reply, a.comment-reply-link, input[type="button"], .button, .item-list-tabs a, .item-list-tabs a:link, .item-list-tabs a:visited', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_button_colour', function(value) {
				value.bind(function(to){
					theme_change_style('div#item-header h3 span.highlight span, .activity-list div.activity-meta a, div.generic-button a, div.comment-options a, .activity-list div.activity-meta a.acomment-reply, a.comment-reply-link, input[type="button"], .button, .item-list-tabs a, .item-list-tabs a:link, .item-list-tabs a:visited', 'background-color', to, '');
					theme_change_style('.button', 'background', '-moz-linear-gradient(top, #ffffff 0%, ' + to + ' 99%);', '');
					theme_change_style('.button', 'background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(99%,' + to + '));', '');
					theme_change_style('.button', 'filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr="#ffffff", endColorstr="' + to + '",GradientType=0);', '');
				})
			});
			wp.customize( 'ne_buddyscholar_button_border', function(value) {
				value.bind(function(to){
					theme_change_style('div#item-header h3 span.highlight span, .activity-list div.activity-meta a, div.generic-button a, div.comment-options a, .activity-list div.activity-meta a.acomment-reply, a.comment-reply-link, input[type="button"], .button, .item-list-tabs a, .item-list-tabs a:link, .item-list-tabs a:visited', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_button_hover_text', function(value) {
				value.bind(function(to){
					theme_change_style('.item-list-tabs a:hover, .item-list-tabs li.selected a, div.activity-meta a:hover,  div.comment-options a:hover, div.generic-button a:hover, div.activity-meta a.acomment-reply:hover', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_button_hover_background', function(value) {
				value.bind(function(to){
					theme_change_style('.item-list-tabs a:hover, .item-list-tabs li.selected a, div.activity-meta a:hover,  div.comment-options a:hover, div.generic-button a:hover, div.activity-meta a.acomment-reply:hover', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_submit_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('input[type="submit"], #login-wrapper .button', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_submit_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('input[type="submit"], #login-wrapper .button', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_submit_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('input[type="submit"], #login-wrapper .button', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_form_background_colour', function(value) {
				value.bind(function(to){
					theme_change_style('input[type="text"], input[type="search"], input[type="password"], select, textarea', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_form_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('input[type="text"], input[type="search"], input[type="password"], select, textarea', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_form_text', function(value) {
				value.bind(function(to){
					theme_change_style('input[type="text"], input[type="search"], input[type="password"], select, textarea', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_label_text_colour', function(value) {
				value.bind(function(to){
					theme_change_style('label', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_comment_list_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('ol.commentlist li.comment img.avatar', 'border', '1px solid ' + to, '');
					theme_change_style('ol.commentlist ul.children li.depth-2, ol.commentlist ul.children li.depth-3, ol.commentlist ul.children li.depth-4, ol.commentlist li.parent', 'border-left', '5px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_comment_meta_colour', function(value) {
				value.bind(function(to){
					theme_change_style('ol.commentlist li.odd, ol.commentlist li.even', 'color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_comment_odd_colour', function(value) {
				value.bind(function(to){
					theme_change_style('ol.commentlist li.odd', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_comment_odd_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('ol.commentlist li.odd', 'border', '1px solid ' + to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_comment_even_colour', function(value) {
				value.bind(function(to){
					theme_change_style('ol.commentlist li.even', 'background-color', to, '');
				})
			});
			wp.customize( 'ne_buddyscholar_comment_even_border_colour', function(value) {
				value.bind(function(to){
					theme_change_style('ol.commentlist li.even', 'border', '1px solid ' + to, '');
				})
			});

			wp.customize( 'background_color', function(value) {
				value.bind(function(to){
					if ( to != '' )
						theme_change_style('#container-wrapper', 'background', 'transparent', '');
					theme_change_style('body', 'background-color', to, '');
				})
			});
			wp.customize( 'background_image', function(value) {
				value.bind(function(to){
					if ( to != '' )
						theme_change_style('#container-wrapper', 'background', 'transparent', '');
					theme_change_style('body', 'background-image', 'url('+to+')', '');
					theme_change_style('body', 'background-repeat', wp.customize('background_repeat').get(), '');
					theme_change_style('body', 'background-position', 'top '+wp.customize('background_position_x').get(), '');
					theme_change_style('body', 'background-attachment', wp.customize('background_attachment').get(), '');
				})
			});
			wp.customize( 'background_repeat', function(value) {
				value.bind(function(to){
					theme_change_style('body', 'background-repeat', to, '');
				})
			});
			wp.customize( 'background_position_x', function(value) {
				value.bind(function(to){
					theme_change_style('body', 'background-position', 'top '+to, '');
				})
			});
			wp.customize( 'background_attachment', function(value) {
				value.bind(function(to){
					theme_change_style('body', 'background-attachment', to, '');
				})
			});
			wp.customize( 'header_image', function(value) {
				value.bind(function(to){
					jQuery('#custom-img-header img').attr('src', to);
				})
			});

		};
	</script>
	<?php
}

// Add additional styling to better fit on Customizer options
function scholar_customize_controls_footer() {
	?>
	<style type="text/css">
		.customize-control-title { line-height: 18px; padding: 2px 0; }
		.customize-control-description { font-size: 11px; color: #666; margin: 0 0 3px; display: block; }
		.customize-control input[type="text"], .customize-control textarea { width: 98%; line-height: 18px; margin: 0; }
	</style>
	<?php
}
add_action('customize_controls_print_footer_scripts', 'scholar_customize_controls_footer');

function scholar_option_in_customize( $option ) {
	global $scholar_use_customizer_type, $scholar_use_customizer_id, $scholar_use_customizer_not_id;
	if ( in_array($option['id'], $scholar_use_customizer_not_id) )
		return false;
	if ( in_array($option['type'], $scholar_use_customizer_type) || in_array($option['id'], $scholar_use_customizer_id) || ( isset($option['custom']) && in_array($option['custom'], $scholar_use_customizer_type) ) )
		return true;
	return false;
}

?>