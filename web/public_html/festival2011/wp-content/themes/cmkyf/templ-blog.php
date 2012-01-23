<?php
/**
 * Template Name: Blog Template
 */

get_header(); ?>

<!-- blog turnes out that the blog will use index.php as a template if it is not the default page. -->
<div id="content" class="narrowcolumn" role="main"><?php if (have_posts()) : ?>

<?php while (have_posts()) : the_post(); ?>

<div class="post-panel c1-style">
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<div class="page-panel-header c-border c-bg corners-top">
			<table class="panel-content">
				<tr>
					<td class="post-title">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark"
						title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="page-panel-body c-border corners-bottom">
			<div class="entry"><?php the_content('Read the rest of this entry &raquo;'); ?></div>
		</div>
	</div>
</div>

<?php endwhile; ?> <?php else : ?>

<h2 class="center">Not Found</h2>
<p class="center">Sorry, but you are looking for something that isn't
here.</p>
<?php get_search_form(); ?> <?php endif; ?></div>

<?php get_sidebar(1); ?>

<div class="clear"></div>



<?php
cmkyf_set_section("blog");

get_footer();
?>