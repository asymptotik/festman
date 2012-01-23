<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

<!-- page -->
<div id="content" class="narrowcolumn" role="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<div class="post-panel c1-style">

		<div class="post" id="post-<?php the_ID(); ?>">
		
			<table class="page-panel-header">
				<tr>
					<td class="post-title"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></td>
					<td class="post-sep"></td>
					<td class="post-time"><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></td>
				</tr>
			</table>
			<hr/>	
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		
	<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	
	</div>
		
	<?php comments_template(); ?>
	
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>

