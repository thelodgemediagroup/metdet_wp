<?php get_header(); ?>



<div class="content-title">
    
</div>

<div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
        <div class="post-content">
        	<p class="error-header">404! We couldn't find the page!</p>
            <p>The page you've requested can not be displayed. It appears you've missed your intended destination, either through a bad or outdated link, or a typo in the page you were hoping to reach.</p>
        </div>
    </div>
</div>



<?php get_footer(); ?>