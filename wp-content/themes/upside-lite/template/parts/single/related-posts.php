<?php
$upside_get_by = esc_attr( get_theme_mod('single_relate_get_by', 'post_tag') );
$upside_limit  = (int) esc_attr( get_theme_mod('single_relate_limit', '6') );

if ($upside_limit > 0) {
    global $post;
    $upside_taxs = array();

    if ('category' == $upside_get_by) {
        $upside_cats = get_the_category($post->ID);
        if ($upside_cats) {
            $ids = array();
            foreach ($upside_cats as $cat) {
                $ids[] = $cat->term_id;
            }
            $upside_taxs [] = array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => $ids
            );
        }
    } else if ('post_tag' == $upside_get_by) {
        $upside_tags = get_the_tags($post->ID);
        if ($upside_tags) {
            $ids = array();
            foreach ($upside_tags as $tag) {
                $ids[] = $tag->term_id;
            }
            $upside_taxs [] = array(
				'taxonomy' => 'post_tag',
				'field'    => 'id',
				'terms'    => $ids
            );
        }
    }

    if ($upside_taxs) {
        $related_args = array(
			'post_type'      => array($post->post_type),
			'tax_query'      => $upside_taxs,
			'post__not_in'   => array($post->ID),
			'posts_per_page' => $upside_limit
        );

        $upside_related_posts = new WP_Query($related_args);
        if ($upside_related_posts->have_posts()):
            $list_classes = array('widget', 'kopa-related-post');
            $list_classes[] = sprintf('post-list-%d-items', $upside_related_posts->post_count);
            $item_limit = 4;

            ?>

            <div id="related-post">
                <h4><?php esc_html_e('Related Posts', 'upside-lite'); ?></h4>
                <div class="row">
                    <?php while ( $upside_related_posts->have_posts() ) :
                        $upside_related_posts->the_post();
                        $u_relate_title = get_the_title();
                        $u_relate_ex_word = (int) esc_attr( get_theme_mod('single_relate_excerpt', '18') );
                        if ( ! $u_relate_ex_word ) {
                            $u_relate_ex_word = 18;
                        }
                    ?>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <article class="entry-item single-related-match-item">
                                <?php if ( has_post_thumbnail()): ?>
                                    <div class="entry-thumb">
                                        <a href="<?php the_permalink(); ?>" title="<?php echo wp_kses_post($u_relate_title); ?>"><?php upside_lite_the_post_thumbnail(get_the_ID(), 'upside-single-related'); ?></a>
                                    </div>
                                <?php endif; ?>
                                <div class="entry-content">
                                    <span class="entry-categories clearfix"><?php the_category(', '); ?></span>
                                    <h5 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo wp_kses_post($u_relate_title); ?>"><?php echo wp_kses_post($u_relate_title); ?></a></h5>
                                    <?php
                                        upside_lite_get_excerpt_length($u_relate_ex_word);
                                        add_filter('excerpt_length', 'upside_lite_set_excerpt_length');
                                        the_excerpt();
                                        remove_filter( 'excerpt_length', 'upside_lite_set_excerpt_length' );
                                    ?>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <?php
        endif;
        wp_reset_postdata();
    }
}