/*
 * File: plugin.php
 * Project: ILC_WP_PLUGIN
 * Version <<projectversion>>
 * File Created: Monday, 18th January 2021 5:29:18 pm
 * Author: Eoan O'Dea (eoan@web-space.design)
 * -----
 * File Description: 
 * Last Modified: Monday, 18th January 2021 6:30:23 pm
 * Modified By: Eoan O'Dea (eoan@web-space.design>)
 * -----
 * Copyright 2021 WebSpace, WebSpace
 */






<?php
namespace ILC;

// use ILC\PageSettings\Page_Settings;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {
	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_script( 'ilc-elementor-widgets', plugins_url( '/assets/js/ilc-bkmk.js', __FILE__ ), [ 'jquery' ], false, true );



		// $plugin_url = plugins_url( '/assets/js/ilc-bkmk.js', __FILE__ );
		// $plugin_data = get_plugin_data(__FILE__);
		// $plugin_version = $plugin_data['Version'];
		// wp_enqueue_script('script', $plugin_url, [ 'jquery' ], false, true );

	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function editor_scripts() {
		add_filter( 'script_loader_tag', [ $this, 'editor_scripts_as_a_module' ], 10, 2 );

		wp_enqueue_script(
			'ilc-elementor-widgets-editor',
			plugins_url( '/assets/js/editor/editor.js', __FILE__ ),
			[
				'elementor-editor',
			],
			'1.2.1',
			true
		);
	}

	/**
	 * Force load editor script as a module
	 *
	 * @since 1.2.1
	 *
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function editor_scripts_as_a_module( $tag, $handle ) {
		if ( 'ilc-elementor-widgets-editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}

		return $tag;
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/skins/skin-ilc-cards.php' );
		require_once( __DIR__ . '/widgets/ilc-carousel.php' );
		require_once( __DIR__ . '/widgets/ilc-post-list.php' );
		require_once( __DIR__ . '/interface/wpfp-interface.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ILC_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ILC_Posts() );
		// \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Inline_Editing() );
	}

	/**
	 * Add page settings controls
	 *
	 * Register new settings for a document page settings.
	 *
	 * @since 1.2.1
	 * @access private
	 */
	private function add_page_settings_controls() {
		// require_once( __DIR__ . '/page-settings/manager.php' );
		// new Page_Settings();
	}

	public function ajax_check_user_logged_in() {
		echo is_user_logged_in()?'yes':'no';
		die();
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );
		
		// $this->add_page_settings_controls();

		// Check if the user is logged in
		add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
		add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');

		// add_action('wp_footer', 'widget_scripts');

	}
}

// Instantiate Plugin Class
Plugin::instance();
