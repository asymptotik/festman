<?php

/**
 * page widget class taylored for cmkyf
 *
 */
class Cmkyf_Widget_Page extends WP_Widget
{

    function Cmkyf_Widget_Page()
    {
        $widget_ops = array('description' => __('Use a page as the content of a widget'));
        $control_ops = array(); //array('width' => 200, 'height' => 200);
        $this->WP_Widget('cmkyf-page', __('CMKYF Page'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
        if (isset($instance['error']) && $instance['error'])
            return;

        extract($args, EXTR_SKIP);

        $page_name = strip_tags($instance['page_name']);

        if (empty($page_name))
            return;

        $title = $instance['title'];
        $link_to_page = (int)$instance['link_to_page'];
        $desc = '';
        $link = '';

        $queryArgs = array(
            'post_type' => 'page',
            'pagename' => $page_name,
        );

        $posts = get_posts($queryArgs);
        if (count($posts) > 0)
            $post = $posts[0];

        if (!isset($post))
        {
            echo "<p>Page Not Found \"$page_name\"!</p>";
            return;
        }
        $link = get_page_link($post->ID);
        $title = $post->post_title;

        if (empty($title))
            $title = empty($desc) ? __('Unknown Page') : $desc;

        $title = apply_filters('widget_title', $title);

        if ($title && $link_to_page)
            $title = "<a class='cmkyf-page-widget' href='$link' title='$desc'>$title</a>";  

        echo $before_widget;
        if ($title)
            echo $before_title . $title . $after_title;
        cmkyf_widget_page_output($post, $instance);
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        return cmkyf_widget_page_process($new_instance);
    }

    function form($instance)
    {

        if (empty($instance))
            $instance = array('title' => '', 'page_name' => '', 'items' => 10, 'error' => false, 'link_to_page' => 1, 'show_summary' => 0, 'show_author' => 0, 'show_date' => 0);
        $instance['number'] = $this->number;

        cmkyf_widget_page_form($instance);
    }
}

/**
 * Display the page entries in a list.
 *
 * @since 2.5.0
 *
 * @param string|array|object $page page url.
 * @param array $args Widget arguments.
 */
function cmkyf_widget_page_output($post, $args = array())
{

    $default_args = array('show_author' => 0, 'show_date' => 0, 'link_to_page' => 1, 'show_summary' => 0);
    $args = wp_parse_args($args, $default_args);
    extract($args, EXTR_SKIP);

    $link_to_page = (int) $link_to_page;
    $show_summary = (int) $show_summary;
    $show_author = (int) $show_author;
    $show_date = (int) $show_date;

    echo $post->post_content;
}

/**
 * Display page widget options form.
 *
 * The options for what fields are displayed for the page form are all booleans
 * and are as follows: 'url', 'title', 'items', 'show_summary', 'show_author',
 * 'show_date'.
 *
 * @since 2.5.0
 *
 * @param array|string $args Values for input fields.
 * @param array $inputs Override default display options.
 */
function cmkyf_widget_page_form($args, $inputs = null)
{

    $default_inputs = array('page_name' => true, 'title' => true, 'link_to_page' => true, 'show_summary' => true, 'show_author' => true, 'show_date' => true);
    $inputs = wp_parse_args($inputs, $default_inputs);
    extract($args);
    extract($inputs, EXTR_SKIP);

    $number = esc_attr($number);
    $title = esc_attr($title);
    $page_name = esc_attr($page_name);
    $link_to_page = (int) $link_to_page;
    $show_summary = (int) $show_summary;
    $show_author = (int) $show_author;
    $show_date = (int) $show_date;

    if ($inputs['page_name']) :
        ?>
        <p><label for="cmkyf-page-name-<?php echo $number; ?>"><?php _e('Enter the Page Name:'); ?></label>
            <input class="widefat" id="cmkyf-page-name-<?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][page_name]" type="text" value="<?php echo $page_name; ?>" /></p>
    <?php endif;
    if ($inputs['title']) : ?>
        <p><label for="cmkyf-page-title-<?php echo $number; ?>"><?php _e('Give the page a title (optional):'); ?></label>
            <input class="widefat" id="cmkyf-page-title-<?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></p>
    <?php endif;
    if ($inputs['items']) : ?>
    <?php endif;
    if ($inputs['link_to_page']) : ?>
        <p><input id="cmkyf-page-link-to-page-<?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][link_to_page]" type="checkbox" value="1" <?php if ($link_to_page) echo 'checked="checked"'; ?>/>
            <label for="cmkyf-page-link-to-page-<?php echo $number; ?>"><?php _e('Use hyperlink to page?'); ?></label></p>
    <?php endif;
    if ($inputs['show_summary']) : ?>
        <p><input id="cmkyf-page-show-summary-<?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][show_summary]" type="checkbox" value="1" <?php if ($show_summary) echo 'checked="checked"'; ?>/>
            <label for="cmkyf-page-show-summary-<?php echo $number; ?>"><?php _e('Display item content?'); ?></label></p>
    <?php endif;
    if ($inputs['show_author']) : ?>
        <p><input id="cmkyf-page-show-author-<?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][show_author]" type="checkbox" value="1" <?php if ($show_author) echo 'checked="checked"'; ?>/>
            <label for="cmkyf-page-show-author-<?php echo $number; ?>"><?php _e('Display item author if available?'); ?></label></p>
    <?php endif;
    if ($inputs['show_date']) : ?>
        <p><input id="cmkyf-page-show-date-<?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][show_date]" type="checkbox" value="1" <?php if ($show_date) echo 'checked="checked"'; ?>/>
            <label for="cmkyf-page-show-date-<?php echo $number; ?>"><?php _e('Display item date?'); ?></label></p>
        <?php
    endif;
    foreach (array_keys($default_inputs) as $input) :
        if ('hidden' === $inputs[$input]) :
            $id = str_replace('_', '-', $input);
            ?>
            <input type="hidden" id="cmkyf-page-<?php echo $id . '-'; ?><?php echo $number; ?>" name="widget-cmkyf-page[<?php echo $number; ?>][<?php echo $input; ?>]" value="<?php echo $$input; ?>" />
            <?php
        endif;
    endforeach;
}

/**
 * Process page feed widget data and optionally retrieve feed items.
 *
 * The feed widget can not have more than 20 items or it will reset back to the
 * default, which is 10.
 *
 * The resulting array has the feed title, feed url, feed link (from channel),
 * feed items, error (if any), and whether to show summary, author, and date.
 * All respectively in the order of the array elements.
 *
 * @since 2.5.0
 *
 * @param array $widget_page page widget feed data. Expects unescaped data.
 * @param bool $check_feed Optional, default is true. Whether to check feed for errors.
 * @return array
 */
function cmkyf_widget_page_process($widget_page)
{
    $page_name = trim(strip_tags($widget_page['page_name']));
    $title = trim(strip_tags($widget_page['title']));
    $link_to_page = (int) $widget_page['link_to_page'];
    $show_summary = (int) $widget_page['show_summary'];
    $show_author = (int) $widget_page['show_author'];
    $show_date = (int) $widget_page['show_date'];

    return compact('title', 'page_name', 'link', 'items', 'error', 'link_to_page', 'show_summary', 'show_author', 'show_date');
}
