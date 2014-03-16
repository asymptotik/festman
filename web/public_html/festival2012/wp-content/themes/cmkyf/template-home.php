<?php
/**
 * Template Name: cmky home
 * Description: Communikey home page template
 *
 *
 * @package WordPress
 * @subpackage cmky
 * @since cmky 1.0
 */

cmkyf_set_section('home');
get_header();

wp_enqueue_script('cmkyf-home');

?>

<div class="spotlight-wrapper">
    <nav class="spotlight-nav">
        <?php 
            $headers = cmkyf_get_header_images();
            $max = count($headers);
            for ($n = 0; $n < $max; $n++) { 
                
                echo '<div class="spot-'. $n . '" rel=".image-' . $n . '"></div>' . "\n";
                
            } 
        ?>

    </nav>

    <?php
    $headers = cmkyf_get_header_images();
    $n = 0;
    foreach ($headers as $header) { 

       
        $header_url = sprintf( $header['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ); 
        
        if ( is_ssl() )
		$header_url = str_replace( 'http://', 'https://', $header_url );
	else
		$header_url = str_replace( 'https://', 'http://', $header_url );
        
        $header_url = esc_url_raw( $header_url );
        
        $header_action = $header['action'];
        if(substr_compare("/", $header_action, 0, 1) == 0)
            $header_action =  get_bloginfo ('url') . $header_action;

        echo '<div id="spot-' . $n . '" class="spotlight image-' . $n . '"' . ($n > 0 ? ' style="display:none;"' : '') . '><a href="' . $header_action . '"><img src="' . $header_url . '"/></a></div>' . "\n";
                 
        $n++;
    } ?>

</div>
<div id="primary">
    <div id="content" role="main">

        <?php if (have_posts()) : ?>

            <?php twentyeleven_content_nav('nav-above'); ?>

            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php get_template_part( 'content', 'body' ); ?>

            <?php endwhile; ?>

            <?php twentyeleven_content_nav('nav-below'); ?>

        <?php else : ?>

            <article id="post-0" class="post no-results not-found">
                <header class="entry-header">
                    <h1 class="entry-title"><?php _e('Nothing Found', 'twentyeleven'); ?></h1>
                </header>

                <div class="entry-content">
                    <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </article>

        <?php endif; ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar('highlights'); ?>

<?php get_footer(); ?>