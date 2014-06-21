<?php if ( have_posts() ) : ?>

    <div class="article-list">

    <?php while ( have_posts() ) : the_post(); ?>

        <div id="post_<?php the_ID(); ?>" class="issue-article-list">

            <?php if ( has_post_thumbnail() ) :?>
            <?php $custom_size = array(200,200); ?>
            <a href="<?php the_permalink() ?>" class="article-list-img"><?php the_post_thumbnail($custom_size, array(
                        'alt'	=> trim(strip_tags( $post->post_title )),
                        'title'	=> trim(strip_tags( $post->post_title )),
                    )); ?></a>
            <?php endif; ?>

            <h2 class="article-list-margin"><a href="<?php the_permalink(); ?>" class="article-list-header"><?php smart_excerpt(get_the_title(), 8); ?></a></h2>
            
            <div class="article-list-abstract article-list-margin"><?php if (function_exists('smart_excerpt')) smart_excerpt(get_the_excerpt(), 36); ?></div>

            <a href="<?php the_permalink(); ?>" class="article-list-readmore">Read More</a>
        </div>

    <?php endwhile; ?>

    </div>

<?php endif; ?>