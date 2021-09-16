<?php
$upside_prev_post = get_previous_post();
$upside_next_post = get_next_post();
$upside_footer_class = 'entry-box-footer clearfix';
$upside_pre_next_image_slug = 'upside-single-prev-nex';
if ( is_singular('k_course') ) {
    $upside_footer_class = 'entry-course-box-footer clearfix';
    $upside_pre_next_image_slug = 'upside-single-course-prev-nex';
}elseif ( is_singular('event') ) {
    $upside_footer_class = 'entry-event-box-footer clearfix';
    $upside_pre_next_image_slug = 'upside-single-course-prev-nex';
}
if ( ! empty($upside_prev_post) || ! empty($upside_next_post) ) :
?>
<footer class="<?php echo esc_attr($upside_footer_class); ?>">

    <?php if ( ! empty($upside_prev_post) ) :
        if ( empty($upside_prev_post->post_title) ) {
            $upside_prev_post->post_title = esc_attr__('No title', 'upside-lite');
        }
        $upside_prev_post_text = esc_attr__('Previous post', 'upside-lite');
        if ( is_singular('event') ) {
            $upside_prev_post_text = esc_attr__('Previous event', 'upside-lite');
        }
    ?>
        <div class="prev-article-item pull-left">
            <article class="entry-item">
                <div class="entry-content">
                    <a href="<?php echo esc_url(get_permalink( $upside_prev_post->ID )); ?>" title="<?php echo wp_kses_post($upside_prev_post->post_title);?>" class="fa fa-angle-left"></a>
                    <a href="<?php echo esc_url(get_permalink( $upside_prev_post->ID )); ?>" title="<?php echo wp_kses_post($upside_prev_post->post_title);?>" class="prev-post"><?php echo wp_kses_post($upside_prev_post_text); ?></a>
                    <h4 class="entry-title"><a href="<?php echo esc_url(get_permalink( $upside_prev_post->ID )); ?>" title="<?php echo wp_kses_post($upside_prev_post->post_title);?>"><?php echo wp_kses_post($upside_prev_post->post_title);?></a></h4>
                </div>
            </article>
        </div>
    <?php endif; ?>

    <?php if ( ! empty($upside_next_post) ) :
        if ( empty($upside_next_post->post_title) ) {
            $upside_next_post->post_title = esc_attr__('No title', 'upside-lite');
        }
        $upside_next_post_text = esc_attr__('Next post', 'upside-lite');
        if ( is_singular('event') ) {
            $upside_next_post_text = esc_attr__('Next event', 'upside-lite');
        }
    ?>
    <div class="next-article-item pull-right">
        <article class="entry-item">
            <div class="entry-content">
                <a href="<?php echo esc_url(get_permalink( $upside_next_post->ID )); ?>" title="<?php echo wp_kses_post($upside_next_post->post_title);?>" class="fa fa-angle-right"></a>
                <a href="<?php echo esc_url(get_permalink( $upside_next_post->ID )); ?>" title="<?php echo wp_kses_post($upside_next_post->post_title);?>" class="next-post"><?php echo wp_kses_post($upside_next_post_text); ?></a>
                <h4 class="entry-title"><a href="<?php echo esc_url(get_permalink( $upside_next_post->ID )); ?>" title="<?php echo wp_kses_post($upside_next_post->post_title);?>"><?php echo wp_kses_post($upside_next_post->post_title);?></a></h4>
            </div>
        </article>
    </div>
    <?php endif; ?>

</footer>
<!-- entry-box-footer -->
<?php endif;