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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Inc {
	const POST_NAME = 'easy_site_builder';
	private static $_instance = null;


	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'init' ] );
        require_once(plugin_dir_path( __FILE__ ).'admin/cpt.php' );
		require_once(plugin_dir_path( __FILE__ ).'admin/metabox1.php' );
		add_action('get_header', [$this, '_renderSiteHeader'], 11);
		add_action('get_footer', [$this, '_renderSiteFooter'], 11);
		add_filter( 'template_include', [$this, 'template_include'], 99 );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ], 2 );
		add_action( 'easy_site_builder_template_load', [ $this, 'template_load' ] );
		//$this->builder_query('header');
	}

	public function init() {

		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		//add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
        
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ]);
		//add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] );

		
	}

	public function register_widgets( $widgets_manager ) {

		require_once( plugin_dir_path( __FILE__ ) . 'widgets/page-title.php' );
        $widgets_manager->register( new Page_Title_Widget() );


	}


	public function register_controls( $controls_manager ) {

		//require_once( __DIR__ . '/includes/controls/control-1.php' );
		//require_once( __DIR__ . '/includes/controls/control-2.php' );

		//$controls_manager->register( new Control_1() );
		//$controls_manager->register( new Control_2() );

	}

	function register_dynamic_tags( $dynamic_tags_manager ) {

		//require_once(plugin_dir_path( __FILE__ ) . 'dynamic-tags/test.php' );
	
		//$dynamic_tags_manager->register( new \Elementor_Dynamic_Tag_Random_Number );
	
	}

    // Creating New Widget Category
    function register_widget_categories( $elements_manager ) {
        $elements_manager->add_category(
            'easy-site-builder-for-elementor',
            [
                'title' => esc_html__( 'Easy Site Builder', 'easy-site-builder-for-elementor' ),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    // Register scripts and styles for Elementor Glister widgets.
    function register_scripts() {
        /* Scripts */
        //wp_register_script( 'slick.min',  EASYSITEBUILDERFORELEMENTOR_URL.'assets/js/slick.min.js', [ 'jquery' ], '1.8.0', true );
        /* Styles */
        //wp_enqueue_style( 'glister-addons', EASYSITEBUILDERFORELEMENTOR_URL.'assets/css/glister-addons.css');

		if (class_exists('\Elementor\Core\Files\CSS\Post')) {
			if(is_page()){
				$Elementor_css = new \Elementor\Core\Files\CSS\Post(absint(149));
				$Elementor_css->enqueue();
			}
		}

    }


	function _renderSiteHeader(){
		$header_tmpl_id = $this->builder_query('header');
		if ($header_tmpl_id) {
            require plugin_dir_path( __FILE__ ) . 'templates/header.php';
            $templates   = [];
			$templates[] = 'header.php';
			// Avoid running wp_head hooks again.
			remove_all_actions( 'wp_head' );
			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
        }
	}

	public function _renderSiteFooter($name)
    {
        $footer_tmpl_id = $this->builder_query('footer');

        if ($footer_tmpl_id) {
            require plugin_dir_path( __FILE__ ) . 'templates/footer.php';
            $templates   = [];
			$templates[] = 'footer.php';
			// Avoid running wp_footer hooks again.
			remove_all_actions( 'wp_footer' );
			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
        }
    }



	public function template_include($template){
		$the_tmpl_id = $this->builder_query('theme_template');

		if ($the_tmpl_id) {
			$template = plugin_dir_path( __FILE__ ) . 'templates/template.php';
		}

		return $template;
	}

	public function template_load(){

		$the_tmpl_id = $this->builder_query('theme_template');
		echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($the_tmpl_id, false);
	}


	public static function builder_query($place){
		$the_id = 0;
		$id_search = 0;
		$id_404 = 0;
		$id_home = 0;
		$id_front = 0;
		$id_page = 0;
		$id_singular = 0;
		$id_archive = 0;
		$id_tax = 0;
		

		// The Query.
		$args = array(
			'post_type' => self::POST_NAME,
			'meta_query' => array(
				array(
					'key' => 'esbfe_type',
					'value' => sanitize_text_field($place),
					'compare' => '=',
				)
			)
		);
		$the_query = new \WP_Query( $args );

		// The Loop.
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$display_on = get_post_meta( get_the_ID(), 'esbfe_display_on', true );
        		$display_not = get_post_meta( get_the_ID(), 'esbfe_display_not', true );
        		$user_roles = get_post_meta( get_the_ID(), 'esbfe_user_roles', true );
				//if($this->user_rolse_check($user_roles, get_the_ID())){
					if(is_search()){

						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array('special-search', $display_on)) {
							$id_search = get_the_ID();
						}
						if( is_array($display_not) && in_array("special-search", $display_not) ){
							$the_id = 0;
						}
						
					}
					
					elseif(is_404()){
						
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array('special-404', $display_on)) {
							$id_404 = get_the_ID();
						}
						if( is_array($display_not) && in_array("special-404", $display_not) ){
							$the_id = 0;
						}
						
					}

					elseif(is_home()){

						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array('special-blog', $display_on)) {
							$id_home = get_the_ID();
						}
						if( is_array($display_not) && in_array("special-blog", $display_not) ){
							$the_id = 0;
						}
						
					}

					elseif(is_front_page()){
						
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array('special-front', $display_on)) {
							$id_front = get_the_ID();
						}
						if( is_array($display_not) && in_array("special-front", $display_not) ){
							$the_id = 0;
						}
						
					}

					elseif(is_page()){
						
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array('special-page', $display_on)) {
							$id_page = get_the_ID();
						}
						if( is_array($display_not) && in_array("special-page", $display_not) ){
							$the_id = 0;
						}
						
					}

					elseif(!is_singular('page') && is_singular()){

						$saved_cpt_string = get_post_type( self::getCurrentPageId() )."|all";
						
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array($saved_cpt_string, $display_on)) {
							$id_singular = get_the_ID();
						}
						if( is_array($display_not) && in_array($saved_cpt_string, $display_not) ){
							$the_id = 0;
						}
						
					}

					elseif(is_tax() || is_category() || is_tag()){

						$saved_cpt_string = 'post|taxarchive|category';
						$saved_cpt_string = self::getCurrentTaxConditionalString();
						
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array($saved_cpt_string, $display_on)) {
							$id_tax = get_the_ID();
						}
						if( is_array($display_not) && in_array($saved_cpt_string, $display_not) ){
							$the_id = 0;
						}
						
					}

					elseif(is_archive()){

						$saved_cpt_string = get_post_type( self::getCurrentPageId() )."|archive";
						
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}
						if (is_array($display_on) && in_array($saved_cpt_string, $display_on)) {
							$id_archive = get_the_ID();
						}
						if( is_array($display_not) && in_array($saved_cpt_string, $display_not) ){
							$the_id = 0;
						}
						
					}

					
				//}
			}
		}
		// Restore original Post Data.
		wp_reset_postdata();

		if(is_search() && $id_search){
			return $id_search;
		}
		elseif(is_404() && $id_404){
			return $id_404;
		}
		elseif(is_home() && $id_home){
			return $id_home;
		}
		elseif(is_front_page() && $id_front){
			return $id_front;
		}
		elseif(is_page() && $id_page){
			return $id_page;
		}
		elseif(is_singular() && $id_singular && !is_singular('page')){
			return $id_singular;
		}
		elseif((is_tax()  || is_category() || is_tag()) && $id_tax){
			return $id_tax;
		}
		elseif(is_archive() && $id_archive){
			return $id_archive;
		}
		else{
			return $the_id;
		}

	}


	public static function getCurrentPageId(){
		global $wp_query;

        if (!is_main_query()) {
            return 0;
        }

        if (is_home() && !is_front_page()) {
            return (int)get_option('page_for_posts');
        } elseif (!is_home() && is_front_page()) {
            return (int)get_option('page_on_front');
        } elseif (function_exists('is_shop') && is_shop()) {
            return wc_get_page_id('shop');
        } elseif (is_privacy_policy()) {
            return (int)get_option('wp_page_for_privacy_policy');
        } elseif (!empty($wp_query->post->ID)) {
            return (int)$wp_query->post->ID;
        } else {
            return 0;
        }
	}

	public static function getCurrentTaxConditionalString(){
		global $wp_query;

        if (!is_main_query()) {
            return 0;
        }

		$get_queried_object = get_queried_object();
		$taxonomy = get_taxonomy( $get_queried_object->taxonomy );
		$posttype = $taxonomy->object_type;

		return $posttype[0]."|taxarchive|".$get_queried_object->taxonomy;
	}

}
Inc::instance();