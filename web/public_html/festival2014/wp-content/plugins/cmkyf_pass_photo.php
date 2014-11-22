<?php

/*
  Plugin Name: Pass Photo
  Plugin URI: http://cmky.org/
  Description: Allows folks to submit pass photos.
  Version: 1.0
  License: GPLv2
  Author: Rick Boykin
 */

define('CMKY_MAX_UPLOAD_SIZE', 200000);
define('CMKY_MAX_IMAGES', 10);
define('CMKY_PHOTO_USER_COOOKIE', 'cmkyf_photo_user');
define('CMYK_PHOTO_SESSION_META_KEY', '_cmky_photo_session_id');
define('TYPE_WHITELIST', serialize(array(
    'image/jpeg',
    'image/png',
    'image/gif'
)));

add_shortcode('cmkyf_photo_form', 'cmkyf_photo_form_shortcode');

function cmkyf_photo_form_shortcode() {
    
    /*
    if (!is_user_logged_in()) {

        return '<p>You need to be logged in to submit an image.</p>';
    }
    */
    $image_user = get_user_by( 'login', 'image' );

    $ret = "";
    
    if (isset($_POST['cmkyf_photo_upload_image_form_submitted']) && 
            wp_verify_nonce($_POST['cmkyf_photo_upload_image_form_submitted'], 'cmkyf_photo_upload_image_form')) {

        $result = cmkyf_photo_parse_file_errors($_FILES['cmkyf_photo_image_file'], $_POST['cmkyf_photo_image_caption']);

        if ($result['error']) {
            $ret .= '<p class="error-text">ERROR: ' . $result['error'] . '</p>';
        } else {

            $pass_photo_data = array(
                'post_title' => $result['caption'],
                'post_status' => 'pending',
                'post_author' => $image_user->ID,
                'post_type' => 'pass_photos'
            );

            if ($post_id = wp_insert_post($pass_photo_data)) {

                cmkyf_photo_process_image('cmkyf_photo_image_file', $post_id, $result['caption']);
                $cmkyf_photo_image_category = 0;
                if(isset($_POST['cmkyf_photo_image_category'])) { $cmkyf_photo_image_category = (int)$_POST['cmkyf_photo_image_category']; }
                if($cmkyf_photo_image_category != 0) {
                    wp_set_object_terms($post_id, $cmkyf_photo_image_category, 'cmkyf_photo_image_category');
                }
            }
        }
    }

    if (isset($_POST['cmkyf_photo_form_delete_submitted']) && 
            wp_verify_nonce($_POST['cmkyf_photo_form_delete_submitted'], 'cmkyf_photo_form_delete')) {

        if (isset($_POST['cmkyf_photo_image_delete_id'])) {

            if ($pass_photos_deleted = cmkyf_photo_delete_pass_photos($_POST['cmkyf_photo_image_delete_id'])) {

                $ret .= '<p>' . $pass_photos_deleted . ' photo(s) deleted!</p>';
            }
        }
    }

    $pass_photos_res = cmkyf_photo_get_pass_photos_table($image_user->ID);
    $allow_uploads = true;
    
    if($pass_photos_res) {
        $pass_photos = $pass_photos_res["photos"];
        if (count($pass_photos) >= CMKY_MAX_IMAGES)
            $allow_uploads = false;
    }
    
    if($allow_uploads)
    {
        $cmkyf_photo_image_caption = "";
        $cmkyf_photo_image_category = 0;
        if(isset($_POST['cmkyf_photo_image_caption'])){ $cmkyf_photo_image_caption = $_POST['cmkyf_photo_image_caption']; }
        if(isset($_POST['cmkyf_photo_image_category'])){ $cmkyf_photo_image_category = $_POST['cmkyf_photo_image_category']; }
        $ret .= cmkyf_photo_get_upload_image_form($cmkyf_photo_image_caption, $cmkyf_photo_image_category);
    }
    
    if ($pass_photos_res) 
        $ret .= $pass_photos_res["html"];

    return $ret;
}

function cmkyf_photo_delete_pass_photos($images_to_delete) {

    $images_deleted = 0;

    foreach ($images_to_delete as $pass_photo) {

        if (isset($_POST['cmkyf_photo_image_delete_id_' . $pass_photo]) && wp_verify_nonce($_POST['cmkyf_photo_image_delete_id_' . $pass_photo], 'cmkyf_photo_image_delete_' . $pass_photo)) {

            if ($post_thumbnail_id = get_post_thumbnail_id($pass_photo)) {

                wp_delete_attachment($post_thumbnail_id);
            }

            wp_trash_post($pass_photo);

            $images_deleted ++;
        }
    }

    return $images_deleted;
}

function cmkyf_photo_get_pass_photos_table($user_id) {

    global $wpdb;
    
    $photo_session_id = cmkyf_photo_get_cookie();
    
    if(!is_string($photo_session_id) || strlen($photo_session_id) <= 0) {
        return 0;
    }
    
    $args = array(
        'author' => $user_id,
        'post_type' => 'pass_photos',
        'post_status' => 'pending'
    );

    $photo_session_meta_key = CMYK_PHOTO_SESSION_META_KEY;

    $querystr = "SELECT wposts.* 
        FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
        WHERE wposts.ID = wpostmeta.post_id 
        AND wpostmeta.meta_key = '$photo_session_meta_key'
        AND wpostmeta.meta_value = '$photo_session_id'
        AND wposts.post_author = $user_id
        AND wposts.post_type = 'pass_photos'
        AND wposts.post_status = 'pending'";
    
    //$pass_photos = new WP_Query($args);

    $pass_photos = $wpdb->get_results($querystr, OBJECT);
    
    if (!count($pass_photos))
        return 0;

    $out = '<br/>';
    $out .= '<p>Thank you. You have submitted the following photo(s). Please feel free to delete a photo or submit additional photos.</p>';

    $out .= '<form method="post" action="">';

    $out .= wp_nonce_field('cmkyf_photo_form_delete', 'cmkyf_photo_form_delete_submitted');

    $out .= '<table id="pass_photos">';
    $out .= '<thead><th>Photo</th><th>Name</th><th>Delete</th></thead>';

    foreach ($pass_photos as $pass_photo) {

        $post_thumbnail_id = get_post_thumbnail_id($pass_photo->ID);

        $out .= wp_nonce_field('cmkyf_photo_image_delete_' . $pass_photo->ID, 'cmkyf_photo_image_delete_id_' . $pass_photo->ID, false);

        $out .= '<tr>';
        $out .= '<td>' . wp_get_attachment_link($post_thumbnail_id, 'thumbnail') . '</td>';
        $out .= '<td>' . $pass_photo->post_title . '</td>';
        $out .= '<td><input type="checkbox" name="cmkyf_photo_image_delete_id[]" value="' . $pass_photo->ID . '" /></td>';
        $out .= '</tr>';
    }

    $out .= '</table>';

    $out .= '<input type="submit" name="cmkyf_photo_delete" value="Delete Selected Photos" />';
    $out .= '</form>';

    $ret = array("html" => $out, "photos" => $pass_photos);
    return $ret;
}

function cmkyf_photo_get_cookie() {
    
    $ret = "";
    
    if(ctype_alnum($_COOKIE[CMKY_PHOTO_USER_COOOKIE])) {
        $ret = $_COOKIE[CMKY_PHOTO_USER_COOOKIE];
    }
    
    return $ret;
}

function cmkyf_photo_process_image($file, $post_id, $caption) {

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attachment_id = media_handle_upload($file, $post_id);

    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    
    $photo_session_id = cmkyf_photo_get_cookie();
    
    if(is_string($photo_session_id) && strlen($photo_session_id) > 0) {
        update_post_meta($post_id, CMYK_PHOTO_SESSION_META_KEY, $photo_session_id);
    }

    $attachment_data = array(
        'ID' => $attachment_id,
        'post_excerpt' => $caption
    );

    wp_update_post($attachment_data);

    return $attachment_id;
}

function cmkyf_photo_parse_file_errors($file = '', $image_caption) {

    $result = array();
    $result['error'] = 0;

    if ($file['error']) {

        $result['error'] = "No file uploaded or there was an upload error!";

        return $result;
    }

    if ($image_caption == '') {

        $result['error'] = "Your Name is required!";
        
        return $result;
    }
    
    $image_caption = trim(preg_replace('/[^a-zA-Z0-9\s]+/', ' ', $image_caption));

    if ($image_caption == '') {

        $result['error'] = "Your Name may only contain letters, numbers and spaces!";

        return $result;
    }

    $result['caption'] = $image_caption;

    $image_data = getimagesize($file['tmp_name']);

    if (!in_array($image_data['mime'], unserialize(TYPE_WHITELIST))) {
        $result['error'] = 'Your photo must be a jpeg, png or gif!';
    } elseif (($file['size'] > CMKY_MAX_UPLOAD_SIZE)) {
        $result['error'] = 'Your photo was ' . $file['size'] . ' bytes! It must not exceed ' . CMKY_MAX_UPLOAD_SIZE . ' bytes.';
    }

    return $result;
}

function cmkyf_photo_get_upload_image_form($cmkyf_photo_image_caption = '', $cmkyf_photo_image_category = 0) {

    $out = '';
    $out .= '<form id="cmkyf_photo_upload_image_form" method="post" action="" enctype="multipart/form-data">';

    $out .= wp_nonce_field('cmkyf_photo_upload_image_form', 'cmkyf_photo_upload_image_form_submitted');

    $out .= '<label for="cmkyf_photo_image_caption">Your Name - as it appears on your pass order form.</label><br/>';
    $out .= '<input type="text" id="cmkyf_photo_image_caption" name="cmkyf_photo_image_caption" value="' . $cmkyf_photo_image_caption . '"/><br/>';
    //$out .= '<label for="cmkyf_photo_image_category">Image Category</label><br/>';
    //$out .= cmkyf_photo_get_image_categories_dropdown('cmkyf_photo_image_category', $cmkyf_photo_image_category) . '<br/>';
    $out .= '<label for="cmkyf_photo_image_file">Select a photo that shows your face. We will want to recognize you.<br/>(' . CMKY_MAX_UPLOAD_SIZE/1000 . 'k bytes maximum)</label><br/>';
    $out .= '<input type="file" size="60" name="cmkyf_photo_image_file" id="cmkyf_photo_image_file"><br/>';

    $out .= '<input type="submit" id="cmkyf_photo_submit" name="cmkyf_photo_submit" value="Submit Photo">';

    $out .= '</form>';
    
    return $out;
}

function cmkyf_photo_get_image_categories_dropdown($taxonomy, $selected) {

    return wp_dropdown_categories(array('taxonomy' => $taxonomy, 'name' => 'cmkyf_photo_image_category', 'selected' => $selected, 'hide_empty' => 0, 'echo' => 0));
}

add_action( 'admin_menu', 'cmkyf_view_pass_photos_plugin_menu' );

function cmkyf_view_pass_photos_plugin_menu() {
	add_submenu_page( 'edit.php?post_type=pass_photos', 'Pass Photo Images', 'View Pass Photo Images', 'manage_options', 'cmkyf_view_pass_photo_images', 'cmkyf_view_pass_photo_images_plugin_options' );
}

function cmkyf_photo_get_pass_photo_images_table() {

    global $wpdb;
    
    $image_user = get_user_by( 'login', 'image' );
    $user_id = $image_user->ID;

    $querystr = "SELECT wposts.* 
        FROM $wpdb->posts wposts
        WHERE wposts.post_author = $user_id
        AND wposts.post_type = 'pass_photos'
        AND wposts.post_status = 'pending'
        ORDER BY wposts.post_date DESC";
    
    //$pass_photos = new WP_Query($args);

    $pass_photos = $wpdb->get_results($querystr, OBJECT);
    
    if (!count($pass_photos))
        return 0;

    $out = '';
    $out .= '<p>You Pass Photos</p>';
    $out .= '<table id="pass_photos">';
    $out .= '<thead><th>Name</th><th>Photo</th></thead>';

    foreach ($pass_photos as $pass_photo) {

        $datetime = strtotime($pass_photo->post_date);
        $mysqldate = date("m/d/y g:i:s A", $datetime);

        $post_thumbnail_id = get_post_thumbnail_id($pass_photo->ID);
        $out .= '<tr>';
        $out .= '<td>' . $pass_photo->post_title . '<br/>' . $mysqldate . '</td>';
        $out .= '<td>' . wp_get_attachment_link($post_thumbnail_id, 'full') . '</td>';
        $out .= '</tr>';
    }

    $out .= '</table>';

    $ret = array("html" => $out, "photos" => $pass_photos);
    return $ret;
}

function cmkyf_view_pass_photo_images_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    $pass_photo_data = cmkyf_photo_get_pass_photo_images_table();
    
	echo '<div class="wrap">';
	echo $pass_photo_data['html'];
	echo '</div>';
}

add_action('init', 'cmkyf_photo_plugin_init');

function cmkyf_photo_plugin_init() {

    if (!isset($_COOKIE[CMKY_PHOTO_USER_COOOKIE])) {
        setcookie(CMKY_PHOTO_USER_COOOKIE, uniqid(), time() + 60 * 60 * 24 * 180);
    }
    
    $image_type_labels = array(
        'name' => _x('User images', 'post type general name'),
        'singular_name' => _x('Pass Photo', 'post type singular name'),
        'add_new' => _x('Add New Pass Photo', 'image'),
        'add_new_item' => __('Add New Pass Photo'),
        'edit_item' => __('Edit Pass Photo'),
        'new_item' => __('Add New Pass Photo'),
        'all_items' => __('View Pass Photos'),
        'view_item' => __('View Pass Photo'),
        'search_items' => __('Search Pass Photos'),
        'not_found' => __('No Pass Photos found'),
        'not_found_in_trash' => __('No Pass Photos found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Pass Photos'
    );

    $image_type_args = array(
        'labels' => $image_type_labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'map_meta_cap' => true,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail')
    );

    register_post_type('pass_photos', $image_type_args);

    $image_category_labels = array(
        'name' => _x('Pass Photo Categories', 'taxonomy general name'),
        'singular_name' => _x('Pass Photo', 'taxonomy singular name'),
        'search_items' => __('Search Pass Photo Categories'),
        'all_items' => __('All Pass Photo Categories'),
        'parent_item' => __('Parent Pass Photo Category'),
        'parent_item_colon' => __('Parent Pass Photo Category:'),
        'edit_item' => __('Edit Pass Photo Category'),
        'update_item' => __('Update Pass Photo Category'),
        'add_new_item' => __('Add New Pass Photo Category'),
        'new_item_name' => __('New Pass Photo Name'),
        'menu_name' => __('Pass Photo Categories'),
    );

    $image_category_args = array(
        'hierarchical' => true,
        'labels' => $image_category_labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'pass_photo_category'),
    );

    register_taxonomy('cmkyf_photo_image_category', array('pass_photos'), $image_category_args);

    $default_image_cats = array('humor', 'landscapes', 'sport', 'people');

    foreach ($default_image_cats as $cat) {

        if (!term_exists($cat, 'cmkyf_photo_image_category'))
            wp_insert_term($cat, 'cmkyf_photo_image_category');
    }
}
