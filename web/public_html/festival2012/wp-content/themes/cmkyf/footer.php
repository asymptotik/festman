<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>

				</div> <!-- tabs-1 -->
			</div>  <!-- tabs-main -->
		
			<div id="footer" role="contentinfo">
			<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
					<div id="footer-media-links">
						<a class="btn-ft btn-ft-m btn-twitter" href="http://twitter.com/Communikey" target="_blank"><img alt="Twitter" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<a class="btn-ft btn-ft-m btn-facebook" href="http://www.facebook.com/pages/Communikey/9124975668?ref=ts" target="_blank"><img alt="Facebook" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<a class="btn-ft btn-ft-m btn-myspace" href="http://www.myspace.com/communikey" target="_blank"><img alt="My Space" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<a class="btn-ft btn-ft-m btn-lastfm" href="http://www.last.fm/festival/1809273+Communikey+Festival+2011" target="_blank"><img alt="Last FM" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<a class="btn-ft btn-ft-m btn-vimeo" href="http://vimeo.com/user936197" target="_blank"><img alt="Vimeo" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<a class="btn-ft btn-ft-m btn-flickr" href="http://www.flickr.com/groups/985395@N24/" target="_blank"><img alt="Flickr" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<a class="btn-ft btn-youtube" href="http://www.youtube.com/results?search_query=communikey&search_type=&aq=f" target="_blank"><img alt="youTube" src="<?php echo cmkyf_image_url('clear.gif'); ?>" /></a>
						<div class="clear"></div>
				    </div>
					<!--   <a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a>
					and <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>. -->
					<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
			</div>
		
		</div> <!-- main -->
		
		<div class="end">
			<div class="site-map">
				<a href="?page_id=2#about-154">Privacy Policy</a> |
				<a href="?page_id=2#about-167">Refund Policy</a> |
				<a href="?page_id=2#about-221">Contact</a> |
				<a href="index.php">Home</a> |
				<a href="index.php?page_id=4">Program</a> |
				<a href="index.php?page_id=12">Blog</a> |
				<a href="index.php?page_id=2">About</a>
				
			</div>
			<div class="copyright">
				&#169; 2011 Communikey
			</div>
		</div>
	</div> <!-- page -->
	
	
	<?php wp_footer(); ?>
			
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#<?php echo cmkyf_section() . "-tab"; ?>').addClass('ui-tabs-selected ui-state-active');
		});
	</script>

</body>
</html>
