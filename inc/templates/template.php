<?php get_header(); ?>
<main>
	<?php while ( have_posts() ) : the_post(); ?>
        <?php do_action('easy_site_builder_template_load') ?>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>