<?php
namespace EasySiteBuilderForElementor;

use Elementor\Widget_Heading;
use Elementor\Plugin;

/*
To reuse this section youu need to rename the following items
Prefix: esbfe_
Textdomain: easy-site-builder-for-elementor
Plugin Name: Easy Site Builder For Elementor
Post Type Name: easy_site_builder
Plugin Short Name: Easy Site Builder
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Page_Title_Widget extends Widget_Heading {


	public function get_name() {
		return 'page-title';
	}


	public function get_title() {
		return esc_html__( 'Page Title', 'easy-site-builder-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-archive-title';
	}

	public function get_categories() {
		return [ 'easy-site-builder-for-elementor' ];
	}

	public function get_keywords() {
		return [ 'title', 'heading', 'page' ];
	}




    protected function register_controls() {
		parent::register_controls();

       
		$this->update_control(
			'title',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => $this->custom_get_page_title(),
			],
		);

		$this->update_control(
			'header_size',
			[
				'default' => 'h1',
			]
		);
	}


    public function custom_get_page_title(){
        if ( is_home() && 'yes' !== $this->get_settings( 'show_home_title' ) ) {
			return;
		}

        return wp_kses_post( get_the_title() );
    }

	public function render() {
		
			return parent::render();
		
	}
}
