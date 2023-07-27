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

        $esbfe_type = get_post_meta( $post->ID, 'esbfe_type', true );
        $esbfe_display_on = get_post_meta( $post->ID, 'esbfe_display_on', true );
        $esbfe_display_not = get_post_meta( $post->ID, 'esbfe_display_not', true );
        $esbfe_user_roles = get_post_meta( $post->ID, 'esbfe_user_roles', true );
        echo "<pre>";
        echo 'display_on';
        print_r($esbfe_display_on);
        echo 'display_not';
        print_r($esbfe_display_not);
        echo 'user_roles';
        print_r($esbfe_user_roles);
        echo "</pre>";

        
        ?>
        <style>
            .esbfe_meta_table{
                border:0 !important;
            }
            .esbfe_meta_table td{
                padding-left: 12px;
                padding-right: 12px;
                border-bottom: 1px solid #f0f0f0;
                zoom: 1;
                vertical-align: middle;
            }
            .esbfe_meta_table_label{
                border-right: 1px solid #E1E1E1;
            }
            .esbfe_meta_table_field{
                padding: 15px 10px;
                position: relative;
                width: 55%;
            }
            .esbfe_meta_table select{
                width: 80%;
                margin-bottom:10px;
            }
            .esbfe_display_on_form_section{
                display: none;
            }
            .esbfe_display_not_form_section{
                display: none;
            }
            .esbfe_user_roles_form_section{
                display: none;
            }
            <?php if(($esbfe_type == 'header') || ($esbfe_type == 'footer') || ($esbfe_type == 'theme_template')): ?>
                .esbfe_display_on_form_section,
                .esbfe_display_not_form_section,
                .esbfe_user_roles_form_section{
                    display: table-row;
                }
            <?php endif; ?>
        </style>
        <table class="widefat esbfe_meta_table">
            <tbody>
                <tr>
                    <td class="esbfe_meta_table_label">
                        <h3 style="margin:0;"><?php _e('Type of Template', 'easy-site-builder-for-elementor') ?></h3>
                    </td>
                    <td class="esbfe_meta_table_field">
                        <?php $this->mb_field_typeOfTemplate($post); ?>
                    </td>
                </tr>
                <tr class="esbfe_display_on_form_section">
                    <td class="esbfe_meta_table_label">
                        <h3 style="margin:0;"><?php _e('Display On', 'easy-site-builder-for-elementor') ?></h3>
                        <p class="description"><?php _e('Add locations for where this template should appear.', 'easy-site-builder-for-elementor') ?></p>
                    </td>
                    <td class="esbfe_meta_table_field">
                        <?php $this->mb_repeater_form_field_display($post, 'display_on', __('Add Display Rule', 'easy-site-builder-for-elementor')); ?>
                    </td>
                </tr>
                <tr class="esbfe_display_not_form_section">
                    <td class="esbfe_meta_table_label">
                        <h3 style="margin:0;"><?php _e('Do Not Display', 'easy-site-builder-for-elementor') ?></h3>
                        <p class="description"><?php _e('Add locations for where this template should not appear.', 'easy-site-builder-for-elementor') ?></p>
                    </td>
                    <td class="esbfe_meta_table_field">
                        <?php $this->mb_repeater_form_field_display($post, 'display_not', __('Add Exclusion Rule', 'easy-site-builder-for-elementor')); ?>
                    </td>
                </tr>
                <tr class="esbfe_user_roles_form_section">
                    <td class="esbfe_meta_table_label">
                        <h3 style="margin:0;"><?php _e('User Roles', 'easy-site-builder-for-elementor') ?></h3>
                        <p class="description"><?php _e('Display custom template based on user role.', 'easy-site-builder-for-elementor') ?></p>
                    </td>
                    <td class="esbfe_meta_table_field">
                        <?php $this->mb_field_UserRoles($post); ?>
                    </td>
                </tr>   
            </tbody>
        </table>
        
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $(document).on('click', '.esbfe_repeater_addNew', function() {
                    let parent = $(this).parent();
                    let block_items = parent.find(".esbfe_repeater_items");
                    let item_count = parseFloat(block_items.find(".esbfe_repeater_item").length);
                    let new_item = parent.find(".esbfe_repeater_item_hiden");
                    let new_item_html = new_item.html().replace(/rand_no/g, item_count).replace(/hiden_name/g, 'name');
                    block_items.append('<div class="esbfe_repeater_item">' + new_item_html + '</div>');  
                });

                $(document).on('click', '.esbfe_repeater_remove', function() {
                    let parent = $(this).parent();
                    parent.remove();
                });

                $("#esbfe_type").change(function(){
                    let value =  $("#esbfe_type option:selected");
                    //alert(value.val());
                    if((value.val() === 'header') || (value.val() === 'footer') || ((value.val() === 'theme_template')) ){
                       $('.esbfe_display_on_form_section').css('display', 'table-row');
                       $('.esbfe_display_not_form_section').css('display', 'table-row');
                       $('.esbfe_user_roles_form_section').css('display', 'table-row');
                    }else{
                       $('.esbfe_display_on_form_section').css('display', 'none');
                       $('.esbfe_display_not_form_section').css('display', 'none');
                       $('.esbfe_user_roles_form_section').css('display', 'none');
                    }
               });

            });
        </script>
        <?php
    }

    function mb_repeater_form_field_display($post, $type, $addNewButtonText){
        $field_id  = 'esbfe_'.$type;
        $values = get_post_meta( $post->ID, 'esbfe_'.$type, true );
        $page_templates = wp_get_theme()->get_page_templates();
        ?>
        <div class="esbfe_repeater_block">
            <div class="esbfe_repeater_items">
                <?php if( $values ){ 
                    foreach( $values as $item_key => $item_value ){ ?>
                <div class="esbfe_repeater_item">
                    <select name="<?php echo esc_attr($field_id); ?>[<?php echo $item_key; ?>]" id="<?php echo esc_attr($field_id); ?>">
                        <?php $this->mb_field_helper_display_options($page_templates, $item_value); ?>
                    </select>                    
                    <button class="esbfe_repeater_remove button" type="button">Remove</button>
                </div>
                <?php } } ?>
            </div>

            <div class="esbfe_repeater_item_hiden" style="display:none;">
                <select hiden_name="<?php echo esc_attr($field_id); ?>[rand_no]" id="<?php echo esc_attr($field_id); ?>">
                    <?php $this->mb_field_helper_display_options($page_templates, $item_value); ?>
                </select>
                <button class="esbfe_repeater_remove button" type="button">Remove</button>
            </div>

            <button class="esbfe_repeater_addNew button" type="button"><?php echo esc_attr($addNewButtonText); ?></button>
        </div>
        <?php
    }

    function mb_field_typeOfTemplate($post){
        $field_id  = 'esbfe_type';
        $value = get_post_meta( $post->ID, 'esbfe_type', true );

        ?>
        <select name="esbfe_type" id="esbfe_type">
            <option value="blank" <?php echo selected( $value, 'blank' ); ?>>Select an Option</option>
            <option value="header" <?php echo selected( $value, 'header' ); ?>>Header</option>
            <option value="footer" <?php echo selected( $value, 'footer' ); ?>>Footer</option>
            <option value="section" <?php echo selected( $value, 'section' ); ?>>Section</option>
            <option value="theme_template" <?php echo selected( $value, 'theme_template' ); ?>>Theme's Template</option>
        </select>
        <?php
    }

    function mb_field_helper_display_options($page_templates, $item_value){
        ?>
        <option value="">Select</option>
        <optgroup label="Basic">
            <option value="basic-global" <?php echo selected( $item_value, 'basic-global' ); ?>>Entire Website</option>
            <option value="basic-singulars" <?php echo selected( $item_value, 'basic-singulars' ); ?>>All Singulars</option>
            <option value="basic-archives" <?php echo selected( $item_value, 'basic-archives' ); ?>>All Archives</option>
        </optgroup>
        <optgroup label="Special Pages">
            <option value="special-page" <?php echo selected( $item_value, 'special-page' ); ?>>All Pages</option>
            <option value="special-404" <?php echo selected( $item_value, 'special-404' ); ?>>404 Page</option>
            <option value="special-search" <?php echo selected( $item_value, 'special-search' ); ?>>Search Page</option>
            <option value="special-blog" <?php echo selected( $item_value, 'special-blog' ); ?>>Blog / Posts Page</option>
            <option value="special-front" <?php echo selected( $item_value, 'special-front' ); ?>>Front Page</option>
            <option value="special-date" <?php echo selected( $item_value, 'special-date' ); ?>>Date Archive</option>
            <option value="special-author" <?php echo selected( $item_value, 'special-author' ); ?>>Author Archive</option>
            <?php if(function_exists('WC')){ ?>
                <option value="special-woo-shop" <?php echo selected( $item_value, 'special-woo-shop' ); ?>>WooCommerce Shop Page</option>
            <?php } ?>
        </optgroup>
        <?php if(is_array( $page_templates)){ ?>
            <optgroup label="Page Templates">
                <?php foreach( $page_templates as $page_template_file => $page_template_name ){ ?>
                    <option value="<?php echo $page_template_file; ?>" <?php echo selected( $item_value, $page_template_file ); ?>>
                        <?php echo $page_template_name; ?>
                    </option>
                <?php } ?>
            </optgroup>
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
                    ($name != 'page')
                ){
                    ?>
                    <optgroup label="<?php echo esc_html( $labels->name ); ?>">
                        <option value="<?php echo esc_attr( $name ); ?>|all">All <?php echo esc_html( $labels->name ); ?></option>
                        <option value="<?php echo esc_attr( $name ); ?>|archive">All <?php echo esc_html( $labels->name ); ?> archive</option>
                        <?php
                            $taxonomy_objects = get_object_taxonomies( $name, 'objects' );
                            //print_r( $taxonomy_objects);
                            foreach ( $taxonomy_objects as $taxonomy_object ){
                                if($taxonomy_object->show_ui ){
                                    $tax_name = $taxonomy_object->name;
                                    $tax_label = $taxonomy_object->label;
                                    ?>
                                        <option value="<?php echo esc_attr( $name ); ?>|taxarchive|<?php echo esc_attr( $tax_name ); ?>">
                                            All <?php echo esc_attr( $tax_label ); ?>
                                        </option>
                                    <?php
                                }
                            }
                        ?>
                    </optgroup>
                    <?php
                }
            endforeach;
        ?>
        <?php
    }

    function mb_field_UserRoles($post){
        $field_id  = 'esbfe_user_roles';
        $values = get_post_meta( $post->ID, 'esbfe_user_roles', true );
        ?>
        <div class="esbfe_repeater_block">
            <div class="esbfe_repeater_items">
                <?php if( $values ){ 
                    foreach( $values as $item_key => $item_value ){ ?>
                <div class="esbfe_repeater_item">
                    <select name="<?php echo esc_attr($field_id); ?>[<?php echo $item_key; ?>]" id="<?php echo esc_attr($field_id); ?>">
                        <?php $this->mb_field_UserRoles_helper_select_options($item_value); ?>
                    </select>                    
                    <button class="esbfe_repeater_remove button" type="button">Remove</button>
                </div>
                <?php } } ?>
            </div>

            <div class="esbfe_repeater_item_hiden" style="display:none;">
                <select hiden_name="<?php echo esc_attr($field_id); ?>[rand_no]" id="<?php echo esc_attr($field_id); ?>">
                    <?php $this->mb_field_UserRoles_helper_select_options($item_value); ?>
                </select>
                <button class="esbfe_repeater_remove button" type="button">Remove</button>
            </div>

            <button class="esbfe_repeater_addNew button" type="button">Add another</button>
        </div>
        <?php
        
    }

    function mb_field_UserRoles_helper_select_options($item_value){
        ?>
            <option value="all" <?php echo selected( $item_value, 'blank' ); ?>>All</option>
            <optgroup label="Basic">
                <option value="logged-in" <?php echo selected( $item_value, 'logged-in' ); ?>>Logged In</option>
                <option value="logged-out" <?php echo selected( $item_value, 'logged-out' ); ?>>Logged Out</option>
            </optgroup>
            <optgroup label="Advanced">
                <option value="administrator" <?php echo selected( $item_value, 'administrator' ); ?>>Administrator</option>
                <option value="editor" <?php echo selected( $item_value, 'editor' ); ?>>Editor</option>
                <option value="author" <?php echo selected( $item_value, 'author' ); ?>>Author</option>
                <option value="contributor" <?php echo selected( $item_value, 'contributor' ); ?>>Contributor</option>
                <option value="subscriber" <?php echo selected( $item_value, 'subscriber' ); ?>>Subscriber</option>
                <?php if(function_exists('WC')){ ?>
                    <option value="customer" <?php echo selected( $item_value, 'customer' ); ?>>Customer</option>
                    <option value="shop_manager" <?php echo selected( $item_value, 'shop_manager' ); ?>>Shop manager</option>
                <?php } ?>
            </optgroup>
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
        $page_templates = wp_get_theme()->get_page_templates();
        $all_post_types = get_post_types( array(
            'public' => true,
        ), 'objects' );
        

        /*  Save Meta
        ================================*/  
        //Save Type Meta
        if ( isset($_POST['esbfe_type'])){
            update_post_meta( $post_id, 'esbfe_type', $_POST['esbfe_type'] );
        } else {
            update_post_meta( $post_id, 'esbfe_type', '' );
        }

        //Save Display One
        if ( isset($_POST['esbfe_display_on'])){
            update_post_meta( $post_id, 'esbfe_display_on', $_POST['esbfe_display_on'] );
        } else {
            update_post_meta( $post_id, 'esbfe_display_on', '' );
        }

        //Save Display Not
        if ( isset($_POST['esbfe_display_not'])){
            update_post_meta( $post_id, 'esbfe_display_not', $_POST['esbfe_display_not'] );
        } else {
            update_post_meta( $post_id, 'esbfe_display_not', '' );
        }

        //Save User Roles
        if ( isset($_POST['esbfe_user_roles'])){
            update_post_meta( $post_id, 'esbfe_user_roles', $_POST['esbfe_user_roles'] );
        } else {
            update_post_meta( $post_id, 'esbfe_user_roles', '' );
        }
    }

    static public function get_post_ids_of_type($type){
        $ids = NULL;
        $args = array(
            'post_type' => self::POST_NAME,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_key' => 'esbfe_type',
            'meta_value' => esc_attr($type),
        );
        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $ids[] = get_the_ID();
            }
        }
        wp_reset_postdata();

        return $ids;
    }
}

AdminMetabox::instance();