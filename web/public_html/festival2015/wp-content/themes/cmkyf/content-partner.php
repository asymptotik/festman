<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
?>

<article id="partner-<?php the_ID(); ?>" class="partner column-3">
	<header class="partner-header">
		<h2 class="partner-title"><?php the_title(); ?></h2>
	</header><!-- .entry-header -->

        <div>
            <?php if (has_post_thumbnail( get_post()->ID ) ): ?>
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'single-post-thumbnail' ); ?>
                <div class="partner-img" style="background-image: url('<?php echo $image[0]; ?>')"></div>
            <?php endif; ?>
        </div>
	<div class="partner-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<!--footer class="partner-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer --><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->