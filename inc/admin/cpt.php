<?php
namespace EasySiteBuilderForElementor;

/*
To reuse this section youu need to rename the following items
Prefix: esbfe_
Textdomain: easy-site-builder-for-elementor
Plugin Name: Easy Site Builder For Elementor
Post Type Name: easy_site_builder
Plugin Short Name: Easy Site Builder
*/

class AdminCpt{
    const NAME_OF_PLUGIN = 'Easy Site Builder For Elementor';
    const SLUG_OF_PLUGIN = 'easy-site-builder-for-elementor';
    const POST_NAME = 'easy_site_builder';

    private static $_instance = null;


	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

    public function __construct() {
		add_action( 'init', [$this, 'CustomPostTypeRegister'], 0 );
        add_action('elementor/init', [$this, 'enable_elementor_for_postType']);
	}

    // Register Custom Post Type
    function CustomPostTypeRegister() {

        $labels = array(
            'name'                  => _x( 'Site Builder', 'Post Type General Name', 'easy-site-builder-for-elementor' ),
            'singular_name'         => _x( 'Site Builder', 'Post Type Singular Name', 'easy-site-builder-for-elementor' ),
            'menu_name'             => __( 'Site Builder', 'easy-site-builder-for-elementor' ),
            'name_admin_bar'        => __( 'Site Builder', 'easy-site-builder-for-elementor' ),
            'archives'              => __( 'Item Archives', 'easy-site-builder-for-elementor' ),
            'attributes'            => __( 'Item Attributes', 'easy-site-builder-for-elementor' ),
            'parent_item_colon'     => __( 'Parent Item:', 'easy-site-builder-for-elementor' ),
            'all_items'             => __( 'All Items', 'easy-site-builder-for-elementor' ),
            'add_new_item'          => __( 'Add New Item', 'easy-site-builder-for-elementor' ),
            'add_new'               => __( 'Add New Layout', 'easy-site-builder-for-elementor' ),
            'new_item'              => __( 'New Item', 'easy-site-builder-for-elementor' ),
            'edit_item'             => __( 'Edit Item', 'easy-site-builder-for-elementor' ),
            'update_item'           => __( 'Update Item', 'easy-site-builder-for-elementor' ),
            'view_item'             => __( 'View Item', 'easy-site-builder-for-elementor' ),
            'view_items'            => __( 'View Items', 'easy-site-builder-for-elementor' ),
            'search_items'          => __( 'Search Item', 'easy-site-builder-for-elementor' ),
            'not_found'             => __( 'Not found', 'easy-site-builder-for-elementor' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'easy-site-builder-for-elementor' ),
            'featured_image'        => __( 'Featured Image', 'easy-site-builder-for-elementor' ),
            'set_featured_image'    => __( 'Set featured image', 'easy-site-builder-for-elementor' ),
            'remove_featured_image' => __( 'Remove featured image', 'easy-site-builder-for-elementor' ),
            'use_featured_image'    => __( 'Use as featured image', 'easy-site-builder-for-elementor' ),
            'insert_into_item'      => __( 'Insert into item', 'easy-site-builder-for-elementor' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'easy-site-builder-for-elementor' ),
            'items_list'            => __( 'Items list', 'easy-site-builder-for-elementor' ),
            'items_list_navigation' => __( 'Items list navigation', 'easy-site-builder-for-elementor' ),
            'filter_items_list'     => __( 'Filter items list', 'easy-site-builder-for-elementor' ),
        );
        $args = array(
            'label'                 => __( 'Site Builder', 'easy-site-builder-for-elementor' ),
            'description'           => __( 'Create layout for the site', 'easy-site-builder-for-elementor' ),
            'labels'                => $labels,
            'supports'              => array( 'title' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 59,
            'menu_icon'             => 'dashicons-editor-table',
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => false,
        );
        register_post_type( self::POST_NAME, $args );

    }

    function enable_elementor_for_postType(){
        add_post_type_support( self::POST_NAME, 'elementor' );
    }
}

AdminCpt::instance();