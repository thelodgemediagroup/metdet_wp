<?php
/*
Template Name: Issue Archive
*/
?>

<?php get_header(); ?>

<div class="content-title"></div>

<div class="metdet-page">

	<h1>Archives</h1>

	<?php if ( function_exists('display_all_issues') ) { display_all_issues(); } ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>