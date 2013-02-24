<?php
    $args = array(
        'meta_key' => 'sgt_slide',
        'meta_value' => 'on',
        'numberposts' => -1,
        );
    $slides = get_posts($args);

    if ( !empty($slides) ) : $exl_posts = Array(); ?>

        <div class="slideshow"><div id="slideshow">

        <?php foreach( $slides as $post ) :
            setup_postdata($post);
            global $exl_posts;
            $exl_posts[] = $post->ID;
        ?>
        <div class="slide clear">
            <div class="post">
                <?php if ( has_post_thumbnail() ) echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'slide',
                    array(
                        'alt'	=> trim(strip_tags( $post->post_title )),
                        'title'	=> trim(strip_tags( $post->post_title )),
                    )).'</a>'; ?>
                
                <div class="slideshow-post">

                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                
                       
                    <div class="post-content"><?php if ( has_post_thumbnail() && function_exists('smart_excerpt') ) smart_excerpt(get_the_excerpt(), 50); else smart_excerpt(get_the_excerpt(), 150); ?></div>

                </div>
            </div>
        </div>
        <?php endforeach; ?>

        </div>

            <a href="javascript: void(0);" id="larr"></a>
            <a href="javascript: void(0);" id="rarr"></a>
        </div>
    <?php endif; ?>