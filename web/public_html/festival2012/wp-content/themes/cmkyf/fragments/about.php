<?php
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', false);

/** Loads the WordPress Environment and Template */
require('../../../../wp-blog-header.php');

if (isset($_GET["page_id"]))
{
    $page_id = $_GET["page_id"];
}

$args = array(
    'post_type' => 'page',
    'page_id' => intval($page_id),
    'order_by' => 'title',
    'order' => 'ASC'
);

query_posts($args);
?>

<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

        <div class="post-panel c4-style">
            <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <div class="page-panel-header c-border c-bg corners-top">
                    <table class="panel-content">
                        <tr>
                            <td class="post-title"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></td>
                        </tr>
                    </table>
                </div>

                <div class="page-panel-body c-border corners-bottom">
                    <div class="entry panel-content">
                        <?php the_content('Read the rest of this entry &raquo;'); ?>
                        <div class="clear"></div>
                    </div>
                </div>

            </div>
        </div>

    <?php endwhile; ?>

<?php else : ?>

    <h2 class="center">Not Found</h2>
    <p class="center">Sorry, but you are looking for something that isn't here.</p>
    <?php get_search_form(); ?>

<?php endif;
wp_reset_query(); ?>
