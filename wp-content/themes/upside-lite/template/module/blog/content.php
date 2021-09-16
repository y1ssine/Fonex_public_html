<?php
    $upside_sb_right   = apply_filters('materitix_get_sidebar', 'sb_right', 'pos_right');
    $image_slug = is_active_sidebar($upside_sb_right) ? 'upside-blog' : 'upside-blog-full';
?>

<?php if ( has_post_thumbnail() ) : ?>

    <div class="entry-thumb" itemscope="" itemtype="http://schema.org/ImageObject">
        <?php
        if ( is_sticky() && ! is_singular() ) {
            echo '<span class="sticky-post-icon"><i class="fa fa-flash"></i></span>';
        }
        ?>
        <?php if ( ! is_single() ) : ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" itemprop="contentUrl">
        <?php endif; ?>
            <?php upside_lite_the_post_thumbnail(get_the_id(), $image_slug); ?>
        <?php if ( ! is_single() ) : ?>
        </a>
        <?php endif; ?>
    </div>

<?php else : ?>

    <?php
        if ( is_sticky() ) {
            echo '<div class="entry-thumb" itemscope="" itemtype="http://schema.org/ImageObject">';
                echo '<span class="sticky-post-icon"><i class="fa fa-flash"></i></span>';
            ?>

                <?php if ( ! is_single() ) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" itemprop="contentUrl">
                <?php endif;
                    upside_lite_the_default_thumbnail(get_the_ID(), $image_slug);
                if ( ! is_single() ) : ?>
                    </a>
                <?php endif; ?>

            <?php
            echo '</div>';
        }
    ?>

<?php endif;