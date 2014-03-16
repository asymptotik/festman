<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">

			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>

			<!-- div id="site-generator">
				<?php do_action( 'twentyeleven_credits' ); ?>
			</div -->
                        

	</footer><!-- #colophon -->
</div><!-- #page -->
<div class="end">
    <div class="fb-like" data-href="http://communikey.us/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial"></div>
    <div class="site-map">
            <a href="<?php echo cmkyf_page_url('cmky/privacy-policy'); ?>">Privacy Policy</a> |
            <a href="<?php echo cmkyf_page_url('cmky/refund-policy'); ?>">Refund Policy</a> |
            <a href="<?php echo cmkyf_page_url('cmky/contact'); ?>">Contact</a> |
            <a href="<?php echo cmkyf_page_url(''); ?>">Home</a> |
            <a href="<?php echo cmkyf_page_url('festival'); ?>">Festival</a> |
            <a href="<?php echo cmkyf_page_url('connect'); ?>">Connect</a> |
            <a href="<?php echo cmkyf_page_url('cmky/organization/'); ?>">About</a>

    </div>
    <div class="copyright">
            &#169; 2014 Communikey
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>