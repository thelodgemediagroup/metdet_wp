<?php
/*
Template Name: Current Issue
*/
?>

<?php get_header(); ?>

<div class="content-title"></div>

<div class="metdet-page">

	<?php if (function_exists('display_current_issue_in_depth')) { display_current_issue_in_depth(); } ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>