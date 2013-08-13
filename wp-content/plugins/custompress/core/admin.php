<?php

/**
* CustomPress_Core_Admin
*
* @uses CustomPress_Core
* @copyright Incsub 2007-2011 {@link http://incsub.com}
* @author Ivan Shaovchev (Incsub), Arnold Bailey (Incsub)
* @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
*/

if (!class_exists('CustomPress_Core_Admin')):

class CustomPress_Core_Admin extends CustomPress_Content_Types {

	/** @public array Available Post Types */
	public $post_types;
	/** @public array Available Taxonomies */
	public $taxonomies;
	/** @public array Available Custom Fields */
	public $custom_fields;
	/** @public boolean Flag whether the users have the ability to declair post type for their own blogs */
	public $enable_subsite_content_types = true;

	function CustomPress_Core_Admin() { __construct(); }

	function __construct(){

		parent::__construct();

		add_action( 'admin_menu', array( &$this, 'on_admin_menu' ) );
		add_action( 'network_admin_menu', array( &$this, 'network_admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );

		add_action( 'admin_print_styles-post.php', array( &$this, 'enqueue_custom_field_scripts') );
		add_action( 'admin_print_styles-post-new.php', array( &$this, 'enqueue_custom_field_scripts') );

		add_action( 'wp_ajax_ct_get_caps', array( &$this, 'ajax_get_caps' ) );
		add_action( 'wp_ajax_ct_save', array( &$this, 'ajax_save' ) );

	}

	/**
	* Admin init
	*
	* @return void
	*/
	function admin_init() {

		//Add custom fields as Columns on edit Post Type page
		if ( isset( $_GET['post_type'] )){

			if (is_network_admin()){
				if( isset($this->network_post_types[$_GET['post_type']]) && is_array( $this->network_post_types[$_GET['post_type'] ] ) ) {
					add_filter( 'manage_edit-' . $_GET['post_type'] . '_columns', array( &$this, 'add_new_cf_columns' ) );
					add_action( 'manage_' . $_GET['post_type'] . '_posts_custom_column', array( &$this, 'manage_cf_columns' ), 10, 2 );
				}
			} else {
				if( isset($this->post_types[$_GET['post_type']]) && is_array( $this->post_types[$_GET['post_type'] ] ) ) {
					add_filter( 'manage_edit-' . $_GET['post_type'] . '_columns', array( &$this, 'add_new_cf_columns' ) );
					add_action( 'manage_' . $_GET['post_type'] . '_posts_custom_column', array( &$this, 'manage_cf_columns' ), 10, 2 );
				}
			}
		}
	}

	/**
	* add new cf columns to columns list
	*
	* @return array columns
	*/
	function add_new_cf_columns( $columns ) {

		if( is_network_admin() ) {

			if ( isset( $this->network_post_types[$_GET['post_type']]['cf_columns'] ) && is_array( $this->network_post_types[$_GET['post_type']]['cf_columns'] ) )
			foreach ( $this->network_post_types[$_GET['post_type']]['cf_columns'] as $key => $value ) {
				if ( 1 == $value )
				$columns[$key] = $this->network_custom_fields[$key]['field_title'];
			}
		} else {
			if ( isset( $this->post_types[$_GET['post_type']]['cf_columns'] ) && is_array( $this->post_types[$_GET['post_type']]['cf_columns'] ) )
			foreach ( $this->post_types[$_GET['post_type']]['cf_columns'] as $key => $value ) {
				if ( 1 == $value )
				$columns[$key] = $this->custom_fields[$key]['field_title'];
			}

		}

		return $columns;
	}

	/**
	* Get values for added cf columns
	*
	* @return void
	*/
	function manage_cf_columns( $column_name, $post_it ) {

		if (is_network_admin() ){
			if ( $column_name == $this->network_custom_fields[$column_name]['field_id']) {
				$custom_field = $this->network_custom_fields[$column_name];
			}
		} else {
			if ( $column_name == $this->custom_fields[$column_name]['field_id']) {
				$custom_field = $this->custom_fields[$column_name];
			}

			$prefix = ( empty($custom_field['field_wp_allow'] ) ) ? $prefix = '_ct_' : 'ct_';
			echo get_post_meta( $post_it, $prefix . $column_name, true );
		}
	}

	/**
	* Register site admin menues.
	*
	* @access public
	* @return void
	*/
	function on_admin_menu() {

		if ( is_multisite() ) {
			$menu_slug = $this->enable_subsite_content_types ? 'ct_content_types' : 'cp_main';
			$menu_callback = $this->enable_subsite_content_types ? 'handle_content_types_page_requests' : 'handle_settings_page_requests';
		} else {
			$menu_slug = 'ct_content_types';
			$menu_callback = 'handle_content_types_page_requests';
		}

		add_menu_page( __('CustomPress', $this->text_domain), __('CustomPress', $this->text_domain), 'activate_plugins', $menu_slug, array( &$this, $menu_callback ) );

		if ( $this->enable_subsite_content_types || !is_multisite() ) {
			$page_content_types = add_submenu_page( 'ct_content_types' , __( 'Content Types', $this->text_domain ), __( 'Content Types', $this->text_domain ), 'activate_plugins', 'ct_content_types', array( &$this, 'handle_content_types_page_requests' ) );

			add_action( 'admin_print_scripts-' . $page_content_types, array( &$this, 'enqueue_scripts' ) );
		}

		$page_settings = add_submenu_page( $menu_slug, __('Settings', $this->text_domain), __('Settings', $this->text_domain), 'activate_plugins', 'cp_main', array( &$this, 'handle_settings_page_requests' ) );
		$export_settings = add_submenu_page( $menu_slug, __('Export / Import', $this->text_domain), __('Export / Import', $this->text_domain), 'manage_options', 'ct_export', array( &$this, 'handle_export' ) );

		add_action( 'admin_print_scripts-' . $page_settings, array( &$this, 'enqueue_settings_scripts' ) );
		add_action( 'admin_print_scripts-' . $export_settings, array( &$this, 'enqueue_scripts' ) );
		add_action( 'admin_head-' . $page_settings, array( &$this, 'ajax_actions' ) );
	}

	/**
	* Register network admin menus.
	*
	* @access public
	* @return void
	*/
	function network_admin_menu() {
		add_menu_page( __('CustomPress', $this->text_domain), __('CustomPress', $this->text_domain), 'manage_network', 'ct_content_types', array( &$this, 'handle_content_types_page_requests' ) );

		$page_content_types = add_submenu_page( 'ct_content_types' , __( 'Content Types', $this->text_domain ), __( 'Content Types', $this->text_domain ), 'manage_network', 'ct_content_types', array( &$this, 'handle_content_types_page_requests' ) );
		$page_settings      = add_submenu_page( 'ct_content_types', __('Settings', $this->text_domain), __('Settings', $this->text_domain), 'manage_network', 'cp_main', array( &$this, 'handle_settings_page_requests' ) );

		$export_settings = add_submenu_page( 'ct_content_types', __('Export / Import', $this->text_domain), __('Export / Import', $this->text_domain), 'manage_network', 'ct_export', array( &$this, 'handle_export' ) );

		add_action( 'admin_print_scripts-' . $page_content_types, array( &$this, 'enqueue_scripts' ) );
		add_action( 'admin_print_scripts-' . $page_settings, array( &$this, 'enqueue_settings_scripts' ) );
		add_action( 'admin_print_scripts-' . $export_settings, array( &$this, 'enqueue_scripts' ) );
		add_action( 'admin_head-' . $page_settings, array( &$this, 'ajax_actions' ) );
	}

	function handle_export(){
		$this->render_admin('export');
	}
	/**
	* Load scripts on plugin specific admin pages only.
	*
	* @return void
	*/
	function enqueue_scripts() {
		wp_enqueue_style( 'ct-admin-styles', $this->plugin_url . 'ui-admin/css/styles.css');
		wp_enqueue_script( 'ct-admin-scripts', $this->plugin_url . 'ui-admin/js/ct-scripts.js', array( 'jquery' ) );
		wp_enqueue_script('jquery-combobox');
		wp_enqueue_style('jquery-combobox');
		wp_enqueue_script('jquery-validate');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker-lang');
	}

	/**
	* Load scripts on plugin specific admin pages only.
	*
	* @return void
	*/
	function enqueue_settings_scripts() {
		wp_enqueue_script( 'settings-admin-scripts', $this->plugin_url . 'ui-admin/js/settings-scripts.js', array( 'jquery' ) );
		wp_enqueue_script('jquery-combobox');
		wp_enqueue_style('jquery-combobox');
		wp_enqueue_script('jquery-validate');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker-lang');
	}

	/**
	* Load styles for "Custom Fields" on add/edit post type pages only.
	*
	* @return void
	*/
	function enqueue_custom_field_scripts() {
		wp_enqueue_style( 'ct-admin-custom-field-styles', $this->plugin_url . 'ui-admin/css/custom-fields-styles.css' );
		wp_enqueue_script('jquery-combobox');
		wp_enqueue_style('jquery-combobox');
		wp_enqueue_script('jquery-validate');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker-lang');
	}

	/**
	* Handle $_GET and $_POST requests for Settings admin page.
	*
	* @return void
	*/
	function handle_settings_page_requests() {

		//$params is the $_POST variable with slashes stripped
		$params = array_map('stripslashes_deep',$_POST);

		// Save settings
		if ( isset( $params['save'] ) && wp_verify_nonce( $params['_wpnonce'], 'verify' ) ) {

			// Set network-wide content types
			if ( is_multisite() && is_super_admin() && is_network_admin() ) {
				update_site_option( 'allow_per_site_content_types', (bool) $params['enable_subsite_content_types'] );
				update_site_option( 'display_network_content_types', (bool) $params['display_network_content_types'] );
			}

			// Create template file
			if ( !empty( $params['post_type_file'] ) ) {
				$this->create_post_type_files( $params['post_type_file'] );
			}

			// Process post types display
			$options = $this->get_options();


			$dpt = array();
			$args = array( 'page' => 'home', 'post_type' => ( isset( $params['cp_post_type']['home'] ) ) ? $params['cp_post_type']['home'] : null );
			$display_post_types['display_post_types'][$args['page']] = $args;

			$args = array( 'page' => 'archive', 'post_type' => ( isset( $params['cp_post_type']['archive'] ) ) ? $params['cp_post_type']['archive'] : null );
			$display_post_types['display_post_types'][$args['page']] = $args;

			$args = array( 'page' => 'front_page', 'post_type' => ( isset( $params['cp_post_type']['front_page'] ) ) ? $params['cp_post_type']['front_page'] : null );
			$display_post_types['display_post_types'][$args['page']] = $args;

			$args = array( 'page' => 'search', 'post_type' => ( isset( $params['cp_post_type']['search'] ) ) ? $params['cp_post_type']['search'] : null );
			$display_post_types['display_post_types'][$args['page']] = $args;

			$options = array_merge( $options , $display_post_types );

			//Update datepicker settings
			if (! empty($params['datepicker_theme']) && ! empty($params['date_format']))
			$options = array_merge( $options, array('datepicker_theme' => $params['datepicker_theme'], 'date_format' => $params['date_format']  ) );
			update_option( $this->options_name, $options );
		}

		$this->render_admin('settings');
	}

	/**
	* Handle $_GET and $_POST requests for Content Types admin page.
	*
	* @return void
	*/
	function handle_content_types_page_requests() {
		$this->render_admin('navigation');
		if ( empty( $_GET['ct_content_type'] ) || $_GET['ct_content_type'] == 'post_type' ) {
			if ( isset( $_GET['ct_add_post_type'] ) )
			$this->render_admin('add-post-type');
			elseif ( isset( $_GET['ct_edit_post_type'] ) )
			$this->render_admin('edit-post-type');
			else
			$this->render_admin('post-types');
		}
		elseif ( $_GET['ct_content_type'] == 'taxonomy' ) {
			if ( isset( $_GET['ct_add_taxonomy'] ) )
			$this->render_admin('add-taxonomy');
			elseif ( isset( $_GET['ct_edit_taxonomy'] ) )
			$this->render_admin('edit-taxonomy');
			else
			$this->render_admin('taxonomies');
		}
		elseif ( $_GET['ct_content_type'] == 'custom_field' ) {
			if ( isset( $_GET['ct_add_custom_field'] ) )
			$this->render_admin('add-custom-field');
			elseif ( isset( $_GET['ct_edit_custom_field'] ) )
			$this->render_admin('edit-custom-field');
			else
			$this->render_admin('custom-fields');
		}
		elseif ( $_GET['ct_content_type'] == 'shortcodes' ) {
			$this->render_admin('shortcodes');
		}
	}

	/**
	* Ajax callback which gets the post types associated with each page.
	*
	* @return JSON Encoded string
	*/
	function ajax_get_caps() {
		global $wp_roles;

		if ( !current_user_can( 'manage_options' ) ) die(-1);

		if(empty($_REQUEST['role'])) die(-1);
		if(empty($_REQUEST['post_type'])) die(-1);

		$role = $_REQUEST['role'];
		$post_type = $_REQUEST['post_type'];

		if ( !$wp_roles->is_role( $role ) )
		die(-1);

		if ( !post_type_exists( $post_type ) )
		die(-1);

		$role_obj = $wp_roles->get_role( $role );

		$all_caps = $this->all_capabilities($post_type);

		$response = array_intersect( array_keys( $role_obj->capabilities ), $all_caps );
		$response = array_flip( $response );

		// response output
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		die();
	}

	/**
	* Save admin options.
	*
	* @return void die() if _wpnonce is not verified
	*/
	function ajax_save() {

		check_admin_referer( 'submit_post_type' );

		if ( !current_user_can( 'manage_options' ) ) die(-1);

		// add/remove capabilities
		global $wp_roles;

		$role = $_REQUEST['roles'];
		$post_type = $_REQUEST['post_type'];

		$all_caps = $this->all_capabilities($post_type);

		$to_add = array_keys( (array)$_REQUEST['capabilities'] );
		$to_remove = array_diff( $all_caps, $to_add );

		foreach ( $to_remove as $capability ) {
			$wp_roles->remove_cap( $role, $capability );
		}

		foreach ( $to_add as $capability ) {
			$wp_roles->add_cap( $role, $capability );
		}
		die(1);
	}
}

/* Initiate Admin Class */
if(is_admin()) {
	$CustomPress_Core = new CustomPress_Core_Admin();
}
endif;