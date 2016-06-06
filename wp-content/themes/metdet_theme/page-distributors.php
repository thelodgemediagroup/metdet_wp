<?php
/*
Template Name: MetDet Distributors
*/
?>

<?php get_header(); ?>

<div class="content-title"></div>

<div class="metdet-page">

    <h1>Distribution</h1>

    <?php if ( function_exists('display_all_metdet_distributors') ) { display_all_metdet_distributors(); } ?>

</div> <!-- MetDet Page -->

<?php get_footer(); ?>