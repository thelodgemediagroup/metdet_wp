<?php get_header(); ?>

<div class="content-title">
    <h1>Articles</h1>

</div>

<?php query_posts(array(
        'post__not_in' => $exl_posts,
        'paged' => $paged,
        'posts_per_page' => 4,
    )
); ?>

<?php get_template_part('loop'); ?>

<?php wp_reset_query(); ?>

<?php get_footer(); ?>
