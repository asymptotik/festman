<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
?>

<article id="highlight-<?php the_ID(); ?>" class="highlight column-3">
	<header class="highlight-header">
		<h2 class="highlight-title"><?php the_title(); ?></h2>
	</header><!-- .entry-header -->

	<div class="highlight-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="highlight-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->