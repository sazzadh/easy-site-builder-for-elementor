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

class AdminMetabox{
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
        add_action( 'admin_init', [$this, 'register_metabox'], 2 );
        add_action( 'save_post', [$this, 'meta_box_save'] );
	}

    /**
	 * Register meta box(es).
	 */
	function register_metabox() {
		add_meta_box( 
            self::SLUG_OF_PLUGIN, 
            __('Easy Site Builder Options', 'easy-site-builder-for-elementor'), 
            [$this, 'meta_box_callback'], 
            self::POST_NAME, 
            'normal', 
            'default'
        );
	}

    function meta_box_callback($post ){

        wp_nonce_field( 'esbfe_meta_nounce', 'esbfe_meta_nounce' );

        //$mv = meta filed value
        $mv_type = get_post_meta( $post->ID, 'esbfe_type', true );
        $post_id = $post->ID;

        //echo $this->get_option('footer', 'display_on', 'global');

        echo "<pre>";
        //print_r(get_option('esbfe_header'));
        echo "</pre>";
        ?>
        <style>
            .postbox .esbfe_meta_holder h2{
                font-weight: bold;
                margin: 0 0 3px !important;
                color: #333;
                padding: 0 !important;
            }
            .postbox .esbfe_meta_holder h3{
                margin-top:3px;
                margin-bottom:3px;
            }
            .postbox .esbfe_meta_holder h4{
                color: #2271B1;
                background-color: #F6F7F7;
                padding: 3px 7px;
                margin-left: -15px;
                margin-right: -15px;
                border-bottom: #C7C7C7 solid 1px;
                border-top: #C7C7C7 solid 1px;
                margin-bottom: 13px;
            }
            .esbfe_meta_holder{
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .esbfe_meta_type{
                width: 100%;
                padding-bottom:20px;
            }
            .esbfe_meta_shortcode{
                width: 100%;
            }
            .esbfe_meta_display{
                width: 30.33%;
                border:solid 1px #00800052;
                padding: 15px;
                box-sizing: border-box;
            }
            .esbfe_meta_notdisplay{
                width: 30.33%;
                border:solid 1px #ff000038;
                padding: 15px;
                box-sizing: border-box;
            }
            .esbfe_meta_user{
                width: 30.33%;
                border:solid 1px #00000038;
                padding: 15px;
                box-sizing: border-box;
            }
            .esbfe_meta_type{
                display: flex;
                align-items: center;
            }
            .esbfe_meta_type > div{
                padding: 4px;
            }
            .esbfe_checkbox_holder{
                display: flex;
                align-items: flex-start;
                margin-bottom:10px;
            }
            .esbfe_checkbox_holder input[type="checkbox"]{
                margin-top:2px;
            }

            .esbfe_checkbox_override,
            .esbfe_checkbox_cancleOverride{
                color:#B32D2E;
            }
            .esbfe_checkbox_cancleOverride{
                color:#2271B1;
            }


            .esbfe_meta_displayOn_header,
            .esbfe_meta_displayOn_footer,
            .esbfe_meta_displayOn_template,
            .esbfe_meta_displayNot_header,
            .esbfe_meta_displayNot_footer,
            .esbfe_meta_displayNot_template,
            .esbfe_meta_userRole_header,
            .esbfe_meta_userRole_footer,
            .esbfe_meta_userRole_template,
            .esbfe_meta_shortcode{
                display:none;
            }
            .esbfe_meta_displayOn_header.active,
            .esbfe_meta_displayOn_footer.active,
            .esbfe_meta_displayOn_template.active,
            .esbfe_meta_displayNot_header.active,
            .esbfe_meta_displayNot_footer.active,
            .esbfe_meta_displayNot_template.active,
            .esbfe_meta_userRole_header.active,
            .esbfe_meta_userRole_footer.active,
            .esbfe_meta_userRole_template.active,
            .esbfe_meta_shortcode.active{
                display:block;
            }
        </style>
        <div class="widefat esbfe_meta_holder">
            <div class="esbfe_meta_type">
                <div>
                    <h2><?php _e('Type of Template', 'easy-site-builder-for-elementor') ?></h2>
                </div>
                <div>
                    <select name="esbfe_type" id="esbfe_type">
                        <option value="blank" <?php echo selected( $mv_type, 'blank' ); ?>>Select an Option</option>
                        <option value="header" <?php echo selected( $mv_type, 'header' ); ?>>Header</option>
                        <option value="footer" <?php echo selected( $mv_type, 'footer' ); ?>>Footer</option>
                        <option value="section" <?php echo selected( $mv_type, 'section' ); ?>>Section</option>
                        <option value="template" <?php echo selected( $mv_type, 'template' ); ?>>Theme's Template</option>
                    </select>
                </div>
            </div>
            <div class="esbfe_meta_shortcode <?php $this->add_active_class_by_type($post_id, 'section'); ?>">
                <input type="text" readonly="readonly" onfocus="this.select();" name="esbfe_meta_shortcode" value='[esbfe="<?php echo esc_attr( $post_id ); ?>"]'>
            </div>

            <div class="esbfe_meta_display">
                <h3><?php _e('Display On:', 'easy-site-builder-for-elementor') ?></h3>
                <div class="esbfe_meta_displayOn_header <?php $this->add_active_class_by_type($post_id, 'header'); ?>">
                    <p class="description"><?php _e('This Header will show to the following area of the site:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->headerFooterTemplate_display_OnNot_options_form($post_id, 'on', 'header'); ?>
                </div>
                <div class="esbfe_meta_displayOn_footer <?php $this->add_active_class_by_type($post_id, 'footer'); ?>" >
                <p class="description"><?php _e('This Footer will show to the following area of the site:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->headerFooterTemplate_display_OnNot_options_form($post_id, 'on', 'footer'); ?>
                </div>
                <div class="esbfe_meta_displayOn_template <?php $this->add_active_class_by_type($post_id, 'template'); ?>" >
                    <p class="description"><?php _e('This Template will show to the following area of the site:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->headerFooterTemplate_display_OnNot_options_form($post_id, 'on', 'template'); ?>
                </div>
            </div>
            <div class="esbfe_meta_notdisplay">
                <h3><?php _e('Do Not Display:', 'easy-site-builder-for-elementor') ?></h3>
                <div class="esbfe_meta_displayNot_header <?php $this->add_active_class_by_type($post_id, 'header'); ?>">
                    <p class="description"><?php _e('This Header will not show to the following area of the site:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->headerFooterTemplate_display_OnNot_options_form($post_id, 'not', 'header'); ?>
                </div>
                <div class="esbfe_meta_displayNot_footer <?php $this->add_active_class_by_type($post_id, 'footer'); ?>">
                    <p class="description"><?php _e('This Footer will not show to the following area of the site:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->headerFooterTemplate_display_OnNot_options_form($post_id, 'not', 'footer'); ?>
                </div>
                <div class="esbfe_meta_displayNot_template <?php $this->add_active_class_by_type($post_id, 'template'); ?>">
                    <p class="description"><?php _e('This Template will not show to the following area of the site:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->headerFooterTemplate_display_OnNot_options_form($post_id, 'not', 'template'); ?>
                </div>
            </div>
            <div class="esbfe_meta_user">
                <h3><?php _e('User Roles:', 'easy-site-builder-for-elementor') ?></h3>
                <div class="esbfe_meta_userRole_header <?php $this->add_active_class_by_type($post_id, 'header'); ?>">
                    <p class="description"><?php _e('Show this Header with the following User Role:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->userRole_options_form($post_id, 'header'); ?>
                </div>
                <div class="esbfe_meta_userRole_footer <?php $this->add_active_class_by_type($post_id, 'footer'); ?>">
                    <p class="description"><?php _e('Show this Footer with the following User Role:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->userRole_options_form($post_id, 'footer'); ?>
                </div>
                <div class="esbfe_meta_userRole_template <?php $this->add_active_class_by_type($post_id, 'template'); ?>">
                    <p class="description"><?php _e('Show this Template with the following User Role:', 'easy-site-builder-for-elementor') ?></p>    
                    <?php $this->userRole_options_form($post_id, 'template'); ?>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            jQuery(document).ready(function($){;

                $("#esbfe_type").change(function(){
                    let value =  $("#esbfe_type option:selected");

                    $('.esbfe_meta_display > div').removeClass('active');
                    $('.esbfe_meta_notdisplay > div').removeClass('active');
                    $('.esbfe_meta_user > div').removeClass('active');
                    $('.esbfe_meta_shortcode').removeClass('active');

                    if(value.val() === 'header'){
                       $('.esbfe_meta_displayOn_header').addClass('active');
                       $('.esbfe_meta_displayNot_header').addClass('active');
                       $('.esbfe_meta_userRole_header').addClass('active'); 
                    }
                    if(value.val() === 'footer'){
                       $('.esbfe_meta_displayOn_footer').addClass('active');
                       $('.esbfe_meta_displayNot_footer').addClass('active');
                       $('.esbfe_meta_userRole_footer').addClass('active'); 
                    }
                    if(value.val() === 'theme_template'){
                       $('.esbfe_meta_displayOn_template').addClass('active');
                       $('.esbfe_meta_displayNot_template').addClass('active');
                       $('.esbfe_meta_userRole_template').addClass('active'); 
                    }
                    if(value.val() === 'section'){
                       $('.esbfe_meta_shortcode').addClass('active'); 
                    }
               });

               $( "body" ).on( "click", ".esbfe_checkbox_override", function() {
                    let current_post_id = <?php echo esc_attr( $post_id ); ?>;
                    let option_value = $(this).attr('data-option');
                    let field_id = $(this).attr('data-field-id');
                    $("#"+field_id).removeAttr("disabled");
                    $("#"+field_id).val(current_post_id);
                    $(this).removeClass('esbfe_checkbox_override');
                    $(this).addClass('esbfe_checkbox_cancleOverride');
                    $(this).text('<?php _e('Cancel Override', 'easy-site-builder-for-elementor') ?>');
               });

               $( "body" ).on( "click", ".esbfe_checkbox_cancleOverride", function() {
                    let current_post_id = <?php echo esc_attr( $post_id ); ?>;
                    let option_value = $(this).attr('data-option');
                    let field_id = $(this).attr('data-field-id');
                    $("#"+field_id).attr("disabled", "disabled");
                    $("#"+field_id).val(option_value);
                    $(this).removeClass('esbfe_checkbox_cancleOverride');
                    $(this).addClass('esbfe_checkbox_override');
                    $(this).text('<?php _e('Override here?', 'easy-site-builder-for-elementor') ?>');
               });

            });
        </script>
        <?php
        
    }



    function meta_box_save( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
            return;
        }

        if ( !isset( $_POST['esbfe_meta_nounce'] ) || !wp_verify_nonce( $_POST['esbfe_meta_nounce'], 'esbfe_meta_nounce' ) ){
            return;
        }

    
        if ( !current_user_can( 'edit_post', $post_id ) ){
            return;
        }
        
        /*  Save Meta
        ================================*/  
        //Save Type Meta
        if(isset($_POST['esbfe_type'])){
            update_post_meta( $post_id, 'esbfe_type', $_POST['esbfe_type'] );
        } else {
            update_post_meta( $post_id, 'esbfe_type', '' );
        }
        
        $type = get_post_meta( $post_id, 'esbfe_type', true );
        
        //display on options
        $display = 'display_on';
        $this->save_option($type, $display, 'global');
        $this->save_option($type, $display, 'singulars');
        $this->save_option($type, $display, 'archives');

        $this->save_option($type, $display, '404');
        $this->save_option($type, $display, 'search');
        $this->save_option($type, $display, 'blog');
        $this->save_option($type, $display, 'frontpage');
        $this->save_option($type, $display, 'date_archive');
        $this->save_option($type, $display, 'author_archive');

        $this->save_option($type, $display, 'post_single');
        $this->save_option($type, $display, 'post_archive');
        $this->save_option($type, $display, 'post_category');
        $this->save_option($type, $display, 'post_tag');

        $this->save_option($type, $display, 'woo_shop');
        $this->save_option($type, $display, 'woo_product');
        $this->save_option($type, $display, 'woo_search');
        $this->save_option($type, $display, 'woo_category');
        $this->save_option($type, $display, 'woo_tag');  
        
        
        //do not display options
        $display = 'display_not';
        $this->save_option($type, $display, 'global');
        $this->save_option($type, $display, 'singulars');
        $this->save_option($type, $display, 'archives');

        $this->save_option($type, $display, '404');
        $this->save_option($type, $display, 'search');
        $this->save_option($type, $display, 'blog');
        $this->save_option($type, $display, 'frontpage');
        $this->save_option($type, $display, 'date_archive');
        $this->save_option($type, $display, 'author_archive');

        $this->save_option($type, $display, 'post_single');
        $this->save_option($type, $display, 'post_archive');
        $this->save_option($type, $display, 'post_category');
        $this->save_option($type, $display, 'post_tag');

        $this->save_option($type, $display, 'woo_shop');
        $this->save_option($type, $display, 'woo_product');
        $this->save_option($type, $display, 'woo_search');
        $this->save_option($type, $display, 'woo_category');
        $this->save_option($type, $display, 'woo_tag'); 
        
        
        //user_role options
        $display = 'user_role';
        $this->save_option($type, $display, 'global');
        $this->save_option($type, $display, 'singulars');
        $this->save_option($type, $display, 'archives');

        $this->save_option($type, $display, '404');
        $this->save_option($type, $display, 'search');
        $this->save_option($type, $display, 'blog');
        $this->save_option($type, $display, 'frontpage');
        $this->save_option($type, $display, 'date_archive');
        $this->save_option($type, $display, 'author_archive');

        $this->save_option($type, $display, 'post_single');
        $this->save_option($type, $display, 'post_archive');
        $this->save_option($type, $display, 'post_category');
        $this->save_option($type, $display, 'post_tag');

        $this->save_option($type, $display, 'woo_shop');
        $this->save_option($type, $display, 'woo_product');
        $this->save_option($type, $display, 'woo_search');
        $this->save_option($type, $display, 'woo_category');
        $this->save_option($type, $display, 'woo_tag'); 

        
    }


    function save_option($type, $display, $slug){
        $name = esc_attr('esbfe_'.$type.'_'.$display.'_'.$slug);

        if(isset($_POST[$name]) && !empty($_POST[$name])){
            
            $value = sanitize_text_field($_POST[$name]);
            update_option($name, $value);
        }
    }


    function get_option($type, $display, $slug){
        $name = esc_attr('esbfe_'.$type.'_'.$display.'_'.$slug);
        return get_option($name);
    }


    /*
        Use case: 
            $type = on/not
            $place = header/footer/template
    */
    function headerFooterTemplate_display_OnNot_options_form($post_id, $type, $place){
        ?>
            <h4><?php _e('Basic', 'easy-site-builder-for-elementor') ?></h4>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_global', 
                    __('Entire Website', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_singulars', 
                    __('All Singulars', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_archives', 
                    __('All Archives', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>

            <h4><?php _e('Special Pages', 'easy-site-builder-for-elementor') ?></h4>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_404', 
                    __('404 Page', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_search',  
                    __('Search Page', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_blog',  
                    __('Blog / Posts Page', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_frontpage', 
                    __('Front Page', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_date_archive', 
                    __('Date Archive', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_author_archive', 
                    __('Author Archive', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>

            <h4><?php _e('Posts', 'easy-site-builder-for-elementor') ?></h4>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_post_single',  
                    __('Post Single', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_post_archive', 
                    __('Post Archive', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_post_category', 
                    __('Post Category Archive', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_display_'.$type.'_post_tag', 
                    __('Post Tag Archive', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>

            <?php if(function_exists('WC')){ ?>
                <h4><?php _e('WooCommerce', 'easy-site-builder-for-elementor') ?></h4>
                <?php 
                    $this->option_checkbox(
                        'esbfe_'.$place.'_display_'.$type.'_woo_shop', 
                        __('Shop Page', 'easy-site-builder-for-elementor'),
                        $post_id
                    ); 
                ?>
                <?php 
                    $this->option_checkbox(
                        'esbfe_'.$place.'_display_'.$type.'_woo_product', 
                        __('Product Page', 'easy-site-builder-for-elementor'),
                        $post_id
                    ); 
                ?>
                <?php 
                    $this->option_checkbox(
                        'esbfe_'.$place.'_display_'.$type.'_woo_search', 
                        __('Search Page', 'easy-site-builder-for-elementor'),
                        $post_id
                    ); 
                ?>
                <?php 
                    $this->option_checkbox(
                        'esbfe_'.$place.'_display_'.$type.'_woo_category', 
                        __('Category Page', 'easy-site-builder-for-elementor'),
                        $post_id
                    ); 
                ?>
                <?php 
                    $this->option_checkbox(
                        'esbfe_'.$place.'_display_'.$type.'_woo_tag',  
                        __('Tag Page', 'easy-site-builder-for-elementor'),
                        $post_id
                    ); 
                ?>
            <?php } ?>

            <?php
                $page_templates = wp_get_theme()->get_page_templates();
                if(is_array( $page_templates)){ ?>
                    <h4><?php _e('Page Templates', 'easy-site-builder-for-elementor') ?></h4>
                    <?php foreach( $page_templates as $page_template_file => $page_template_name ){ ?>
                        <?php 
                            $this->option_checkbox(
                                'esbfe_'.$place.'_display_'.$type.'_page_tpl_'.esc_attr($page_template_name), 
                                esc_attr($page_template_name),
                                $post_id
                            ); 
                        ?>
                    <?php } ?>
            <?php  } ?>

            <?php
            // Get all post types
            $args       = array(
                'public' => true,
            );
            $post_types = get_post_types( $args, 'objects' );

            foreach ( $post_types as $post_type_obj ):
                $labels = get_post_type_labels( $post_type_obj );
                $name = $post_type_obj->name;
                if(
                    ($name != 'easy_site_builder') && 
                    ($name != 'elementor_library') && 
                    ($name != 'elementor-hf') && 
                    ($name != 'attachment') && 
                    ($name != 'e-landing-page') &&
                    ($name != 'product') &&
                    ($name != 'post') &&
                    ($name != 'page')
                ){
                    ?>
                    <h4><?php echo esc_html( $labels->name ); ?></h4>
                    <?php 
                        $this->option_checkbox(
                            'esbfe_'.$place.'_display_'.$type.'_cpt_'.esc_attr( $name ).'_single', 
                            esc_html( $labels->name ).__(' Single', 'easy-site-builder-for-elementor'),
                            $post_id
                        ); 
                    ?>
                    <?php 
                        $this->option_checkbox(
                            'esbfe_'.$place.'_display_'.$type.'_cpt_'.esc_attr( $name ).'_archive', 
                            esc_html( $labels->name ).__(' Archive', 'easy-site-builder-for-elementor'),
                            $post_id
                        ); 
                    ?>
                    <?php
                }
            endforeach;
        ?>
        <?php
    }

    /*
        Use case: 
            $type = on/not
            $place = header/footer/template
    */
    function userRole_options_form($post_id, $place){
        ?>
            <h4><?php _e('Basic', 'easy-site-builder-for-elementor') ?></h4>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_all', 
                    __('All', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_logged_in', 
                    __('Logged In', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_logged_out', 
                    __('Logged Out', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>

            <h4><?php _e('Advanced', 'easy-site-builder-for-elementor') ?></h4>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_administrator', 
                    __('Administrator', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_editor', 
                    __('Editor', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_author', 
                    __('Author', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_contributor', 
                    __('Contributor', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php 
                $this->option_checkbox(
                    'esbfe_'.$place.'_user_role_subscriber', 
                    __('Subscriber', 'easy-site-builder-for-elementor'),
                    $post_id
                ); 
            ?>
            <?php if(function_exists('WC')){ ?>
                <?php 
                    $this->option_checkbox(
                        'esbfe_'.$place.'_user_role_shop_manager', 
                        __('Shop manager', 'easy-site-builder-for-elementor'),
                        $post_id
                    ); 
                ?>
            <?php } ?>
        <?php
    }

   

    function option_checkbox($name, $label, $post_id){
        $option = get_option( sanitize_text_field( $name ) );
        $disable_input = '';
        $value = $post_id;
        if( ($option != '') && ($option != $post_id) ){
            $disable_input = 'disabled="disabled"';
            $value = $option;
        }
        ?>
        <div id="<?php echo esc_attr( $name ); ?>" class="esbfe_checkbox_holder">
            <div>
                <input 
                    type="checkbox" 
                    name="<?php echo esc_attr( $name ); ?>" 
                    id="<?php echo esc_attr( $name ); ?>-field" 
                    value="<?php echo esc_attr( $value ); ?>" 
                    <?php echo $disable_input; ?> 
                    <?php checked( $option, sanitize_text_field( $value ) ); ?>>
            </div>
            <div>
                <label for="<?php echo esc_attr( $name ); ?>-field">
                    <strong><?php echo esc_html( $label ); ?></strong>
                    
                    <?php if($disable_input != ''): ?>
                        <em style="display:block;">
                            <?php _e('Already used at ', 'easy-site-builder-for-elementor') ?>
                            <a href="<?php echo admin_url( 'post.php?post='.$option.'&action=edit'); ?>">
                                #<?php echo esc_attr( $option ); ?>
                            </a><br>
                            <span class="esbfe_checkbox_override" 
                                data-option="<?php echo esc_attr( $option ); ?>" 
                                data-field-id="<?php echo esc_attr( $name ); ?>-field" 
                            >
                                <?php _e('Override here?', 'easy-site-builder-for-elementor') ?>
                            </span>
                        </em>
                    <?php endif; ?>
                </label>
            </div>
        </div>
        <?php
    }


    function add_active_class_by_type($post_id, $match){
        $mv_type = get_post_meta( $post_id, 'esbfe_type', true );
        if($mv_type == $match){
            echo 'active';
        }
    }
}

AdminMetabox::instance();