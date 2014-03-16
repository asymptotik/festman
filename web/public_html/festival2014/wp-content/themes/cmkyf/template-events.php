<?php
/**
 * Template Name: Events Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
cmkyf_set_section('events');
get_header();
?>
<div id="primary">
    <div id="highlights" class="highlight-area" role="main">

        <?php if (have_posts()) : ?>

            <?php twentyeleven_content_nav('nav-above'); ?>

            <?php /* Start the Loop */ ?>
            <?php
            while (have_posts()) : the_post();


                $innerQuery = new WP_Query();
                $theId = get_the_ID();
                $innerArgs = array(
                    'post_type' => 'page',
                    'post_parent' => $theId,
                    'order_by' => 'title',
                    'order' => 'ASC'
                    );

                    $innerQuery->query($innerArgs);
                    
                    while($innerQuery->have_posts())
                    {
                        $innerQuery->the_post();
                        get_template_part('content', 'highlight');
                    }

                    wp_reset_query(); 

                ?>
                

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

<?php get_footer(); ?>