<?php
/**
 * CMKYF Mail List Entry widget
 *
 */
class Cmkyf_Widget_Mailing_List extends WP_Widget {

	function Cmkyf_Widget_Mailing_List() {
		$widget_ops = array( 'description' => __('CMKY Mailing List Email entry') );
		$control_ops = array( 'width' => 400, 'height' => 200 );
		$this->WP_Widget( 'cmkyf-list', __('CMKYF Mailing List'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {

		if ( isset($instance['error']) && $instance['error'] )
			return;

		extract($args, EXTR_SKIP);

		$url = $instance['url'];
		while ( stristr($url, 'http') != $url )
			$url = substr($url, 1);

		if ( empty($url) )
			return;

		$title = $instance['title'];
		$desc =  $instance['desc'];

		$title = apply_filters('widget_title', $title );
		$url = esc_url(strip_tags($url));
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		cmkyf_widget_list_output( $instance );
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$testurl = $new_instance['url'] != $old_instance['url'];
		return cmkyf_widget_list_process( $new_instance, $testurl );
	}

	function form($instance) {

		if ( empty($instance) )
			$instance = array( 'title' => '', 'url' => '', 'items' => 10, 'error' => false, 'show_summary' => 0, 'show_author' => 0, 'show_date' => 0 );
		$instance['number'] = $this->number;

		cmkyf_widget_list_form( $instance );
	}
}

/**
 * Display the RSS entries in a list.
 *
 * @since 2.5.0
 *
 * @param array $args Widget arguments.
 */
function cmkyf_widget_list_output( $args = array() ) {

	$default_args = array( 'desc' => '' );
	$args = wp_parse_args( $args, $default_args );
	extract( $args, EXTR_SKIP );
	
	echo '<form id="ccsfg" name="ccsfg" method="post" action="' . get_template_directory_uri() . '/ccontact/signup/index.php' . '">';
        echo '  <div id="cmky_mailinglist">' . "\n";
        echo '    <input type="text" name="EmailAddress" value="" id="EmailAddress" /><input type="button" name="signup" id="signup" value="ok" />';
        echo '  </div>' . "\n";
                //   <!-- ########## Contact Lists ########## -->
        echo '    <input type="hidden"  checked="checked"  value="CMKY general" name="Lists[]" id="list_CMKY general" />';
                //<!-- ########## Success / Failure Redirects ########## -->
        echo '    <input type="hidden" name="SuccessURL" value="http://communikey.us/festival2012/connect/email-list-thanks" />';
        echo '    <input type="hidden" name="FailureURL" value="http://communikey.us/festival2012/connect/email-list-error" />';
        echo '    <input type="submit" name="signup" id="signup" value="Join My Mailing List" />';
        echo '</form>' . "\n";

	Cmkyf_Widget_Mailing_List_Script::init();
}



/**
 * Display RSS widget options form.
 *
 * The options for what fields are displayed for the RSS form are all booleans
 * and are as follows: 'url', 'title', 'items', 'show_summary', 'show_author',
 * 'show_date'.
 *
 * @since 2.5.0
 *
 * @param array|string $args Values for input fields.
 * @param array $inputs Override default display options.
 */
function cmkyf_widget_list_form( $args, $inputs = null ) {

	$default_inputs = array( 'url' => true, 'title' => true, 'desc' => true);
	$inputs = wp_parse_args( $inputs, $default_inputs );
	extract( $args );
	extract( $inputs, EXTR_SKIP);

	$number = esc_attr( $number );
	$title  = esc_attr( $title );
	$url    = esc_url( $url );
	$items  = (int) $items;
	if ( $items < 1 || 20 < $items )
		$items  = 10;
	$show_summary   = (int) $show_summary;
	$show_author    = (int) $show_author;
	$show_date      = (int) $show_date;

	if ( !empty($error) )
		echo '<p class="widget-error"><strong>' . sprintf( __('RSS Error: %s'), $error) . '</strong></p>';

	if ( $inputs['url'] ) :
?>
	<p><label for="cmkyf-list-url-<?php echo $number; ?>"><?php _e('Enter the Mailing List URL here:'); ?></label>
	<input class="widefat" id="cmkyf-list-url-<?php echo $number; ?>" name="widget-cmkyf-list[<?php echo $number; ?>][url]" type="text" value="<?php echo $url; ?>" /></p>
<?php endif; if ( $inputs['title'] ) : ?>
	<p><label for="cmkyf-list-title-<?php echo $number; ?>"><?php _e('Give the feed a title (optional):'); ?></label>
	<input class="widefat" id="cmkyf-list-title-<?php echo $number; ?>" name="widget-cmkyf-list[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></p>

<?php endif; if ( $inputs['title'] ) : ?>
	<p><label for="cmkyf-list-desc-<?php echo $number; ?>"><?php _e('Give the feed a description (optional):'); ?></label>
	<input class="widefat" id="cmkyf-list-desc-<?php echo $number; ?>" name="widget-cmkyf-list[<?php echo $number; ?>][desc]" type="text" value="<?php echo $desc; ?>" /></p>

<?php
	endif;
	foreach ( array_keys($default_inputs) as $input ) :
		if ( 'hidden' === $inputs[$input] ) :
			$id = str_replace( '_', '-', $input );
?>
	<input type="hidden" id="cmkyf-list-<?php echo $id; ?>-<?php echo $number; ?>" name="widget-cmkyf-list[<?php echo $number; ?>][<?php echo $input; ?>]" value="<?php echo $$input; ?>" />
<?php
		endif;
	endforeach;
}

/**
 * Process RSS feed widget data and optionally retrieve feed items.
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
 * @param array $widget_list RSS widget feed data. Expects unescaped data.
 * @param bool $check_feed Optional, default is true. Whether to check feed for errors.
 * @return array
 */
function cmkyf_widget_list_process( $widget_list, $check_feed = true ) {

	$url           = esc_url_raw(strip_tags( $widget_list['url'] ));
	$title         = trim(strip_tags( $widget_list['title'] ));
	$desc         = trim(strip_tags( $widget_list['desc'] ));

	return compact( 'title', 'url', 'desc');
}


class Cmkyf_Widget_Mailing_List_Script {
	static $add_script = true;
 
	function init() {
		add_action('wp_footer', array(__CLASS__, 'add_script'));
	}
 
	function add_script() {
		if ( ! self::$add_script )
			return;
 
		wp_register_script('jquery-watermark', plugins_url('cmkyf/js/jquery.watermark.min.js'), array(), '1.0', true);
		wp_register_script('cmkyf-list', plugins_url('cmkyf/js/cmkyf-list.js'), array(), '1.0', true);
		
		wp_print_scripts('jquery-watermark');
		wp_print_scripts('cmkyf-list');
		
		self::$add_script = false;
	}
}

