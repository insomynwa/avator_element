<?php
namespace AvatorElement\Modules\AssetsManager\AssetTypes;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use AvatorElement\Plugin;
use AvatorElement\Modules\AssetsManager\Classes;
use Elementor\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Icons_Manager {

	const CAPABILITY = 'manage_options';

	const CPT = 'elementor_icons';

	const FONTS_OPTION_NAME = 'elementor_fonts_manager_fonts';

	const FONTS_NAME_TYPE_OPTION_NAME = 'elementor_fonts_manager_font_types';

	private $post_type_object;

	private $enqueued_fonts = [];

	protected $icon_types = [];

	/**
	 * get a font type object for a given type
	 *
	 * @param null $type
	 *
	 * @return array|bool|\AvatorElement\Modules\AssetsManager\Classes\Font_Base
	 */
	public function get_icon_type_object( $type = null ) {
		if ( null === $type ) {
			return $this->icon_types;
		}

		if ( isset( $this->icon_types[ $type ] ) ) {
			return $this->icon_types[ $type ];
		}

		return false;
	}

	/**
	 * Add a font type to the font manager
	 *
	 * @param string            $icon_type
	 * @param Classes\Assets_Base $instance
	 */
	public function add_icon_type( $icon_type, $instance ) {
		$this->icon_types[ $icon_type ] = $instance;
	}

	/**
	 * Register elementor icon set custom post type
	 */
	public function register_post_type() {
		$labels = [
			'name' => _x( 'Custom Icons', 'Elementor Icon', 'avator-element' ),
			'singular_name' => _x( 'Icon Set', 'Elementor Icon', 'avator-element' ),
			'add_new' => _x( 'Add New', 'Elementor Icon', 'avator-element' ),
			'add_new_item' => _x( 'Add New Icon Set', 'Elementor Icon', 'avator-element' ),
			'edit_item' => _x( 'Edit Icon Set', 'Elementor Icon', 'avator-element' ),
			'new_item' => _x( 'New Icon Set', 'Elementor Icon', 'avator-element' ),
			'all_items' => _x( 'All Icons', 'Elementor Icon', 'avator-element' ),
			'view_item' => _x( 'View Icon', 'Elementor Icon', 'avator-element' ),
			'search_items' => _x( 'Search Font', 'Elementor Icon', 'avator-element' ),
			'not_found' => _x( 'No Fonts found', 'Elementor Icon', 'avator-element' ),
			'not_found_in_trash' => _x( 'No Icon found in Trash', 'Elementor Icon', 'avator-element' ),
			'parent_item_colon' => '',
			'menu_name' => _x( 'Custom Icons', 'Elementor Icon', 'avator-element' ),
		];

		$args = [
			'labels' => $labels,
			'public' => false,
			'rewrite' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => [ 'title' ],
		];

		$this->post_type_object = register_post_type( self::CPT, $args );
	}

	public function post_updated_messages( $messages ) {
		$messages[ self::CPT ] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Icon Set updated.', 'avator-element' ),
			2 => __( 'Custom field updated.', 'avator-element' ),
			3 => __( 'Custom field deleted.', 'avator-element' ),
			4 => __( 'Icon Set updated.', 'avator-element' ),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Icon Set restored to revision from %s', 'avator-element' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Icon Set saved.', 'avator-element' ),
			7 => __( 'Icon Set saved.', 'avator-element' ),
			8 => __( 'Icon Set submitted.', 'avator-element' ),
			9 => __( 'Icon Set updated.', 'avator-element' ),
			10 => __( 'Icon Set draft updated.', 'avator-element' ),
		];

		return $messages;
	}

	/**
	 * Add Font manager link to admin menu
	 */
	public function register_admin_menu() {
		$menu_title = _x( 'Custom Icons', 'Elementor Font', 'avator-element' );
		add_submenu_page(
			Settings::PAGE_ID,
			$menu_title,
			$menu_title,
			self::CAPABILITY,
			'edit.php?post_type=' . self::CPT
		);
	}

	public function redirect_admin_old_page_to_new() {
		if ( ! empty( $_GET['page'] ) && 'elementor_custom_icons' === $_GET['page'] ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . self::CPT ) );
			die;
		}
	}

	/**
	 * Clean up admin Font manager admin listing
	 */
	public function clean_admin_listing_page() {
		global $typenow;

		if ( self::CPT !== $typenow ) {
			return;
		}

		add_filter( 'months_dropdown_results', '__return_empty_array' );
		add_filter( 'screen_options_show_screen', '__return_false' );
	}

	public function post_row_actions( $actions, $post ) {
		if ( self::CPT !== $post->post_type ) {
			return $actions;
		}

		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}

	public function add_finder_item( array $categories ) {
		$categories['settings']['items']['custom-icons'] = [
			'title' => __( 'Custom Icons', 'avator-element' ),
			'icon' => 'favorite',
			'url' => admin_url( 'edit.php?post_type=' . self::CPT ),
			'keywords' => [ 'custom', 'icons', 'elementor' ],
		];

		return $categories;
	}

	/**
	 * Register Font Manager action and filter hooks
	 */
	protected function actions() {
		add_action( 'init', [ $this, 'register_post_type' ] );

		if ( is_admin() ) {
			add_action( 'init', [ $this, 'redirect_admin_old_page_to_new' ] );
			add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 50 );
			add_action( 'admin_head', [ $this, 'clean_admin_listing_page' ] );
		}

		add_filter( 'post_updated_messages', [ $this, 'post_updated_messages' ] );
		add_filter( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );

		add_filter( 'elementor/finder/categories', [ $this, 'add_finder_item' ] );

		/**
		 * Elementor icons manager loaded.
		 *
		 * Fires after the icons manager was fully loaded and instantiated.
		 *
		 * @since 2.0.0
		 *
		 * @param Fonts_Manager $this An instance of icons manager.
		 */
		do_action( 'avator_element/icons_manager_loaded', $this );
	}

	/**
	 * Fonts_Manager constructor.
	 */
	public function __construct() {
		$this->actions();
		$this->add_icon_type( 'custom', new Icons\Custom_Icons() );
		$this->add_icon_type( 'font-awesome-pro', new Icons\Font_Awesome_Pro() );
	}
}
