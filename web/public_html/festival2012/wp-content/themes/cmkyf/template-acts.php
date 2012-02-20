<?php
/**
 * Template Name: Acts Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
cmkyf_set_section('events');
cmkyf_set_subsection('acts');
get_header();

require_once CMKYF_PLUGIN_BASE_DIR.'/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Utils.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Location.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Event.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Workshop.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Installation.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Program_ProgramItem.php';

require_once get_template_directory() . '/controls/ItemListControl.php';

cmkyf_festival_menu();

$acts = ProgramItem::getAllTypedProgramItems('Act');
$itemListControl = new ItemListControl($acts);
?>
<div id="primary">
    
    <?php echo $itemListControl->render(); ?>
    
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

<?php get_footer(); ?>