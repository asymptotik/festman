<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<nav id="subaccess" role="navigation">
    <h3 class="assistive-text"><?php _e('Organization menu', 'twentyeleven'); ?></h3>
    <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
    <div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e('Skip to primary content', 'twentyeleven'); ?>"><?php _e('Skip to primary content', 'twentyeleven'); ?></a></div>
    <div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e('Skip to secondary content', 'twentyeleven'); ?>"><?php _e('Skip to secondary content', 'twentyeleven'); ?></a></div>
    <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>

    <?php $subsection = cmkyf_subsection(); ?>

    <div class="menu">
        <ul><li class="<?php echo ($subsection === 'organization' ? 'current_page_item' : 'page_item'); ?>"><a title="Organization" href="<?php echo cmkyf_page_url('cmky/organization'); ?>">Organization<div><div class="subtitle">who we are</div></div></a></li>
            <li class="<?php echo ($subsection === 'icas' ? 'current_page_item' : 'page_item'); ?>"><a title="ICAS" href="<?php echo cmkyf_page_url('cmky/icas'); ?>">ICAS<div><div class="subtitle">the bigger picture</div></div></a></li>
            <li class="<?php echo ($subsection === 'partners' ? 'current_page_item' : 'page_item'); ?>"><a title="Partners" href="<?php echo cmkyf_page_url('cmky/partners'); ?>">Partners<div><div class="subtitle">we care</div></div></a></li>
            <li class="<?php echo ($subsection === 'contact' ? 'current_page_item' : 'page_item'); ?>"><a title="Contact" href="<?php echo cmkyf_page_url('cmky/contact'); ?>">Contact<div><div class="subtitle">talk to us</div></div></a></li>
        </ul>   
    </div>
    <?php //wp_nav_menu(array('theme_location' => 'primary'));  ?>
</nav>