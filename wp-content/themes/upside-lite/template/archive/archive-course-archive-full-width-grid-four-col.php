<?php
$upside_lite_current_layout = upside_lite_get_template_setting();
$upside_lite_current_sidebar = $upside_lite_current_layout['sidebars'];
?>

<header class="page-header">

    <div class="mask-pattern"></div>

    <div class="mask"></div>

    <div class="page-header-bg page-header-bg-1"></div>

    <div class="page-header-inner">

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <?php get_template_part( 'template/module/title' ); ?>

                </div>
                <!-- col-md-12 -->

            </div>
            <!-- row -->

        </div>
        <!-- container -->

    </div>
    <!-- page-header-inner -->

</header>
<!-- page-header -->

<?php get_template_part( 'template/module/breadcrumb' ); ?>

<section class="kopa-area kopa-area-31">

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="widget kopa-masonry-list-2-widget" id="course-grid-four-col">
                    <?php
                    $upside_lite_page_title = upside_lite_get_page_title();
                    $upside_lite_description = upside_lite_get_page_descritpion();
                    if ( ! empty($upside_lite_page_title) || ! empty($upside_lite_description) ) :
                        ?>
                        <div class="widget-title widget-title-s5 text-center">
                            <span></span>
                            <?php if ( ! empty($upside_lite_page_title) ) : ?>
                            <h2><?php echo esc_html($upside_lite_page_title); ?></h2>
                            <?php endif; ?>
                            <?php if ( ! empty($upside_lite_description) ) : ?>
                            <p><?php echo esc_textarea($upside_lite_description); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                    <?php
                    if ( have_posts() ) :
                        $upside_lite_course_thumb = esc_attr( get_theme_mod('course_archive_thumb', '1' ) );
                        $upside_lite_course_teacher = esc_attr( get_theme_mod('course_archive_teacher', '1' ) );
                        $upside_lite_course_rate = esc_attr( get_theme_mod('course_archive_rate', '1' ) );
                        $upside_lite_course_hot = esc_attr( get_theme_mod('course_archive_hot', '1' ) );
                        ?>
                        <div class="masonry-list-wrapper">

                            <ul class="up_source_grid_fcol clearfix">

                                <?php while ( have_posts() ) :
                                the_post();
                                ?>
                                <li class="masonry-item">
                                    <article class="entry-item hot-item">
                                        <div class="entry-thumb">
                                            <div class="mask"></div>
                                            <?php
                                                if ( 1 == $upside_lite_course_thumb ) {
                                                    get_template_part( 'inc/post-types/course/parts/thumbnail' );
                                                }

                                                if ( 1 == $upside_lite_course_rate ) {
                                                    get_template_part( 'inc/post-types/course/parts/rate' );
                                                }

                                                if ( 1 == $upside_lite_course_hot ) {
                                                    get_template_part( 'inc/post-types/course/parts/hot' );
                                                }

                                            ?>
                                        </div>
                                        <div class="entry-content">
                                            <?php
                                                if ( 1 == $upside_lite_course_teacher ) {
                                                    get_template_part( 'inc/post-types/course/parts/teacher' );
                                                }
                                                get_template_part( 'inc/post-types/course/parts/title' );
                                            ?>
                                        </div>
                                    </article>
                                </li>
                                <!-- masonry-item -->

                                <?php endwhile; ?>

                            </ul>
                            <!-- clearfix -->

                        </div>
                        <!-- masonry-list-wrapper -->

                        <?php
                        global $wp_query, $wp_rewrite;
                        $total = $wp_query->max_num_pages;
                        if ( $total > 1 ) :
                            $url = get_post_type_archive_link('course' );
                            if (is_tax('course-category')) {
                                $url = get_term_link(get_query_var('term'), 'course-category' );
                            }
                            $current = ((int)get_query_var('paged')) ? (int)get_query_var('paged') : 1;

                            if ($wp_rewrite->using_permalinks()) {
                                $url = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/', 'paged=' );
                            } else {
                                $url = sprintf('%s&paged=', $url);
                            }

                            ?>
                            <div class="text-center">
                                <span id="btn-more-gfc" class="load-more" data-url="<?php echo esc_url( $url ); ?>" data-paged="<?php echo esc_attr($current + 1); ?>"><?php esc_html_e('Load more', 'upside-lite' ); ?><i class="fa fa-spinner"></i></span>
                            </div>
                            <?php endif; ?>

                        <?php endif; ?>

                </div>
                <!-- widget -->

            </div>
            <!-- col-md-12 -->

        </div>
        <!-- row -->

    </div>
    <!-- container -->

</section>
<!-- kopa-area -->
<?php if ( is_active_sidebar($upside_lite_current_sidebar['sb_before_footer']) ) : ?>
    <section class="kopa-area-16 kopa-area-parallax">

        <div class="mask"></div>

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <?php dynamic_sidebar($upside_lite_current_sidebar['sb_before_footer']); ?>

                </div>
                <!-- col-md-12 -->

            </div>
            <!-- row -->

        </div>
        <!-- container -->

    </section>
    <!-- kopa-area-16 -->
<?php endif;