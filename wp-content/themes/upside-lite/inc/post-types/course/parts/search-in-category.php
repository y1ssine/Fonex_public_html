<?php
$search_params = upside_lite_get_search_param();
$exclude_ids = array();
$item_per_row = 3;
$cols = sprintf('col-md-%1$s col-sm-%1$s col-xs-12', esc_attr($item_per_row));
?>
<div class="widget kopa-course-search-widget">

    <div class="widget-content">

        <form method="get" action="<?php echo esc_url(trailingslashit(home_url('/'))); ?>" class="course-form clearfix">

            <div class="row">
                <?php
                foreach ( $search_params as $param ) {
                    if ( $exclude_ids ) {
                        if ( in_array($param['id'], $exclude_ids) ) {
                            continue;
                        }
                    }

                    switch ( $param['element-type'] ) {
                        case 'text':
                            echo '<div class="' . esc_attr($cols) . '">';
                            echo '<div class="text-block">';
                            echo sprintf('<input type="text" name="%s" placeholder="%s" />', esc_attr($param['id']), esc_attr($param['title']));
                            echo '</div>';
                            echo '</div>';
                            break;
                        case 'select':
                            echo '<div class="' . esc_attr($cols) . '">';
                            echo '<div class="select-block">';
                            echo sprintf('<select name="%s">', esc_attr($param['id']));
                            echo sprintf('<option value="">%s</option>', esc_html($param['title']));
                            if ( 'taxonomy' == $param['data'] ) {
                                $terms = get_terms( $param['data-source'] );
                                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                                    foreach ( $terms as $term ) {
                                        echo sprintf('<option value="%s">%s</option>', esc_attr($term->slug), esc_attr($term->name));
                                    }
                                }
                            } elseif ( 'metabox' == $param['data'] && isset($param['data-source']) ) {
                                $post_types = new WP_Query( array(
                                    'post_type' => $param['data-source'],
                                    'post_status'    => array('publish'),
                                    'posts_per_page' => -1,
                                ) );

                                if ( $post_types->have_posts() ) {
                                    while ( $post_types->have_posts() ) {
                                        $post_types->the_post();
                                        echo sprintf('<option value="%s">%s</option>', esc_attr(get_the_ID()), esc_attr(get_the_title()));
                                    }
                                }
                                wp_reset_postdata();
                            }
                            echo '</select>';
                            echo '<i class="fa fa-sort-desc"></i>';
                            echo '</div>';
                            echo '</div>';
                            break;
                    }

                }
                ?>



            </div>

            <div class="text-center">
                <input type="hidden" name="type" value="k_course" />
                <input class="course-submit" type="submit" value="search">
            </div>
        </form>
        <p class="text-center"><?php esc_html_e('Or, If you are looking for more specific News, please visit our News Center to receive our other news publications. Visit Our News Center', 'upside-lite'); ?></p>
    </div>
    <!-- widget-content -->
</div>
<!-- widget -->