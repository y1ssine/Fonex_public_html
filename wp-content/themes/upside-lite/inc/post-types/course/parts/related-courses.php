<?php
$upside_course_limit  = (int) esc_attr( get_theme_mod('course_single_relate_limit', '2') );
if ($upside_course_limit > 0) {
    global $post;
    $upside_taxs = array();
    $upside_terms = get_the_terms($post->ID, 'course-category');
    if ($upside_terms) {
        $ids = array();
        foreach ($upside_terms as $cat) {
            $ids[] = $cat->term_id;
        }
        $upside_taxs [] = array(
            'taxonomy' => 'course-category',
            'field'    => 'id',
            'terms'    => $ids
        );
    }
     if ($upside_taxs) {
         $related_args = array(
             'post_type'      => array($post->post_type),
             'tax_query'      => $upside_taxs,
             'post__not_in'   => array($post->ID),
             'posts_per_page' => $upside_course_limit
         );

         $upside_related_courses = new WP_Query($related_args);
         if ( $upside_related_courses->have_posts() ) {
             ?>

         <div id="related-post">

             <h4><?php esc_html_e('Related Courses', 'upside-lite'); ?></h4>

             <div class="row">

                 <?php
                    while ( $upside_related_courses->have_posts() ) {
                        $upside_related_courses->the_post();
                        $u_relate_title = get_the_title();
                        $u_relate_ex_word = (int) esc_attr( get_theme_mod('course_single_relate_excerpt', '18') );
                        if ( ! $u_relate_ex_word ) {
                            $u_relate_ex_word = 18;
                        }
                        ?>

                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <article class="entry-item single-related-match-item">

                                <?php if ( has_post_thumbnail()): ?>
                                    <div class="entry-thumb">
                                        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($u_relate_title); ?>"><?php upside_lite_the_post_thumbnail(get_the_ID(), 'upside-single-related'); ?></a>
                                    </div>
                                <?php endif; ?>

                                <div class="entry-content">
                                    <?php
                                    $upside_cats = get_the_terms(get_the_ID(), 'course-category');
                                    if ( $upside_cats && ! is_wp_error( $upside_cats ) ) {
                                        $upside_cat_html = array();
                                        foreach ( $upside_cats as $term ) {
                                            $upside_cat_html[] = '<a href="' . esc_url(get_term_link( $term->slug, 'course-category' )) . '">' . esc_html($term->name) . '</a>';
                                        }
                                        ?>
                                        <span class="entry-categories clearfix">
                                            <?php
                                            echo join(', ', $upside_cat_html);
                                            ?>
                                        </span>
                                        <?php
                                    }
                                    ?>
                                    <h5 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo wp_kses_post($u_relate_title); ?>"><?php echo wp_kses_post($u_relate_title);?></a></h5>
                                    <?php
                                        upside_lite_get_excerpt_length($u_relate_ex_word);
                                        add_filter('excerpt_length', 'upside_lite_set_excerpt_length');
                                        the_excerpt();
                                        remove_filter( 'excerpt_length', 'upside_lite_set_excerpt_length' );
                                    ?>
                                </div>
                            </article>

                        </div>
                        <!-- col-md-6 -->

                        <?php
                    }
                 wp_reset_postdata();
                 ?>

             </div>
             <!-- row -->

         </div>
         <!-- related-post -->

             <?php
         }
     }

}

?>
