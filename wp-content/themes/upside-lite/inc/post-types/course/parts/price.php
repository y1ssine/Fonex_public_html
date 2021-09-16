<?php
    global $post;
    $upside_course_price_regular = get_post_meta($post->ID, 'utp-course-price', true);
    $upside_course_price_sale = get_post_meta($post->ID, 'utp-course-price-sale', true);
    $upside_course_price_text = get_post_meta($post->ID, 'utp-course-price-text', true);
    if ( ! empty($upside_course_price_text) || ! empty($upside_course_price_sale) || ! empty($upside_course_price_regular) ) :
?>
    <div class="price-box pull-left">
        <?php if ( ! empty($upside_course_price_text) ) : ?>
            <span><?php echo esc_html($upside_course_price_text); ?></span>
        <?php endif; ?>

        <?php if ( ! empty($upside_course_price_sale) ) : ?>
            <del><?php echo esc_html($upside_course_price_sale); ?></del>
        <?php endif; ?>

        <?php if ( ! empty($upside_course_price_regular) ) : ?>
            <ins><?php echo esc_html($upside_course_price_regular); ?></ins>
        <?php endif; ?>

    </div>
<?php endif;