<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title><?php
            /*
            * Print the <title> tag based on what is being viewed.
            */
            global $page, $paged;

            wp_title('|', true, 'right');

            // Add the blog name.
            bloginfo('name');

            // Add the blog description for the home/front page.
            $site_description = get_bloginfo('description', 'display');
            if ($site_description && ( is_home() || is_front_page() ))
                echo " | $site_description";

            // Add a page number if necessary:
            if ($paged >= 2 || $page >= 2)
                echo ' | ' . sprintf(__('Page %s', 'twentyeleven'), max($paged, $page));
            ?>
        </title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="icon" type="image/gif" href="<?php echo get_template_directory_uri() . "/images/favicon.gif"?>"/></head>
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        <?php
        /* We add some JavaScript to pages with the comment form
         * to support sites with threaded comments (when in use).
         */
        if (is_singular() && get_option('thread_comments'))
            wp_enqueue_script('comment-reply');

        /* Always have wp_head() just before the closing </head>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to add elements to <head> such
         * as styles, scripts, and meta tags.
         */
        wp_head();
        ?>
        
        <script type="text/javascript">
            var cmkyf_theme_url="<?php echo get_template_directory_uri() ?>";
        </script>
    </head>

    <body <?php body_class(); ?>>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=428242693869462";
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div id="page" class="hfeed">
            <header id="branding" role="banner">
                <hgroup>
                    <h1 id="site-title"><span><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></span></h1>
                    <h2 id="site-description"><?php bloginfo('description'); ?></h2>
                </hgroup>
                <div id="header-tickets">
                    <a title="Tickets" href="<?php echo cmkyf_page_url('festival/tickets'); ?>"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>"/></a>
                </div>
                <div id="header_nav">
                    <nav id="social" role="navigation">
                        <div class="menu">
                            <ul>
                               
                                <li class="page_item"><a id="menu_item_twitter" title="Twitter" target="_blank" href="http://twitter.com/communikey"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>"/></a></li>
                                <li class="page_item"><a id="menu_item_facebook" title="Facebook" target="_blank" href="http://www.facebook.com/communikey"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>"/></a></li>
                                <li class="page_item"><a id="menu_item_vimeo" title="Vimeo" target="_blank" href="http://vimeo.com/user936197"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>"/></a></li>
                                <li class="page_item"><a id="menu_item_lastfm" title="Last FM" target="_blank" href="http://www.last.fm/user/communikey"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>"/></a></li>
                                <li class="page_item"><a id="menu_item_mixcloud" title="Mix Cloud" target="_blank" href="http://www.mixcloud.com/cmky"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>"/></a></li>
                            </ul>   
                        </div>

                        <?php cmkyf_email_signup_e(); ?>
        
                    </nav>

                    <nav id="access" role="navigation">
                        <h3 class="assistive-text"><?php _e('Main menu', 'twentyeleven'); ?></h3>
                        <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
                        <div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e('Skip to primary content', 'twentyeleven'); ?>"><?php _e('Skip to primary content', 'twentyeleven'); ?></a></div>
                        <div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e('Skip to secondary content', 'twentyeleven'); ?>"><?php _e('Skip to secondary content', 'twentyeleven'); ?></a></div>
                        <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>

                        <?php $section = cmkyf_section(); ?>
                        
                        <div class="menu">
                            <ul>
                                <li class="<?php echo ($section === 'org' ? 'current_page_item' : 'page_item'); ?>"><a title="CMKY" href="<?php echo cmkyf_page_url('cmky/organization'); ?>">CMKY<div class="subtitle">organization</div></a></li>
                                <li class="<?php echo ($section === 'festival' ? 'current_page_item' : 'page_item'); ?>"><a title="Festival" href="<?php echo cmkyf_page_url('festival'); ?>">FESTIVAL<div class="subtitle">interdisciplinary arts</div></a></li>
                                <!-- li class="<?php echo ($section === 'events' ? 'current_page_item' : 'page_item'); ?>"><a title="Events" href="<?php echo cmkyf_page_url('events'); ?>">EVENTS<div class="subtitle">what's happening</div></a></li -->
                                <li class="<?php echo ($section === 'yesand' ? 'current_page_item' : 'page_item'); ?>"><a title="Yes And" href="<?php echo cmkyf_page_url('yes-and/overview'); ?>">YES AND<div class="subtitle">designing our future</div></a></li>
                                <li class="<?php echo ($section === 'connect' ? 'current_page_item' : 'page_item'); ?>"><a title="Connect" href="<?php echo cmkyf_page_url('connect'); ?>">CONNECT<div class="subtitle">keep in contact</div></a></li>
                            </ul>   
                        </div>
                        <?php //wp_nav_menu(array('theme_location' => 'primary')); ?>
                    </nav><!-- #access -->
                </div>
                
                <!--
                <?php
                // Check to see if the header image has been removed
                $header_image = get_header_image();
                if (!empty($header_image)) :
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php
                        // The header image
                        // Check if this is a post or page, if it has a thumbnail, and if it's a big one
                        if (is_singular() &&
                                has_post_thumbnail($post->ID) &&
                                ( /* $src, $width, $height */ $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array(HEADER_IMAGE_WIDTH, HEADER_IMAGE_WIDTH)) ) &&
                                $image[1] >= HEADER_IMAGE_WIDTH) :
                            // Houston, we have a new header image!
                            echo get_the_post_thumbnail($post->ID, 'post-thumbnail');
                        else :
                            ?>
                            <img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
                        <?php endif; // end check for featured image or standard header ?>
                    </a>
                <?php endif; // end check for removed header image ?>

                  
                <?php
                // Has the text been hidden?
                if ('blank' == get_header_textcolor()) :
                    ?>
                    <div class="only-search<?php if (!empty($header_image)) : ?> with-image<?php endif; ?>">
                        <?php get_search_form(); ?>
                    </div>
                    <?php
                else :
                    ?>
                    <?php get_search_form(); ?>
                <?php endif; ?>
                        -->

                
            </header><!-- #branding -->


            <div id="main">