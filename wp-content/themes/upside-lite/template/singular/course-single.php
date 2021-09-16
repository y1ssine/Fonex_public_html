<?php
$upside_current_layout = upside_lite_get_template_setting();
$upside_current_sidebar = $upside_current_layout['sidebars'];
?>
<header class="page-header">
    <div class="mask-pattern"></div>
    <div class="mask"></div>
    <div class="page-header-bg page-header-bg-1"></div>
    <div class="page-header-inner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php get_template_part(  'template/module/title'); ?>
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
<?php get_template_part(  'template/module/breadcrumb'); ?>
<section class="kopa-area kopa-area-31">
<div class="container">
<div class="row">

<div class="col-md-9 col-sm-9 col-xs-12">

<?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            $post_title = get_the_title();

            $ups_thumb = esc_attr( get_theme_mod('course_single_thumbnail', '1') );
            $ups_cat = esc_attr( get_theme_mod('course_single_cat', '1') );
            $ups_author = esc_attr( get_theme_mod('course_single_author', '1') );
            $ups_prev_nex = esc_attr( get_theme_mod('course_single_next_prev', '1') );
            $ups_relate_limit = (int) esc_attr( get_theme_mod('course_single_relate_limit', '2') );
            $ups_teacher = esc_attr( get_theme_mod('course_single_teacher', '1') );
            $ups_meta = esc_attr( get_theme_mod('course_single_meta', '1') );
            $ups_price = esc_attr( get_theme_mod('course_single_price', '1') );
            $ups_join_btn = esc_attr( get_theme_mod('course_single_join_btn', '1') );
            $ups_download_btn = esc_attr( get_theme_mod('course_single_download_btn', '1') );

            if ( 1 == $ups_teacher || 1 == $ups_meta || 1 == $ups_price || 1 == $ups_join_btn || 1 == $ups_download_btn ) {
                $main_cls = 'col-md-8 col-sm-8 col-xs-12 left-col';
            } else {
                $main_cls = 'col-md-12 col-sm-12 col-xs-12 left-col';
            }

            ?>

        <div class="entry-course-box">

        <div class="row">

        <div class="<?php echo esc_attr($main_cls); ?>">

            <h5 class="entry-title" id="upside-post-title"><?php echo esc_html($post_title); ?></h5>

            <?php if ( has_post_thumbnail() && 1 == $ups_thumb ) :  ?>
                <div class="entry-thumb pull-left">
                    <?php the_post_thumbnail('medium'); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <div id="upside-single-content">
                    <?php the_content(); ?>
                </div>
                <br>

                <?php
                    $upside_cats = get_the_terms(get_the_ID(), 'course-category');
                    if ( $upside_cats && ! is_wp_error( $upside_cats ) && 1 == $ups_cat ) {
                        ?>
                        <div class="tag-box">

                            <span><?php esc_html_e('Categories:', 'upside-lite'); ?></span>

                            <?php
                                foreach ( $upside_cats as $term ) {
                                    echo '<a href="' . esc_url(get_term_link( $term->slug, 'course-category' )) . '">' . esc_html($term->name) . '</a>';
                                }
                            ?>

                        </div>
                        <!-- tag-box -->
                        <?php
                    }

                    do_action('upside_lite_add_single_follow');

                    if ( 1 == $ups_author ) {
                        get_template_part( 'template/parts/single/author-meta');
                    }

                    if ( 1 == $ups_prev_nex ) {
                        get_template_part(  'template/parts/single/next-prev');
                    }

                    if ( $ups_relate_limit ) {
                        get_template_part( 'inc/post-types/course/parts/related-courses');
                    }
                ?>
            </div>

        </div>
        <!-- col-md-8 -->
        <?php if ( 1 == $ups_teacher || 1 == $ups_meta || 1 == $ups_price || 1 == $ups_join_btn || 1 == $ups_download_btn ) : ?>
            <div class="col-md-4 col-sm-4 col-xs-12 right-col">

                <?php
                    if ( 1 == $ups_teacher ) {
                        get_template_part(  'inc/post-types/course/parts/single-speaker');
                    }

                    if ( 1 == $ups_meta ) {
                        get_template_part( 'inc/post-types/course/parts/single-metadata');
                    }
                    get_template_part( 'inc/post-types/course/parts/single-other');
                ?>

            </div>
            <!-- col-md-4 -->
        <?php endif; ?>

        </div>
        <!-- row -->


        <?php
            if ( comments_open() ) :
                comments_template();
            endif;
        ?>

        </div>
        <!-- entry-course-box -->

            <?php
        }
    }
?>


</div>
<!-- col-md-9 -->


<?php if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) : ?>
    <div class="col-md-3 col-sm-3 col-xs-12" id="upside-right-sidebar">
        <?php
        add_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog');
        dynamic_sidebar($upside_current_sidebar['sb_right']);
        remove_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog');
        ?>
    </div>
<?php endif; ?>
<!-- col-md-3 -->

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
            <!-- col-md-12 -->

        </div>
        <!-- row -->

    </div>
    <!-- container -->

</section>
<!-- kopa-area-16 -->
<?php endif;