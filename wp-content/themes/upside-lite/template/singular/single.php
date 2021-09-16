<?php
$upside_current_layout = upside_lite_get_template_setting();
$upside_current_sidebar = $upside_current_layout['sidebars'];

$upside_enable_page_header = apply_filters('upside_enable_show_page_header', 1);
if ( $upside_enable_page_header ) :
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
<?php endif; ?>

<?php get_template_part( 'template/module/breadcrumb' ); ?>

<section class="kopa-area kopa-area-31">

    <div class="container">

        <div class="row">

            <?php
            if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) {
                $upside_main_class = 'col-md-9 col-sm-9 col-xs-12';
            } else {
                $upside_main_class = 'col-md-12 col-sm-12 col-xs-12';
            }
            ?>
            <div class="<?php echo esc_attr($upside_main_class); ?>">

                <?php if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    $post_title = get_the_title();
                    ?>

                    <div class="entry-box">

                        <?php
                        $enable_fimage = esc_attr( get_theme_mod('single_featured_image', '1' ) );
                        if ( 1 == $enable_fimage ) {
                            get_template_part( 'template/module/blog/content' );
                        }
                        ?>

                        <div class="entry-content">

                            <header class="entry-content-header clearfix">
                                <?php
                                $enable_date = esc_attr( get_theme_mod('single_date', '1' ) );
                                if ( 1 == $enable_date ) :
                                    ?>
                                    <div class="entry-date pull-left">
                                        <p><?php echo get_the_time(apply_filters('upside_single_date_format_day_of_week', 'l')); ?></p>
                                        <strong><?php echo get_the_time(apply_filters('upside_single_date_format_day_of_month_', 'j')); ?></strong>
                                        <span><?php echo get_the_time(apply_filters('upside_single_date_format_month_', 'M')); ?></span>
                                        <?php do_action('upside_single_last_entry_date' ); ?>
                                    </div>
                                    <?php endif; ?>

                                <div class="entry-title">

                                    <h3 id="upside-post-title"><?php echo wp_kses_post($post_title); ?></h3>

                                    <div class="meta-box clearfix">
                                        <?php
                                        $upside_parts = array();
                                        $enable_author_top = esc_attr( get_theme_mod('single_author_top', '1' ) );
                                        if ( 1 == $enable_author_top ) {
                                            $upside_parts[] = 'template/parts/common/author';
                                        }
                                        $enable_category = esc_attr( get_theme_mod('single_category', '1' ) );
                                        if ( 1 == $enable_category ) {
                                            $upside_parts[] = 'template/parts/common/category';
                                        }
                                        $enable_comment = esc_attr( get_theme_mod('single_comment', '1' ) );
                                        if ( 1 == $enable_comment ) {
                                            $upside_parts[] = 'template/parts/common/comment';
                                        }

                                        if ( $upside_parts ) {
                                            $count = 0;
                                            foreach ( $upside_parts as $part ) {
                                                if ( $count  && $count < count($upside_parts) ) {
                                                    echo '<span class="entry-meta pull-left">&nbsp;/&nbsp;</span>';
                                                }
                                                get_template_part($part);
                                                $count++;
                                            }
                                        }
                                        ?>
                                    </div>
                                    <!-- meta-box -->

                                </div>
                                <!-- entry-title -->

                            </header>
                            <!-- entry-content-header -->
                            <div id="upside-single-content">
                                <?php
                                the_content();
                                $post_page_link = wp_link_pages(
                                    array(
                                        'before' =>'',
                                        'after' => '',
                                        'link_before' => '',
                                        'link_after' => '',
                                        'nextpagelink' => '<span class="next fa fa-angle-right"></span>',
                                        'previouspagelink' => '<span class="prev fa fa-angle-left"></span>',
                                        'echo' => false
                                    )
                                );
                                if ( ! empty($post_page_link) ) {
                                    echo '<div class="pagination clearfix"><ul class="clearfix pull-right">' . $post_page_link . '</ul></div>';
                                }
                                ?>
                            </div>

                        </div>
                        <!-- entry-content -->

                        <?php
                        $enable_tag = esc_attr( get_theme_mod('single_tag','1' ) );
                        if ( 1 == $enable_tag ) {
                            the_tags('<div class="tag-box"><span>' . esc_attr__('Tags:', 'upside-lite') . ' </span>','&nbsp;','</div>' );
                        }
                        ?>
                        <!-- tag-box -->

                        <?php
                        $enable_author_f = esc_attr( get_theme_mod('single_author_full', '1' ) );
                        if ( 1 == $enable_author_f ) {
                            get_template_part( 'template/parts/single/author-meta' );
                        }

                        $enable_next_pre = esc_attr( get_theme_mod('single_nex_prev', '1' ) );
                        if ( 1 == $enable_next_pre ) {
                            get_template_part( 'template/parts/single/next-prev' );
                        }

                        ?>

                    </div>
                    <!-- entry-box -->

                    <?php get_template_part( 'template/parts/single/related-posts' ); ?>
                    <!-- related-post -->

                    <?php
                    if ( comments_open() ) :
                        comments_template();
                    endif;
                    ?>

                    <?php
                endwhile;
            endif; ?>

            </div>
            <!-- col-md-9 -->


            <?php if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) : ?>
            <div class="col-md-3 col-sm-3 col-xs-12" id="upside-right-sidebar">
                <?php
                add_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog' );
                dynamic_sidebar($upside_current_sidebar['sb_right']);
                remove_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog' );
                ?>
            </div>
            <?php endif; ?>

        </div>
        <!-- row -->

    </div>
    <!-- container -->

</section>
<!-- kopa-area -->

<?php if ( is_active_sidebar($upside_current_sidebar['sb_before_footer']) ) : ?>
<section class="kopa-area-16 kopa-area-parallax">
    <div class="mask"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php dynamic_sidebar($upside_current_sidebar['sb_before_footer']); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>