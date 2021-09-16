<?php
    global $post;
    $post_temp = $post;
    if ( isset($overide_obj) ) { // check if override value
        $post_temp = $overide_obj;
    }

    // get rate base product rate which link to this course
    $upside_product_link = get_post_meta($post_temp->ID, 'utp-course-product', true);
    if ( ! empty($upside_product_link) ) {
        if ( 'no' === get_option( 'woocommerce_enable_review_rating' )  )
            return;

        $upside_pf = new WC_Product_Factory();
        $temp_product = $upside_pf->get_product($upside_product_link);
        if ( $temp_product ) {
            $count   = $temp_product->get_rating_count();
            if ( $count > 0 ) {
                $upside_current_layout = upside_lite_get_template_setting();
                switch( $upside_current_layout['layout_id'] ) {
                    case 'course-archive-full-width-grid-four-col':
                        $upside_ul_class = 'kopa-rating clearfix';
                        break;
                    case 'course-archive-full-width-grid-three-col':
                        $upside_ul_class = 'kopa-rating pull-right clearfix';
                        break;
                    default:
                        $upside_ul_class = 'kopa-rating clearfix';
                        break;
                }

                echo sprintf('<ul class="%s">', esc_attr($upside_ul_class));
                for ( $i = 0; $i < $count; $i++ ) {
                    echo '<li><i class="fa fa-star"></i></li>';
                }
                $no_star_count = 5 - $count;
                if ( $no_star_count ) {
                    for ( $j = 0; $j < $no_star_count; $j++ ) {
                        echo '<li><i class="fa fa-star-o"></i></li>';
                    }
                }
                echo '</ul>';
            }
        }
    }
