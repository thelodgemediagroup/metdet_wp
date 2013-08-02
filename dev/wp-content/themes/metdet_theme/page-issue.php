<?php
/*
Template Name: Issue
*/
?>

<?php get_header(); ?>

<div class="content-title"></div>

<div class="metdet-page">

	<?php 
	
		$year_cat = get_cat_ID($_GET['issue_year']);
		$month_cat = get_cat_ID($_GET['issue_month']);

		query_posts(array(
	        'category__and' => array($year_cat, $month_cat)
    		)
		); 
	?>

<?php get_template_part('loop-issue'); ?>

<?php wp_reset_query(); ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>