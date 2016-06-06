<?php if ( have_posts() ) : ?>

    <div id="loop" class="clear">

        <?php 

        $home_class_array = array('float-left', 'float-right');
        $home_class_counter = 0;

        ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div <?php post_class('article-home '.$home_class_array[$home_class_counter%2]); ?> id="post_<?php the_ID(); ?>">
            <?php if ( has_post_thumbnail() ) :?>
            <a href="<?php the_permalink() ?>" class="thumb"><?php the_post_thumbnail('thumbnail', array(
                        'alt'	=> trim(strip_tags( $post->post_title )),
                        'title'	=> trim(strip_tags( $post->post_title )),
                    )); ?></a>
            <?php endif; ?>
                        
            <h2><?php the_title(); ?></h2>
            
            <div class="post-content"><?php if (function_exists('smart_excerpt')) smart_excerpt(get_the_excerpt(), 55); ?></div>

            <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
        </div>

        <?php $home_class_counter++ ?>

    <?php endwhile; ?>

    </div>

<?php endif; ?>
