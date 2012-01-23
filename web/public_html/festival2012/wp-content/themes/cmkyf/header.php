<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">


<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/css/dark-hive/jquery-ui-1.7.2.custom.css"; ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="icon" type="image/gif" href="<?php echo get_template_directory_uri() . "/images/favicon.gif"?>"/></head>

<script type="text/javascript">
	var cmkyf_theme_url="<?php echo get_template_directory_uri() ?>";
</script>

<style type="text/css" media="screen">

<?php
// Checks to see whether it needs a sidebar or not
if ( empty($withcomments) && !is_single() ) {
?>
	// #page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbg-<?php bloginfo('text_direction'); ?>.jpg") repeat-y top; border: none; }
<?php } else { // No sidebar ?>
	// #page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbgwide.jpg") repeat-y top; border: none; }
<?php } ?>

</style>

<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); 
wp_enqueue_script('jquery'); 
wp_enqueue_script('jquery-ui-custom', get_template_directory_uri() . "/js/jquery-ui-1.7.2.custom.min.js"); 
wp_enqueue_script('jquery-em', get_template_directory_uri() . "/js/jquery.em.min.js"); 
wp_enqueue_script('j-scroll-pane', get_template_directory_uri() . "/js/jScrollPane-1.2.3.min.js"); 
wp_enqueue_script('jquery-scroll-to', get_template_directory_uri() . "/js/jquery.scrollTo-min.js"); 
?>

<?php wp_head(); ?>
</head>
	<body <?php body_class(); ?>>
	 
		<div id="page">
			<div id="main">
				<div id="header" role="banner">
					<a id="header-home" href="index.php">&nbsp;</a>
					<div id="header-ext">
						<a href="?page_id=2#about-297" class="btn-tickets"><img src="<?php echo cmkyf_image_url('clear.gif'); ?>" alt="Tickets"></a>
					</div>
				</div>
				
				<div id="tabs-main" class="ui-tabs ui-widget">
					<div id="tabs-1" class="tab-content ui-tabs-panel">
						<div id="chart-nums-wrapper"><div id="chart-nums"></div></div>
						<img id="world-map" class="alignleft" src="<?php echo cmkyf_image_url('11-world.png'); ?>" alt="World"/>
						<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
							<li id="about-tab" class="ui-state-default"><a href="index.php?page_id=2">ABOUT</a><br/><img src="<?php echo cmkyf_image_url('11-ul-about.png'); ?>"/></li>
							<li id="program-tab" class="ui-state-default"><a href="index.php?page_id=4">PROGRAM</a><br/><img src="<?php echo cmkyf_image_url('11-ul-program.png'); ?>"/></li>
							<li id="blog-tab" class="ui-state-default"><a href="index.php?page_id=12">BLOG</a><br/><img src="<?php echo cmkyf_image_url('11-ul-blog.png'); ?>"/></li>
							<li id="yesand-tab" class="ui-state-default"><a href="index.php?page_id=176">YES, <i>AND</i>...?</a><br/><img src="<?php echo cmkyf_image_url('11-ul-yes-and.png'); ?>"/></li>
						</ul>
