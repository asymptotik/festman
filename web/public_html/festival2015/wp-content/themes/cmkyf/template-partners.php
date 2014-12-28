<?php
/**
 * Template Name: Partners Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
cmkyf_set_section('partners');
get_header();

wp_enqueue_script('masonry');
wp_enqueue_script('cmkyf-partners');

?>
<div id="primary">
    

        <?php if (have_posts()) : ?>

            <?php twentyeleven_content_nav('nav-above'); ?>

            <?php
            while (have_posts()) : the_post(); 
                get_template_part('content', 'page'); ?>

                <div id="partners" class="partner-area" role="main">
                    
                <?php
                $innerQuery = new WP_Query();
                $theId = get_the_ID();
                $innerArgs = array(
                    'post_type' => 'page',
                    'post_parent' => $theId,
                    'order_by' => 'title',
                    'order' => 'ASC',
                    'posts_per_page' => -1,
                    );

                    $innerQuery->query($innerArgs);
                    
                    while($innerQuery->have_posts())
                    {
                        $innerQuery->the_post();
                        get_template_part('content', 'partner');
                    }

                    wp_reset_query(); 

                ?>
                
                </div><!-- #partners -->
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

</div><!-- #primary -->

<?php get_footer(); ?>