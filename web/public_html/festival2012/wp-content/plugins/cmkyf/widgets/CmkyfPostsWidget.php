<?php

/**
 * Recent_Posts widget class
 *
 * @since 2.8.0
 */
class Cmkyf_Widget_Posts extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array('classname' => 'cmkyf_widget_posts', 'description' => __("Posts from your site"));
        parent::__construct('cmkyf-posts', __('CMKYF Posts'), $widget_ops);
        $this->alt_option_name = 'cmkyf_widget_entries';

        add_action('save_post', array(&$this, 'flush_widget_cache'));
        add_action('deleted_post', array(&$this, 'flush_widget_cache'));
        add_action('switch_theme', array(&$this, 'flush_widget_cache'));
    }

    function widget($args, $instance)
    {
        // keeps the warnings away
        $before_widget = '';
        $after_widget = '';
        $before_title = '';
        $after_title = '';
        
        $cache = wp_cache_get('cmkyf_widget_posts', 'widget');

        if (!is_array($cache))
            $cache = array();

        if (!isset($args['widget_id']))
            $args['widget_id'] = $this->id;

        if (isset($cache[$args['widget_id']]))
        {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Posts') : $instance['title'], $instance, $this->id_base);
        $category = empty( $instance['category'] ) ? '' : $instance['category'];
        
        if (empty($instance['number']) || !$number = absint($instance['number']))
            $number = 10;

        $args = array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true);
        if(!empty($category))
            $args['category_name'] = $category;
       
        $icon = WP_PLUGIN_URL . '/cmkyf/festman/images/rss.png';
        
        if(!empty($category))
        {
            $rss_url = get_bloginfo('url') . '?feed=rss2&category_name=' . $category;
            $page_url = get_bloginfo('url') . '?category_name=' . $category;
        }
        else
        {
            $rss_url = get_bloginfo('rss2_url');
            $page_url = get_bloginfo('url');
        }

        if ( $title )
            $title = '<a class="widget-icon" href="' . $rss_url . '" title="' . esc_attr(__('Syndicate this content')) . '">' .
                '<img width="20" height="20" src="' . $icon . '" alt="RSS" /></a><a class="rsswidget" href="' . $page_url .'">' . $title . '</a>';
              
        $r = new WP_Query($args);
        if ($r->have_posts()) {
            
            echo $before_widget;
            if ($title) 
                echo $before_title . $title . $after_title; 
            echo '<ul>';
            while ($r->have_posts()) {
                $r->the_post(); 
            
                $permalink = get_permalink();
                $title = get_the_title() ? get_the_title() : get_the_ID();
                $excerpt = get_the_excerpt();
                $date = get_the_date();
                
                echo '<li><a href="' . esc_attr($permalink) . '" title="' . esc_attr($title) . '">'. esc_html($title) . '</a><div class="content">' . esc_html($excerpt) . '</div><time>' . esc_html($date) . '</time></li>' . "\n";
            }
            echo '</ul>';
            echo $after_widget; 
            
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();
        }

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('cmkyf_widget_posts', $cache, 'widget');
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['category'] = strip_tags($new_instance['category']);
        $instance['number'] = (int) $new_instance['number'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['cmkyf_widget_entries']))
            delete_option('cmkyf_widget_entries');

        return $instance;
    }

    function flush_widget_cache()
    {
        wp_cache_delete('cmkyf_widget_posts', 'widget');
    }

    function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $category = isset($instance['category']) ? esc_attr($instance['category']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo $category; ?>" /></p>
   
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        <?php
    }
}