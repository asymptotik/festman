<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<!-- index -->
	<div id="content" class="narrowcolumn" role="main"><?php if (have_posts()) : ?>
	
		<?php while (have_posts()) : the_post(); ?>
		
		<div class="post-panel c1-style">
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<div class="page-panel-header c-border c-bg corners-top">
					<table class="panel-content">
						<tr>
							<td class="post-title"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></td>
							<td class="post-sep"></td>
							<td class="post-time"><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></td>
						</tr>
					</table>
				</div>
				<div class="page-panel-body c-border corners-bottom">
					<div class="entry panel-content"><?php the_content('Read the rest of this entry &raquo;'); ?></div>
				</div>
				<!-- TODO: fix category pages
				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
				-->
			</div>
		</div>
		
		<?php endwhile; ?> 

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
		
		<?php else : ?>
		
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?> 
		<?php endif; ?>
	</div>
	
	<div class="right-sidebar">
		<?php get_sidebar(1); ?>
	</div>
	
	<div class="clear"></div>

<?php
cmkyf_set_section("blog");
get_footer();
?>


