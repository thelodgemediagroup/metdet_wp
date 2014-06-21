<?php
/*
Template Name: Current Issue
*/
?>

<?php get_header(); ?>
<?php
	$metdet_issue_meta = metdet_get_current_issue_meta();
	$year_cat = get_cat_ID($metdet_issue_meta->issue_year);
	$month_cat = get_cat_ID($metdet_issue_meta->issue_month);
?>
<div class="content-title">
	<h1>Current Issue</h1>	
</div>

<div class="metdet-page">
	<div class="year-selector float-right">
		<div class="archive-year issue-pdf-button" unselectable="on"><a href="<?php echo $metdet_issue_meta->issue_path; ?>">Issue PDF</a></div>
	</div> <!--/ .year-selector -->
	<div class="in-depth-issue">
		<h1><?php echo $metdet_issue_meta->issue_month ?> <span class="issue-year-highlight"><?php echo $metdet_issue_meta->issue_year; ?></span></h1>
	</div><!--/ .in-depth-issue -->
	<?php 

		query_posts(array(
	        'category__and' => array($year_cat, $month_cat)
			)
		); 		
	?>

<?php get_template_part('loop-issue'); ?>

<?php wp_reset_query(); ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>