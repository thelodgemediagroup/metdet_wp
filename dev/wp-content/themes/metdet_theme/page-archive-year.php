<?php
/*
Template Name: Issue Archive by Year
*/
?>

<?php get_header(); ?>

<div class="content-title"></div>

<div class="metdet-page">

	<h1><span class="issue-year-highlight"><?php echo mysql_real_escape_string($_GET['issue_year']); ?></span> Issues</h1>

	<?php if (function_exists('display_issues_by_year')) { display_issues_by_year(); } ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>