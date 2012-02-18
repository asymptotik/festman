<?php

/*
  Plugin Name: cmkyf
  Plugin URI: http://www.communikey.us/cmkyf
  Description: A festival pluggin for Communikey Festival of Electronic Arts
  Version: 1.0
  Author: Rick Boykin
  Author URI: http://www.communikey.us
 */
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require dirname(__FILE__) . '/widgets/CmkyfRssWidget.php';
require dirname(__FILE__) . '/widgets/CmkyfMailingListWidget.php';
require dirname(__FILE__) . '/festman/admin/install/database.php';

// add_action('admin_menu', 'my_plugin_menu');
register_activation_hook(__FILE__, 'festman_install');

/**
 * Register widgets.
 *
 * Calls 'widgets_init' action after the Hello World widget has been registered.
 */
$festman_db_version = "1.0";

add_action('widgets_init', 'register_cmkyf_widgets');

function register_cmkyf_widgets()
{
    register_widget('Cmkyf_Widget_RSS');
    register_widget('Cmkyf_Widget_Mailing_List');
}
add_action('admin_init', 'fm_admin_init');

function fm_admin_init()
{
    wp_enqueue_script('post');
    wp_enqueue_script('editor');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('word-count');
    wp_enqueue_script('jquery');
    
    require_once(dirname(__FILE__) . '/festman/objects/Act.php');
    require_once(dirname(__FILE__) . '/festman/objects/Event.php');
    require_once(dirname(__FILE__) . '/festman/objects/Film.php');
    require_once(dirname(__FILE__) . '/festman/objects/Installation.php');
    require_once(dirname(__FILE__) . '/festman/objects/Klass.php');
    require_once(dirname(__FILE__) . '/festman/objects/Location.php');
    require_once(dirname(__FILE__) . '/festman/objects/Panel.php');
    require_once(dirname(__FILE__) . '/festman/objects/Workshop.php');

    if (!session_id())
        session_start();
}
add_action('admin_head', 'fm_admin_head');

function fm_admin_head()
{
    wp_tiny_mce();
}
add_action('admin_menu', 'cmkyf_plugin_menu');

function cmkyf_plugin_menu()
{
    add_menu_page('Festman Config', 'Festman', 'publish_posts', 'fm-act-page', 'fm_blank', '', 40);
    $menu_page = add_submenu_page('fm-act-page', 'Festival Acts', 'Acts', 'publish_posts', 'fm-act-page', 'fm_acts_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Classes', 'Classes', 'publish_posts', 'fm-klass-page', 'fm_classes_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Films', 'Films', 'publish_posts', 'fm-film-page', 'fm_films_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Panels', 'Panels', 'publish_posts', 'fm-panel-page', 'fm_panels_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Workshops', 'Workshops', 'publish_posts', 'fm-workshop-page', 'fm_workshops_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Installations', 'Installations', 'publish_posts', 'fm-installation-page', 'fm_installations_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Locations', 'Locations', 'publish_posts', 'fm-location-page', 'fm_locations_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Festival Events', 'Events', 'publish_posts', 'fm-event-page', 'fm_events_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
    $menu_page = add_submenu_page('fm-act-page', 'Collateral', 'Collateral', 'publish_posts', 'fm-collateral-page', 'fm_collateral_admin_func');
    add_action("admin_print_scripts-$menu_page", 'fm_admin_page_head');
}

function fm_admin_page_head()
{
    $css_file = plugin_dir_url(__FILE__) . "festman/css/admin.css";
    echo "<link rel='stylesheet' id='lc-custom_-css'  href='" . $css_file . "' type='text/css' media='all' />\n";

    $fm_validation = plugin_dir_url(__FILE__) . "festman/script/validation.js";
    wp_enqueue_script('fm_validation', $fm_validation);

    $fm_utils = plugin_dir_url(__FILE__) . "festman/script/utils.js";
    wp_enqueue_script('fm_utils', $fm_utils);
    
    $fm_changes = plugin_dir_url(__FILE__) . "festman/script/changes.js";
    wp_enqueue_script('fm_changes', $fm_changes);
}

// function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) 

function fm_blank()
{
    
}

function fm_program_item_handler($klass)
{
    $_SESSION["current_program_item_class"] = $klass;
    require_once(dirname(__FILE__) . '/festman/admin/FmProgramItemEventHandler.php');
    $handler = new FmProgramItemEventHandler();
    $results = $handler->getActionResults();
    //echo "Action Results: $results";

    if (isset($results))
    {
        include($results);
    }
    else
    {
        require_once('festman/admin/program_item_selector.php');
    }
}

// Acts
function fm_acts_admin_func()
{
    fm_program_item_handler("Act");
}

// Classes
function fm_classes_admin_func()
{
    fm_program_item_handler("Klass");
}

function fm_films_admin_func()
{
    fm_program_item_handler("Film");
}

function fm_panels_admin_func()
{
    fm_program_item_handler("Panel");
}

function fm_workshops_admin_func()
{
    fm_program_item_handler("Workshop");
}

function fm_installations_admin_func()
{
    fm_program_item_handler("Installation");
}

function fm_locations_admin_func()
{
    require_once(dirname(__FILE__) . '/festman/admin/FmLocationEventHandler.php');
    $handler = new FmLocationEventHandler();
    $results = $handler->getActionResults();
    //echo "Action Results: $results";

    if (isset($results))
    {
        include($results);
    }
    else
    {
        require_once('festman/admin/location_selector.php');
    }
}

function fm_events_admin_func()
{
    require_once(dirname(__FILE__) . '/festman/admin/FmEventEventHandler.php');
    $handler = new FmEventEventHandler();
    $results = $handler->getActionResults();
    //echo "Action Results: $results";

    if (isset($results))
    {
        include($results);
    }
    else
    {
        require_once('festman/admin/event_selector.php');
    }
}

function fm_collateral_admin_func()
{
    require_once(dirname(__FILE__) . '/festman/admin/FmCollateralCollectionEventHandler.php');

    $handler = new FmCollateralCollectionEventHandler();
    $results = $handler->getActionResults();
    //echo "Action Results: $results";

    if (isset($results))
    {
        include($results);
    }
    else
    {
        require_once('festman/admin/collateral_collection_editor.php');
    }
}

function festman_install()
{
    global $cmky_db_version;

    add_option("festman_db_version", $festman_db_version);
    cmkyf_install_database();
}
?>