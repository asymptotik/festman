<?php
/**
 * The Sidebar containing the highlights area.
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */

$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>
    <div id="highlights" class="widget-area" role="complementary">
        <?php dynamic_sidebar( 'sidebar-6' );?>
    </div><!-- #secondary .widget-area -->
<?php endif; ?>