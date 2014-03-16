<?php
/**
 * Template Name: Festival Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
cmkyf_set_section('festival');
cmkyf_set_subsection('overview');
get_header();
wp_enqueue_script('cmkyf-festival');
?>

<div class="spotlight-wrapper">
    <nav class="spotlight-nav">
        <div class="spot-0" rel=".image-0"></div>
        <div class="spot-1" rel=".image-1"></div>
        <div class="spot-2" rel=".image-2"></div>
        <div class="spot-3" rel=".image-3"></div>
        <div class="spot-4" rel=".image-4"></div>
        <div class="spot-5" rel=".image-5"></div>
        <div class="spot-6" rel=".image-6"></div>
        <div class="spot-7" rel=".image-7"></div>
    </nav>

    
    <div id="spot-0" class="spotlight image-0" ><img src="<?php echo cmkyf_image_url('headers-fest/BlackBox.jpg'); ?>"/></div>
    <div id="spot-1" class="spotlight image-1" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/MarkMcguire.jpg'); ?>"/></div>
    <div id="spot-2" class="spotlight image-2" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/Parade.jpg'); ?>"/></div>
    <div id="spot-3" class="spotlight image-3" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/Pulshar.jpg'); ?>"/></div>
    <div id="spot-4" class="spotlight image-4" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/WilliamBasinkski.jpg'); ?>"/></div>
    <div id="spot-5" class="spotlight image-5" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/KidsPatch.jpg'); ?>"/></div>
    <div id="spot-6" class="spotlight image-6" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/XavierVanWersch.jpg'); ?>"/></div>
    <div id="spot-7" class="spotlight image-7" style="display:none;"><img src="<?php echo cmkyf_image_url('headers-fest/YesAnd.jpg'); ?>"/></div>

</div>

<?php
cmkyf_include('menu-festival.php');
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