<?php
/**
 * Template to display site footer
 */

if($footer_tmpl_id != null){
    // Output Elementor builder content without CSS.
    echo Elementor\Plugin::$instance->frontend->get_builder_content($footer_tmpl_id, false);
}
?>
</div>
<?php
wp_footer();
?>

</body>
</html>
