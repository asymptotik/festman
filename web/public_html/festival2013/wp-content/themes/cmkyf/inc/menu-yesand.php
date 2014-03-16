<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<nav id="subaccess" role="navigation">
    <h3 class="assistive-text"><?php _e('Yes And menu', 'twentyeleven'); ?></h3>
    <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
    <div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e('Skip to primary content', 'twentyeleven'); ?>"><?php _e('Skip to primary content', 'twentyeleven'); ?></a></div>
    <div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e('Skip to secondary content', 'twentyeleven'); ?>"><?php _e('Skip to secondary content', 'twentyeleven'); ?></a></div>
    <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>

    <?php $subsection = cmkyf_subsection(); ?>

    <div class="menu">
        <ul>
            <li class="<?php echo ($subsection === 'green-pass' ? 'current_page_item' : 'page_item'); ?>"><a title="Green Pass" href="<?php echo cmkyf_page_url('yes-and/green-pass'); ?>">Green Pass<div><div class="subtitle">the better pass</div></div></a></li>
            <li class="<?php echo ($subsection === 'offsetting' ? 'current_page_item' : 'page_item'); ?>"><a title="Offsetting" href="<?php echo cmkyf_page_url('yes-and/offsetting'); ?>">Offsetting<div><div class="subtitle">better environment</div></div></a></li>
            <li class="<?php echo ($subsection === 'renewable-energy' ? 'current_page_item' : 'page_item'); ?>"><a title="Renewable Energy" href="<?php echo cmkyf_page_url('yes-and/renewable-energy'); ?>">Renewable Energy<div><div class="subtitle">better energy</div></div></a></li>
            <li class="<?php echo ($subsection === 'transportation' ? 'current_page_item' : 'page_item'); ?>"><a title="Transportation" href="<?php echo cmkyf_page_url('yes-and/transportation'); ?>">Transportation<div><div class="subtitle">getting around</div></div></a></li>
            <li class="<?php echo ($subsection === 'zero-waste' ? 'current_page_item' : 'page_item'); ?>"><a title="Zero Waste" href="<?php echo cmkyf_page_url('yes-and/zero-waste'); ?>">Zero Waste<div><div class="subtitle">better process</div></div></a></li>
            <li class="<?php echo ($subsection === 'education' ? 'current_page_item' : 'page_item'); ?>"><a title="Education" href="<?php echo cmkyf_page_url('yes-and/education'); ?>">Education<div><div class="subtitle">better everything</div></div></a></li>
            <li class="<?php echo ($subsection === 'bike-program' ? 'current_page_item' : 'page_item'); ?>"><a title="Bike Program" href="<?php echo cmkyf_page_url('yes-and/bike-program'); ?>">Bike Program<div><div class="subtitle">better transportation</div></div></a></li>
        </ul>   
    </div>
    <?php //wp_nav_menu(array('theme_location' => 'primary'));  ?>
</nav>