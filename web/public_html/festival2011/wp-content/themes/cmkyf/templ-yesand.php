<?php
/**
 * Template Name: YesAnd Template
 */

wp_enqueue_script('jquery-history', get_template_directory_uri() . "/js/jquery-history.min.js", array(), false, true);
wp_enqueue_script('about', get_template_directory_uri() . "/js/about.min.js", array(), false, true);


get_header();
 
require_once dirname(__FILE__).'/controls/QueryListControl.php';

$args = array(
  'post_type' => 'page',
  'post_parent' => 690,
  'order_by' => 'title',
  'order' => 'ASC'
  );
query_posts($args);
$itemMap = array(); 

 ?>
 <div class="left-column c3-style">
	<div class="standard-menu corners-top corners-bottom page-panel-body c-border bottom-margin">
		<div class="panel-content">
			<ul id="menu">	
				<?php $n=0; if (have_posts()) : while (have_posts()) : the_post(); ?>
				    
				    <?php 
						$innerQuery = new WP_Query();
						$theId = get_the_ID();
						$innerArgs = array(
						  'post_type' => 'page',
						  'post_parent' => $theId,
						  'order_by' => 'title',
						  'order' => 'ASC'
						  );
						  
						  $innerQuery->query($innerArgs);
						  echo '<li id="index-' . $n . '" class="ui-state-default">';
						  if($innerQuery->have_posts())
						  {
								echo '<span class="ui-icon ui-icon-triangle-1-e"></span>';
								echo '<a href="#about-'; the_id(); echo '" rel="history" title="'; the_title_attribute(); echo '">'; the_title(); echo '</a>';
								$queryListControl = new QueryListControl($innerQuery);
								$queryListControl->render();
				
								$itemMap[$theId] = array('item'=>$n, 'sub-item'=>-1);
								$listMap = $queryListControl->getMap();
								foreach($listMap as $id=>$index)
								{
									$itemMap[$id] = array('item'=>$n, 'sub-item'=>$index);
								}
						  }
						  else
						  {
								echo '<a href="#about-'; the_id(); echo '" rel="history" title="'; the_title_attribute(); echo '">'; the_title(); echo '</a>';
								$itemMap[$theId] = array('item'=>$n, 'sub-item'=>-1);
						  }
						  
						  $n++;
						  
						  echo '</li>';
					  ?>
				 <?php  endwhile; ?>
				 
	
				 <?php endif; wp_reset_query(); ?>
			</ul>
		</div>
	</div>
	
	<?php get_sidebar(3); ?>
</div>

	<div id="content-container" class="narrowcolumn" role="main">
	<div id="content"></div>
	<div id="processing-wrapper">
	<div id="processing"><img
		src="<?php echo cmkyf_image_url('ajax-loader.gif') ?>" /></div>
	</div>
	</div>

	<div class="clear"></div>
	
<!-- ?php get_sidebar(); ? -->

			 <script type='text/javascript'>
			    function getCmkyItemMap()
			    {
			 		var cmkyf_about_values = [];
			 	<?php foreach($itemMap as $id=>$val) { 
			 		$item = $val['item'];
			 		$sub_item = $val['sub-item']; ?>
			 		cmkyf_about_values["item_<?php echo $id; ?>"] = { item: <?php echo $item; ?>, sub_item: <?php echo $sub_item; ?> };
			 	<?php }?>
			 		return cmkyf_about_values;
			    }
			 </script>
			 
<?php 
cmkyf_set_section("yesand");
get_footer(); 
?>
