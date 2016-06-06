<?php
/*
Template Name: Issue Archive
*/
?>

<?php get_header(); ?>

<?php

	$date = date('Y');
	
	if ( function_exists('display_issues_by_year') ) { $issue_display = display_issues_by_year($date); }
	
	if (!$issue_display)
	{
		$date = $date - 1;
		$issue_display = display_issues_by_year($date);
	}

?>

<div class="content-title"><h1>Archives</h1></div>

<div class="metdet-page">

	<h1 id="archive-date"><?php echo $date; ?></h1>

	<div class="year-selector float-right">
		<?php
			$years = get_years();
			
			foreach ($years as $year)
			{
				echo '<div class="archive-year float-left" unselectable="on">'.$year.'</div>';
			}
			
		?>
	</div> <!--/ .year-selector -->
	<div id="issue-div">
		<?php if (isset($issue_display)) { echo $issue_display; } ?>
	</div> <!--/ #issue-div -->

</div> <!-- MetDet Page -->

<?php get_footer(); ?>