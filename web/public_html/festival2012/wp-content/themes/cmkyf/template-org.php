<?php
/**
 * Template Name: Org Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
$slug = basename(get_permalink());

cmkyf_set_section('org');
cmkyf_set_subsection($slug);

get_header();
cmkyf_include('menu-organization.php');
?>
<div id="primary">
    <div id="content" role="main">

        <?php if (have_posts()) : ?>

            <?php twentyeleven_content_nav('nav-above'); ?>

            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php get_template_part('content', get_post_format()); ?>

            <?php endwhile; ?>

            <?php twentyeleven_content_nav('nav-below'); ?>

        <?php else : ?>

            <article id="post-0" class="post no-results not-found">
                <header class="entry-header">
                    <h1 class="entry-title"><?php _e('Nothing Found', 'twentyeleven'); ?></h1>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven'); ?></p>
                    <?php get_search_form(); ?>
                </div><!-- .entry-content -->
            </article><!-- #post-0 -->

        <?php endif; ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>