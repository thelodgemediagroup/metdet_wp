<?php
/*
Template Name: Issue Archive
*/
?>

<?php get_header(); ?>

<div class="content-title"></div>

<div class="metdet-page">

	<h1 class="">Archives</h1>

	<div class="year-selector float-right">
		<?php
			$years = get_years();
			foreach ($years as $year)
			{
				echo '<button type=button value="'.$year.'" class="archive-year float-left">'.$year.'</button>';
			}
		?>
	</div> <!--/ .year-selector -->
	<div id="issue-div">
		<?php 

			$date = date('Y');
			
			if ( function_exists('display_issues_by_year') ) { $issue_display = display_issues_by_year($date); } 
			if (!$issue_display)
			{
				$date = $date - 1;
				display_issues_by_year($date);
			}

		?>
	</div> <!--/ #issue-div -->

</div> <!-- MetDet Page -->

<?php get_footer(); ?>