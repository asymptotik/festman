<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar-3" class="sidebar" role="complementary">
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : ?>
			
			<?php endif; ?>
		</ul>
	</div>

