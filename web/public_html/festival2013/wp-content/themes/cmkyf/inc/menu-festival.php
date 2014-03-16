<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<nav id="subaccess" role="navigation">
    <h3 class="assistive-text"><?php _e('Festival menu', 'twentyeleven'); ?></h3>
    <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
    <div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e('Skip to primary content', 'twentyeleven'); ?>"><?php _e('Skip to primary content', 'twentyeleven'); ?></a></div>
    <div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e('Skip to secondary content', 'twentyeleven'); ?>"><?php _e('Skip to secondary content', 'twentyeleven'); ?></a></div>
    <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>

    <?php $subsection = cmkyf_subsection(); ?>

    <div class="menu">
        <ul>
            <li class="<?php echo ($subsection === 'tickets' ? 'current_page_item' : 'page_item'); ?>"><a title="Tickets" href="<?php echo cmkyf_page_url('tickets'); ?>">Tickets<div><div class="subtitle">how to go</div></div></a></li>
            <li class="<?php echo ($subsection === 'acts' ? 'current_page_item' : 'page_item'); ?>"><a title="Acts" href="<?php echo cmkyf_page_url('festival/acts'); ?>">Acts<div><div class="subtitle">who's who</div></div></a></li>
            <!-- li class="<?php echo ($subsection === 'workshops' ? 'current_page_item' : 'page_item'); ?>"><a title="Workshops" href="<?php echo cmkyf_page_url('festival/workshops'); ?>">Workshops<div><div class="subtitle">hands on</div></div></a></li>
            <li class="<?php echo ($subsection === 'installations' ? 'current_page_item' : 'page_item'); ?>"><a title="Installations" href="<?php echo cmkyf_page_url('festival/installations'); ?>">Installations<div><div class="subtitle">environments</div></div></a></li -->
            <li class="<?php echo ($subsection === 'films' ? 'current_page_item' : 'page_item'); ?>"><a title="Films" href="<?php echo cmkyf_page_url('festival/films'); ?>">Films<div><div class="subtitle">the screen</div></div></a></li>
            <li class="<?php echo ($subsection === 'events' ? 'current_page_item' : 'page_item'); ?>"><a title="Events" href="<?php echo cmkyf_page_url('festival/events'); ?>">Events<div><div class="subtitle">who what where</div></div></a></li>
            <li class="<?php echo ($subsection === 'locations' ? 'current_page_item' : 'page_item'); ?>"><a title="Locations" href="<?php echo cmkyf_page_url('festival/locations'); ?>">Locations<div><div class="subtitle">where is that</div></div></a></li>
            <li class="<?php echo ($subsection === 'schedule' ? 'current_page_item' : 'page_item'); ?>"><a title="Schedule" href="<?php echo cmkyf_page_url('festival/schedule'); ?>">Schedule<div><div class="subtitle">keeping up with it</div></div></a></li>
            <li class="<?php echo ($subsection === 'tourism' ? 'current_page_item' : 'page_item'); ?>"><a title="Tourism" href="<?php echo cmkyf_page_url('tourism'); ?>">Tourism<div><div class="subtitle">while you're there</div></div></a></li>
        </ul>   
    </div>
    <?php //wp_nav_menu(array('theme_location' => 'primary'));  ?>
</nav>