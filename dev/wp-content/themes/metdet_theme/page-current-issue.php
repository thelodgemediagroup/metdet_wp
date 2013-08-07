<?php
/*
Template Name: Current Issue
*/
?>

<?php get_header(); ?>

<div class="content-title">
	
</div>

<div class="metdet-page">

	<?php 
		$metdet_issue_meta = metdet_get_current_issue_meta();
		$year_cat = get_cat_ID($metdet_issue_meta->issue_year);
		$month_cat = get_cat_ID($metdet_issue_meta->issue_month);

		query_posts(array(
	        'category__and' => array($year_cat, $month_cat)
			)
		); 		
	?>

<?php get_template_part('loop-issue'); ?>

<?php wp_reset_query(); ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>