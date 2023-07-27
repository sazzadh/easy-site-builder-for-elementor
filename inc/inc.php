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

		if(is_page()){
			$template = plugin_dir_path( __FILE__ ) . 'templates/template.php';
		}

		return $template;
	}


	public static function builder_query($place){
		$the_id = 0;
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

						if( is_array($display_not) && in_array("special-search", $display_not) ){
							$the_id = 0;
						}
						if (is_array($display_on) && in_array("basic-global", $display_on) ) {
							$the_id = get_the_ID();
						}elseif (is_array($display_on) && in_array('special-search', $display_on)) {
							$the_id = get_the_ID();
						}
						
					}
				//}
			}
		}
		// Restore original Post Data.
		wp_reset_postdata();

		return $the_id;
	}


	public function user_rolse_check($meta, $post_id){
		if(in_array("subscriber", $meta)){

		}
	}

}
Inc::instance();