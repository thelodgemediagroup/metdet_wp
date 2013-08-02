<?php if ( have_posts() ) : ?>

    <div class="article-list">

    <?php while ( have_posts() ) : the_post(); ?>

        <div id="post_<?php the_ID(); ?>">
            <?php if ( has_post_thumbnail() ) :?>
            <a href="<?php the_permalink() ?>" class="thumb"><?php the_post_thumbnail('thumbnail', array(
                        'alt'	=> trim(strip_tags( $post->post_title )),
                        'title'	=> trim(strip_tags( $post->post_title )),
                    )); ?></a>
            <?php endif; ?>
                        
            <h2><a href="<?php the_permalink(); ?>" class="article-list-header"><?php the_title(); ?></a></h2>
            
            <div class="post-content"><?php if (function_exists('smart_excerpt')) smart_excerpt(get_the_excerpt(), 200); ?></div>

            <a href="<?php the_permalink(); ?>" class="article-list-readmore">Read More</a>
        </div>

    <?php endwhile; ?>

    </div>

<?php endif; ?>
