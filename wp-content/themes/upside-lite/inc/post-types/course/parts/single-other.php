<!--<div class="event-ticket">
    <p class="kopa-spinner clearfix">
        <label class="kopa-spinner-label">Number of ticket</label>
        <input class="spinner" name="value" value="1" />
    </p>
</div>-->
<!-- event-ticket -->
<?php
    global $post;

    $upside_price = esc_attr( get_theme_mod('course_single_price', '1') );
    $upside_join_btn = esc_attr( get_theme_mod('course_single_join_btn', '1') );
    $upside_download_btn = esc_attr( get_theme_mod('course_single_download_btn', '1') );

    $upside_course_price_regular = get_post_meta($post->ID, 'utp-course-price', true);
    $upside_course_price_sale = get_post_meta($post->ID, 'utp-course-price-sale', true);
    $upside_price_temp = '';
    if ( ! empty($upside_course_price_regular) ) {
        $upside_price_temp = $upside_course_price_regular;
    }
    if ( ! empty($upside_course_price_sale) ) {
        $upside_price_temp = $upside_course_price_sale;
    }
    if ( ! empty($upside_price_temp) && 1 == $upside_price ) :
?>
    <div class="event-price">
        <p><?php esc_html_e('Price', 'upside-lite'); ?></p>
        <span><?php echo wp_kses_post($upside_price_temp); ?></span>
    </div>
    <!-- event-price -->
<?php endif; ?>

<?php
    $upside_course_btn_join_text = get_post_meta($post->ID, 'utp-course-btn-join-text', true);
    $upside_course_btn_join_link = get_post_meta($post->ID, 'utp-course-btn-join-link', true);

    $upside_course_product_id = get_post_meta($post->ID, 'utp-course-product', true);
    $upside_course_product_id = (int)$upside_course_product_id;
    if ( $upside_course_product_id ) {
        if ( class_exists('WooCommerce') ) {
            $product = new WC_Product( $upside_course_product_id );
            if ( $product && ! is_wp_error($product) ) {
                $product_shop = $product->post;
                if ( $product_shop && ! is_wp_error($product_shop) && 'product' == $product_shop->post_type ) {
                    $upside_course_btn_join_link = get_permalink($upside_course_product_id);
                }
            }
        }
    }

    if ( ! empty($upside_course_btn_join_text) && ! empty($upside_course_btn_join_link) && 1 == $upside_join_btn ):
        ?>
        <a href="<?php echo esc_url($upside_course_btn_join_link); ?>" class="kopa-button pink-button medium-button"><?php echo esc_html($upside_course_btn_join_text); ?></a>
    <?php endif; ?>

<?php
    $upside_course_btn_link = get_post_meta($post->ID, 'utp-course-btn-download-link', true);
    $upside_course_btn_text = get_post_meta($post->ID, 'utp-course-btn-download-text', true);
    if ( ! empty($upside_course_btn_link) && ! empty($upside_course_btn_text) && 1 == $upside_download_btn ):
?>
    <a href="<?php echo esc_url($upside_course_btn_link); ?>" class="kopa-button blue-button medium-button" title="<?php echo esc_attr($upside_course_btn_text); ?>"><?php echo esc_html($upside_course_btn_text); ?></a>
<?php endif;