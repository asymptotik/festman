<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width))
    $content_width = 584;


if (!defined('CMKYF_PLUGIN_URL'))
    define('CMKYF_PLUGIN_URL', WP_PLUGIN_URL . '/cmkyf');
if (!defined('CMKYF_PLUGIN_DIR'))
    define('CMKYF_PLUGIN_DIR', WP_PLUGIN_DIR . '/cmkyf');
if (!defined('CMKYF_PLUGIN_BASE_URL'))
    define('CMKYF_PLUGIN_BASE_URL', WP_PLUGIN_URL . '/cmkyf/festman');
if (!defined('CMKYF_PLUGIN_BASE_DIR'))
    define('CMKYF_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/cmkyf/festman');

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action('after_setup_theme', 'cmkyf_setup');

if (!function_exists('cmkyf_setup')):

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
     * functions.php file.
     *
     * @uses load_theme_textdomain() For translation/localization support.
     * @uses add_editor_style() To style the visual editor.
     * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
     * @uses register_nav_menus() To add support for navigation menus.
     * @uses add_custom_background() To add support for a custom background.
     * @uses add_custom_image_header() To add support for a custom header.
     * @uses register_default_headers() To register the default custom header images provided with the theme.
     * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
     *
     * @since Twenty Eleven 1.0
     */
    function cmkyf_setup()
    {

        /* Make Twenty Eleven available for translation.
         * Translations can be added to the /languages/ directory.
         * If you're building a theme based on Twenty Eleven, use a find and replace
         * to change 'twentyeleven' to the name of your theme in all the template files.
         */
        load_theme_textdomain('twentyeleven', get_template_directory() . '/languages');

        $locale = get_locale();
        $locale_file = get_template_directory() . "/languages/$locale.php";
        if (is_readable($locale_file))
            require_once( $locale_file );

        // This theme styles the visual editor with editor-style.css to match the theme style.
        add_editor_style();

        // Load up our theme options page and related code.
        require( get_template_directory() . '/inc/theme-options.php' );

        // Grab Twenty Eleven's Ephemera widget.
        require( get_template_directory() . '/inc/widgets.php' );

        // Add default posts and comments RSS feed links to <head>.
        add_theme_support('automatic-feed-links');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menu('primary', __('Primary Menu', 'twentyeleven'));

        // Add support for a variety of post formats
        add_theme_support('post-formats', array('aside', 'link', 'gallery', 'status', 'quote', 'image'));

        // Add support for custom backgrounds
        add_custom_background();

        // This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
        add_theme_support('post-thumbnails');

        // The next four constants set how Twenty Eleven supports custom headers.
        // The default header text color
        define('HEADER_TEXTCOLOR', '000');

        // By leaving empty, we allow for random image rotation.
        define('HEADER_IMAGE', '');

        // The height and width of your custom header.
        // Add a filter to twentyeleven_header_image_width and twentyeleven_header_image_height to change these values.
        define('HEADER_IMAGE_WIDTH', apply_filters('twentyeleven_header_image_width', 1000));
        define('HEADER_IMAGE_HEIGHT', apply_filters('twentyeleven_header_image_height', 360));

        // We'll be using post thumbnails for custom header images on posts and pages.
        // We want them to be the size of the header image that we just defined
        // Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
        set_post_thumbnail_size(HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true);

        // Add Twenty Eleven's custom image sizes
        add_image_size('large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true); // Used for large feature (header) images
        add_image_size('small-feature', 500, 300); // Used for featured posts if a large-feature doesn't exist
        // Turn on random header image rotation by default.
        add_theme_support('custom-header', array('random-default' => true));

        // Add a way for the custom header to be styled in the admin panel that controls
        // custom headers. See twentyeleven_admin_header_style(), below.
        add_custom_image_header('cmkyf_header_style', 'cmkyf_admin_header_style', 'cmkyf_admin_header_image');

        // ... and thus ends the changeable header business.
        // Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
        register_default_headers(array(
            'festival_01' => array(
                'url' => '%s/images/headers/CMKY_2013_SPLASH_PAGE_1.jpg',
                'thumbnail_url' => '%s/images/headers/CMY_NYE_WEB_BANNER_123112_1-240x86.jpg',
                /* translators: header image description */
                'description' => __('Festival 2013 One', 'twentyeleven'),
                'action' => 'http://cmky.org/festival2013/festival/'
            ),
            'festival_02' => array(
                'url' => '%s/images/headers/CMKY_2013_SPLASH_PAGE_2.jpg',
                'thumbnail_url' => '%s/images/headers/CMY_NYE_WEB_BANNER_123112_1-240x86.jpg',
                /* translators: header image description */
                'description' => __('Festival 2013 Two', 'twentyeleven'),
                'action' => 'http://cmky.org/festival2013/festival/'
            ),
            'festival_03' => array(
                'url' => '%s/images/headers/CMKY_2013_SPLASH_PAGE_3.jpg',
                'thumbnail_url' => '%s/images/headers/CMY_NYE_WEB_BANNER_123112_1-240x86.jpg',
                /* translators: header image description */
                'description' => __('Festival 2013 Three', 'twentyeleven'),
                'action' => 'http://cmky.org/festival2013/festival/'
            ),
            'festival_04' => array(
                'url' => '%s/images/headers/CMKY_2013_SPLASH_PAGE_4.jpg',
                'thumbnail_url' => '%s/images/headers/CMY_NYE_WEB_BANNER_123112_1-240x86.jpg',
                /* translators: header image description */
                'description' => __('Festival 2013 Four', 'twentyeleven'),
                'action' => 'http://cmky.org/festival2013/festival/'
            ),
            /*,
            'festival_01' => array(
                'url' => '%s/images/headers/fest-mountains.jpg',
                'thumbnail_url' => '%s/images/headers/fest-mountains-thumb.png',
                // translators: header image description
                'description' => __('Festival 2012 Mountains', 'twentyeleven'),
                'action' => '/festival'
            ),
            'wheel' => array(
                'url' => '%s/images/headers/fest-boat.jpg',
                'thumbnail_url' => '%s/images/headers/fest-boat-thumb.png',
                // translators: header image description 
                'description' => __('Festival 2012 Boat', 'twentyeleven'),
                'action' => '/festival'
            ),
            'shore' => array(
                'url' => '%s/images/headers/fest-beach.jpg',
                'thumbnail_url' => '%s/images/headers/fest-beach-thumb.png',
                // translators: header image description 
                'description' => __('Festival 2012 Beach', 'twentyeleven'),
                'action' => '/festival'
            )*/
        ));
    }
endif; // twentyeleven_setup

if (!function_exists('cmkyf_header_style')) :

    /**
     * Styles the header image and text displayed on the blog
     *
     * @since Twenty Eleven 1.0
     */
    function cmkyf_header_style()
    {

        // If no custom options for text are set, let's bail
        // get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
        if (HEADER_TEXTCOLOR == get_header_textcolor())
            return;
        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">
        <?php
        // Has the text been hidden?
        if ('blank' == get_header_textcolor()) :
            ?>
                #site-title,
                #site-description {
                    position: absolute !important;
                    clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                    clip: rect(1px, 1px, 1px, 1px);
                }
            <?php
        // If the user has set a custom color for the text use that
        else :
            ?>
                #site-title a,
                #site-description {
                    color: #<?php echo get_header_textcolor(); ?> !important;
                }
        <?php endif; ?>
        </style>
        <?php
    }
endif; // twentyeleven_header_style

if (!function_exists('cmkyf_admin_header_style')) :

    /**
     * Styles the header image displayed on the Appearance > Header admin panel.
     *
     * Referenced via add_custom_image_header() in twentyeleven_setup().
     *
     * @since Twenty Eleven 1.0
     */
    function cmkyf_admin_header_style()
    {
        ?>
        <style type="text/css">
            .appearance_page_custom-header #headimg {
                border: none;
            }
            #headimg h1,
            #desc {
                font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
            }
            #headimg h1 {
                margin: 0;
            }
            #headimg h1 a {
                font-size: 32px;
                line-height: 36px;
                text-decoration: none;
            }
            #desc {
                font-size: 14px;
                line-height: 23px;
                padding: 0 0 3em;
            }
            <?php
            // If the user has set a custom color for the text use that
            if (get_header_textcolor() != HEADER_TEXTCOLOR) :
                ?>
                #site-title a,
                #site-description {
                    color: #<?php echo get_header_textcolor(); ?>;
                }
        <?php endif; ?>
            #headimg img {
                max-width: 1000px;
                height: auto;
                width: 100%;
            }
        </style>
        <?php
    }
endif; // twentyeleven_admin_header_style

if (!function_exists('cmkyf_admin_header_image')) :

    /**
     * Custom header image markup displayed on the Appearance > Header admin panel.
     *
     * Referenced via add_custom_image_header() in twentyeleven_setup().
     *
     * @since Twenty Eleven 1.0
     */
    function cmkyf_admin_header_image()
    {
        ?>
        <div id="headimg">
            <?php
            if ('blank' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) || '' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR))
                $style = ' style="display:none;"';
            else
                $style = ' style="color:#' . get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) . ';"';
            ?>
            <h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
            <div id="desc"<?php echo $style; ?>><?php bloginfo('description'); ?></div>
        <?php $header_image = get_header_image();
        if (!empty($header_image)) :
            ?>
                <img src="<?php echo esc_url($header_image); ?>" alt="" />
        <?php endif; ?>
        </div>
    <?php
    }
endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function cmkyf_excerpt_length($length)
{
    return 30;
}
add_filter('excerpt_length', 'cmkyf_excerpt_length');

/**
 * Returns a "Continue Reading" link for excerpts
 */
function cmkyf_continue_reading_link()
{
    return ' <a href="' . esc_url(get_permalink()) . '">' . __('more', 'twentyeleven') . '</a>';
}
/*
 * Strip and trim a string to and excerpt style length
 */

function cmkyf_string_excerpt($str, $len)
{
    $desc = str_replace(array("\n", "\r"), ' ', esc_attr(strip_tags(@html_entity_decode($str, ENT_QUOTES, get_option('blog_charset')))));
    $desc = wp_html_excerpt($desc, $len) . ' [&hellip;]';
    return esc_html($desc);
}
/*
 * Strip and trim a string to and excerpt style length
 */

function cmkyf_string_excerpt_with_more($str, $len, $class_name, $url)
{
    $desc = str_replace(array("\n", "\r"), ' ', esc_attr(strip_tags(@html_entity_decode($str, ENT_QUOTES, get_option('blog_charset')))));
    $desc = wp_html_excerpt($desc, $len);
    return esc_html($desc) . ' <a class="' . $class_name . '" href="' . $url . '">[more]</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and cmkyf_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function cmkyf_auto_excerpt_more($more)
{
    return ' &hellip;' . cmkyf_continue_reading_link();
}
add_filter('excerpt_more', 'cmkyf_auto_excerpt_more');

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function cmkyf_custom_excerpt_more($output)
{

    return $output;
}
add_filter('get_the_excerpt', 'cmkyf_custom_excerpt_more');

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function cmkyf_page_menu_args($args)
{
    $args['show_home'] = true;
    return $args;
}
add_filter('wp_page_menu_args', 'cmkyf_page_menu_args');

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function cmkyf_widgets_init()
{

    register_widget('Twenty_Eleven_Ephemera_Widget');

    register_sidebar(array(
        'name' => __('Main Sidebar', 'twentyeleven'),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Showcase Sidebar', 'twentyeleven'),
        'id' => 'sidebar-2',
        'description' => __('The sidebar for the optional Showcase Template', 'twentyeleven'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Highlight Main Area', 'twentyeleven'),
        'id' => 'sidebar-6',
        'description' => __('An optional widget area for your site highlights', 'twentyeleven'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer Area One', 'twentyeleven'),
        'id' => 'sidebar-3',
        'description' => __('An optional widget area for your site footer', 'twentyeleven'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer Area Two', 'twentyeleven'),
        'id' => 'sidebar-4',
        'description' => __('An optional widget area for your site footer', 'twentyeleven'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer Area Three', 'twentyeleven'),
        'id' => 'sidebar-5',
        'description' => __('An optional widget area for your site footer', 'twentyeleven'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'cmkyf_widgets_init');

if (!function_exists('twentyeleven_content_nav')) :

    /**
     * Display navigation to next/previous pages when applicable
     */
    function twentyeleven_content_nav($nav_id)
    {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) :
            ?>
            <nav id="<?php echo $nav_id; ?>">
                <h3 class="assistive-text"><?php _e('Post navigation', 'twentyeleven'); ?></h3>
                <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven')); ?></div>
                <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven')); ?></div>
            </nav><!-- #nav-above -->
        <?php
        endif;
    }
endif; // twentyeleven_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber()
{
    if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches))
        return false;

    return esc_url_raw($matches[1]);
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class()
{
    $count = 0;

    if (is_active_sidebar('sidebar-3'))
        $count++;

    if (is_active_sidebar('sidebar-4'))
        $count++;

    if (is_active_sidebar('sidebar-5'))
        $count++;

    $class = '';

    switch ($count)
    {
        case '1':
            $class = 'one';
            break;
        case '2':
            $class = 'two';
            break;
        case '3':
            $class = 'three';
            break;
    }

    if ($class)
        echo 'class="' . $class . '"';
}
if (!function_exists('twentyeleven_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own twentyeleven_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since Twenty Eleven 1.0
     */
    function twentyeleven_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
                <li class="post pingback">
                    <p><?php _e('Pingback:', 'twentyeleven'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'twentyeleven'), '<span class="edit-link">', '</span>'); ?></p>
                                <?php
                                break;
                            default :
                                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment">
                        <footer class="comment-meta">
                            <div class="comment-author vcard">
                <?php
                $avatar_size = 68;
                if ('0' != $comment->comment_parent)
                    $avatar_size = 39;

                echo get_avatar($comment, $avatar_size);

                /* translators: 1: comment author, 2: date and time */
                printf(__('%1$s on %2$s <span class="says">said:</span>', 'twentyeleven'), sprintf('<span class="fn">%s</span>', get_comment_author_link()), sprintf('<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),
                                /* translators: 1: date, 2: time */ sprintf(__('%1$s at %2$s', 'twentyeleven'), get_comment_date(), get_comment_time())
                        )
                );
                ?>

                <?php edit_comment_link(__('Edit', 'twentyeleven'), '<span class="edit-link">', '</span>'); ?>
                            </div><!-- .comment-author .vcard -->

                    <?php if ($comment->comment_approved == '0') : ?>
                                <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'twentyeleven'); ?></em>
                                <br />
                    <?php endif; ?>

                        </footer>

                        <div class="comment-content"><?php comment_text(); ?></div>

                        <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply <span>&darr;</span>', 'twentyeleven'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div><!-- .reply -->
                    </article><!-- #comment-## -->

                    <?php
                    break;
            endswitch;
        }
endif; // ends check for twentyeleven_comment()

if (!function_exists('twentyeleven_posted_on')) :

    /**
        * Prints HTML with meta information for the current post-date/time and author.
        * Create your own twentyeleven_posted_on to override in a child theme
        *
        * @since Twenty Eleven 1.0
        */
    function twentyeleven_posted_on()
    {
        printf(__('<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven'), esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'twentyeleven'), get_the_author())), get_the_author()
        );
    }
endif;

/**
    * Adds two classes to the array of body classes.
    * The first is if the site has only had one author with published posts.
    * The second is if a singular post being displayed
    *
    * @since Twenty Eleven 1.0
    */
function twentyeleven_body_classes($classes)
{

    if (function_exists('is_multi_author') && !is_multi_author())
        $classes[] = 'single-author';

    if (is_page_template('template-home.php'))
        $classes[] = 'singular';
    else if (is_singular() && !is_home() && !is_page_template('showcase.php') && !is_page_template('sidebar-page.php') && !is_page_template('template-festival.php') && !is_page_template('template-org.php') && !is_page_template('template-yesand.php'))
        $classes[] = 'singular';

    return $classes;
}
add_filter('body_class', 'twentyeleven_body_classes');

function cmky_register_scripts()
{
    wp_register_script('jquery-watermark', get_template_directory_uri() . '/js/jquery.watermark.min.js', array('jquery'), "1.3", true);
    wp_register_script('cmkyf-header', get_template_directory_uri() . '/js/cmkyf-header.js', array('jquery-watermark'), "1.0", true);
    wp_register_script('jquery-ui-effects', get_template_directory_uri() . '/js/jquery-ui-effects.min.js', array('jquery'), "1.0", true);
    wp_register_script('jquery-spotlight', get_template_directory_uri() . '/js/jquery.spotlight.js', array('jquery-ui-effects'), "1.0", true);
    wp_register_script('jquery-mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel-3.0.6.pack.js', array('jquery'), "3.0.6", true);
    wp_register_script('jquery-fancybox', get_template_directory_uri() . '/js/jquery.fancybox.js', array('jquery'), "2.0.3", true);
    wp_register_script('jquery-fancybox-buttons', get_template_directory_uri() . '/js/jquery.fancybox-buttons.js', array('jquery-fancybox'), "2.0.4", true);
    wp_register_script('jquery-fancybox-thumbs', get_template_directory_uri() . '/js/jquery.fancybox-thumbs.js', array('jquery-fancybox'), "2.0.4", true);
    wp_register_script('jquery-address', get_template_directory_uri() . '/js/jquery.address-1.5.min.js', array('jquery'), "1.5", true);
    wp_register_script('cmkyf-home', get_template_directory_uri() . '/js/cmkyf-home.js', array('jquery-spotlight'), "1.0", true);
    wp_register_script('cmkyf-festival', get_template_directory_uri() . '/js/cmkyf-festival.js', array('jquery-spotlight'), "1.0", true);
    wp_register_script('cmkyf-page-management', get_template_directory_uri() . '/js/cmkyf-page-management.js', array('jquery-address', 'jquery-fancybox', 'jquery-fancybox-buttons', 'jquery-fancybox-thumbs'), "1.0", true);
    wp_register_script('cmkyf-program-item', get_template_directory_uri() . '/js/cmkyf-program-item.js', array('jquery-mousewheel', 'jquery-fancybox', 'jquery-fancybox-buttons', 'jquery-fancybox-thumbs'), "1.0", true);


    wp_register_style('jquery-fancybox-style', get_template_directory_uri() . '/js/jquery.fancybox.css', false, '2.0.4', 'all');
    wp_register_style('jquery-fancybox-buttons-style', get_template_directory_uri() . '/js/jquery.fancybox-buttons.css', false, '2.0.4', 'all');
    wp_register_style('jquery-fancybox-thumbs-style', get_template_directory_uri() . '/js/jquery.fancybox-thumbs.css', false, '2.0.4', 'all');

    wp_enqueue_script('cmkyf-header');
}
add_action('wp_enqueue_scripts', 'cmky_register_scripts');

function cmkyf_image_url($image)
{
    return get_template_directory_uri() . '/images/' . $image;
}

function cmkyf_fragment_url($fragment)
{
    return get_template_directory_uri() . '/fragments/' . $fragment;
}

function cmkyf_page_url($page)
{
    return get_site_url() . '/' . $page;
}

function cmkyf_email_signup_e()
{
    /*
     * Mail Chimp
     */
    echo '<div id="mc_embed_signup">';
    echo '<form action="http://communikey.us6.list-manage.com/subscribe/post?u=b1a1e43077ae717f80d89d6a0&amp;id=23d3d64842" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>';
    echo '  <div id="cmky_mailinglist">' . "\n";
    echo '      <input type="text" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required><input type="button" name="subscribe" id="mc-embedded-subscribe" value="ok" />';
    echo '  </div>' . "\n";
    echo '</form>';
    echo '</div>';
    echo '<!--End mc_embed_signup-->';

    /*
     * icontact
     */
    /*
        echo '<form id="ccsfg" name="ccsfg" method="post" action="' . get_template_directory_uri() . '/ccontact/signup/index.php' . '">';
    echo '  <div id="cmky_mailinglist">' . "\n";
    echo '    <input type="text" name="EmailAddress" value="" id="EmailAddress" /><input type="button" name="signup" id="signup" value="ok" />';
    echo '  </div>' . "\n";
            //   <!-- ########## Contact Lists ########## -->
    echo '    <input type="hidden"  checked="checked"  value="CMKY general" name="Lists[]" id="list_CMKY general" />';
            //<!-- ########## Success / Failure Redirects ########## -->
    echo '    <input type="hidden" name="SuccessURL" value="http://communikey.us/festival2012/connect/email-list-thanks" />';
    echo '    <input type="hidden" name="FailureURL" value="http://communikey.us/festival2012/connect/email-list-error" />';
    echo '</form>';
    */
    
    
    
    /*
     * php list
    echo '<form method=post action="https://app.icontact.com/icp/signup.php" name="icpsignup" id="icpsignup554" accept-charset="UTF-8" >' . "\n";
    echo '  <input type=hidden name=redirect value="http://www.communikey.us/festival2012/connect/email-list-thanks" />' . "\n";
    echo '  <input type=hidden name=errorredirect value="http://www.communikey.us/festival2012/connect/email-list-error" />' . "\n";
    echo '  <div id="cmky_mailinglist">' . "\n";
    echo '    <input name="fields_email" type="text" id="ltxtMailingList" /><input type="button" onClick="$object_class = $item->getObjectClass();" name="subscribe" id="lbtnSubmit" value="ok" />' . "\n";
    echo '  </div>' . "\n";
    echo '  <input type=hidden name="listid" value="3888">' . "\n";
    echo '  <input type=hidden name="specialid:3888" value="G7NV">' . "\n";
    echo '  <input type=hidden name=clientid value="734261">' . "\n";
    echo '  <input type=hidden name=formid value="554">' . "\n";
    echo '  <input type=hidden name=reallistid value="1">' . "\n";
    echo '  <input type=hidden name=doubleopt value="1">' . "\n";
    echo '</form>' . "\n";
     */
}

/**
    * Get random header image from registered images in theme.
    *
    * @since 3.2.0
    *
    * @return string Path to header image
    */
function cmkyf_get_header_images()
{
    $default = defined( 'HEADER_IMAGE' ) ? HEADER_IMAGE : '';
    $url = get_theme_mod( 'header_image', $default );
    $headers = array();
    
    if ( 'remove-header' == $url )
	return false;
    
    global $_wp_default_headers;

    if ( is_random_header_image() )
    {
        $header_image_mod = get_theme_mod('header_image', '');

        if ('random-uploaded-image' == $header_image_mod)
            $headers = get_uploaded_header_images();
        elseif (!empty($_wp_default_headers))
        {
            if ('random-default-image' == $header_image_mod)
            {
                $headers = $_wp_default_headers;
            }
            else
            {
                $is_random = get_theme_support('custom-header');
                if (isset($is_random[0]) && !empty($is_random[0]['random-default']))
                {
                    $headers = $_wp_default_headers;
                }
            }
        }
    }
    else if(empty($url) == false)
    {
        $headers['header_image'] = array( 'url' => $url );
    }
    
    return $headers;
}
/*
function get_header_image() {
	$default = defined( 'HEADER_IMAGE' ) ? HEADER_IMAGE : '';
	$url = get_theme_mod( 'header_image', $default );

	if ( 'remove-header' == $url )
		return false;

	if ( is_random_header_image() )
		$url = get_random_header_image();

	if ( is_ssl() )
		$url = str_replace( 'http://', 'https://', $url );
	else
		$url = str_replace( 'https://', 'http://', $url );

	return esc_url_raw( $url );
}*/


function cmkyf_set_section($section)
{
    global $cmkyf_section;
    $cmkyf_section = $section;
}

function cmkyf_section()
{
    global $cmkyf_section;
    return $cmkyf_section;
}

function cmkyf_set_subsection($section)
{
    global $cmkyf_subsection;
    $cmkyf_subsection = $section;
}

function cmkyf_subsection()
{
    global $cmkyf_subsection;
    return $cmkyf_subsection;
}

function cmkyf_include($inc)
{
    require( get_template_directory() . '/inc/' . $inc );
}

function cmkyf_current_page_url()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on")
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}