<?php
/*
Plugin Name: Ad Rotator
Plugin URI: http://kpumuk.info/projects/wordpress-plugins/ad-rotator/
Description: Simple widget to display random HTML code (advertisements) from a given group of HTML-chunks separated with <tt>&lt;!&#45;&#45;more&#45;&#45;&gt;</tt> on sidebar.
Version: 2.0.3
Author: Dmytro Shteflyuk
Author URI: http://kpumuk.info/
*/

/*  Copyright 2006-2009  Dmytro Shteflyuk  (email: kpumuk@kpumuk.info)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class Ad_Rotator_Widget extends WP_Widget {
  function Ad_Rotator_Widget() {
    $widget_ops = array('classname' => 'widget_ad_rotator', 'description' => __('Rotate ads on your sidebar'));
    $control_ops = array('width' => 400, 'height' => 350);
    $this->WP_Widget('ad_rotator', __('Ad Rotator'), $widget_ops, $control_ops);
  }
  
  function widget($args, $instance) {
    extract($args);
    
    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
    $text = $instance['text'];
    $chunks = explode('<!--more-->', $text);
    $chunkno = mt_rand(0, sizeof($chunks) - 1);

    echo $before_widget;
    if (!empty($title)) echo $before_title . $title . $after_title;
    echo '<div class="widget_ad_rotator">' . trim($chunks[$chunkno]) . '</div>';
    
    echo $after_widget;
  }
  
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    if (current_user_can('unfiltered_html'))
      $instance['text'] = $new_instance['text'];
    else
      $instance['text'] = wp_filter_post_kses($new_instance['text']);

    return $instance;
  }

  function form($instance) {
    $instance = wp_parse_args((array)$instance, array('title' => '', 'text' => ''));
    $title = strip_tags($instance['title']);
    $text = format_to_edit($instance['text']);
?>    
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">
        Title:
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
      </label>
    </p>
    <p><label for="<?php echo $this->get_field_id('text'); ?>">HTML Text (chunks are separated with <tt>&lt;!--more--&gt;</tt>):</label></p>
    <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
<?php
  }
}

function register_Ad_Rotator_Widget(){
  register_widget('Ad_Rotator_Widget');
}

add_action('init', 'register_Ad_Rotator_Widget', 1);

?>