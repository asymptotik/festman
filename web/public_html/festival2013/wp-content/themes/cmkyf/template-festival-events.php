<?php
/**
 * Template Name: Festival Events Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
cmkyf_set_section('festival');
cmkyf_set_subsection('events');
get_header();

wp_enqueue_script('cmkyf-page-management');
wp_enqueue_style('jquery-fancybox-style');
wp_enqueue_style('jquery-fancybox-buttons-style');
wp_enqueue_style('jquery-fancybox-thumbs-style');

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

require_once get_template_directory() . '/controls/EventListControl.php';

cmkyf_include('menu-festival.php');

$events = Event::getAllEvents();
$listControl = new EventListControl($events);
?>
<div id="primary">
    
    <?php echo $listControl->render(); ?>
    
</div><!-- #primary -->

<?php get_footer(); ?>