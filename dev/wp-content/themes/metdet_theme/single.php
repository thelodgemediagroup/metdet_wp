<?php get_header(); ?>

    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>

        <div class="content-title">
            <?php
                $metdet_cat = get_the_category($post->ID);
                $article_month = '';                
                $article_year = '';

                foreach ($metdet_cat as $cat)
                {
                    if (metdet_is_month($cat->name))
                    {
                        $article_month = $cat->name;
                    }
                    else if (metdet_is_year($cat->name))
                    {
                        $article_year = $cat->name;
                    }
                }
                if (!empty($article_month) && !empty($article_year))
                {
                    $issue_timestamp = $article_month.', '.$article_year;
                }
            ?>
            <a href="http://facebook.com/share.php?u=<?php the_permalink() ?>&amp;t=<?php echo urlencode(the_title('','', false)) ?>" target="_blank" class="f" title="Share on Facebook"></a>
            <a href="http://twitter.com/home?status=<?php the_title(); ?> <?php echo getTinyUrl(get_permalink($post->ID)); ?>" target="_blank" class="t" title="Spread the word on Twitter"></a>
            <a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>" target="_blank" class="di" title="Bookmark on Del.icio.us"></a>
            <a href="http://stumbleupon.com/submit?url=<?php the_permalink() ?>&amp;title=<?php echo urlencode(the_title('','', false)) ?>" target="_blank" class="su" title="Share on StumbleUpon"></a>
        </div>

        <div class="entry">
            <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
                <div class="post-meta">
                    <h1><?php the_title(); ?></h1>
                    The Metropolitan  &bull; <?php echo $issue_timestamp; ?>
                </div>
                <div class="post-content"><?php the_content(); ?></div>
                <div class="post-footer"><?php the_tags(__('<strong>Tags: </strong>'), ', '); ?></div>
            </div>
            <div class="post-navigation clear">
                <?php
                    $prev_post = get_adjacent_post(false, '', true);
                    $next_post = get_adjacent_post(false, '', false); ?>
                    <?php if ($prev_post) : $prev_post_url = get_permalink($prev_post->ID); $prev_post_title = $prev_post->post_title; ?>
                        <a class="post-prev" href="<?php echo $prev_post_url; ?>"><em>Previous post</em><span><?php echo $prev_post_title; ?></span></a>
                    <?php endif; ?>
                    <?php if ($next_post) : $next_post_url = get_permalink($next_post->ID); $next_post_title = $next_post->post_title; ?>
                        <a class="post-next" href="<?php echo $next_post_url; ?>"><em>Next post</em><span><?php echo $next_post_title; ?></span></a>
                    <?php endif; ?>
                <div class="line"></div>
            </div>
        </div>

        <?php endwhile; ?>
    <?php endif; ?>

<?php comments_template(); ?>

<?php get_footer(); ?>