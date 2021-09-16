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

<section class="kopa-area">

<div class="container">

<div class="row">

<?php if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        $post_title = get_the_title();
        $member_postition = get_post_meta(get_the_ID(), 'utp-member-position', 'true' );

        $utm_thumb = esc_attr( get_theme_mod('member_single_thumbnail', '1' ) );
        $utm_position = esc_attr( get_theme_mod('member_single_position', '1' ) );
        $utm_excerpt = esc_attr( get_theme_mod('member_single_excerpt', '1' ) );
        $utm_social = esc_attr( get_theme_mod('member_single_share_socials', '1' ) );
        $utm_list_course = esc_attr( get_theme_mod('member_single_list_course', '1' ) );
        $utm_limit = apply_filters('utp_list_course_in_member_single_limit', '-1' );
    ?>
        <div class="col-md-9 col-sm-9 col-xs-12">

            <div class="entry-professor-box">

                <div class="row clearfix">

                    <?php if ( has_post_thumbnail() && 1 == $utm_thumb ) :  ?>
                        <div class="entry-professor-thumb col-md-4 col-sm-4 col-xs-12">
                            <?php the_post_thumbnail('medium' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-professor-content col-md-8 col-sm-8 col-xs-12">
                        <header>
                            <h2 class="entry-title" id="upside-post-title"><?php echo esc_html($post_title); ?></h2>
                            <?php if ( ! empty($member_postition) && 1 == $utm_position ) : ?>
                                <span><?php echo esc_html($member_postition); ?></span>
                            <?php endif; ?>
                        </header>
                        <?php
                           if ( 1 == $utm_excerpt ) {
                               the_excerpt();
                           }
                           if ( 1 == $utm_social ) {
                               get_template_part( 'inc/post-types/member/parts/follow-social' );
                           }
                         ?>
                    </div>

                </div>
                <!-- row -->
                <div id="upside-single-content">
                    <?php the_content(); ?>
                </div>

            </div>
            <!-- entry-professor-box -->

            <div class="widget kopa-course-list-3-widget">
                <?php

                    $query = array(
                        'post_type'      => array('k_course'),
                        'posts_per_page' => (int)$utm_limit,
                        'post_status'    => array('publish'),
                        'meta_query' => array(
                            array(
                                'key' => 'utp-course-instructors',
                                'value'   => get_the_ID(),
                                'compare' => 'LIKE',
                            ),
                        ),
                    );

                    $results = new WP_Query( $query );

                    if ( $results->have_posts() && 1 == $utm_list_course ) {
                        $grids = array(
                            array(
                                'field' => 'utp-course-id',
                                'title' => 'ID',
                            ),
                            array(
                                'field' => 'utp-course-title',
                                'title' => 'Course Name',
                            ),
                            array(
                                'field' => 'utp-course-duration',
                                'title' => 'Duration',
                            ),
                            array(
                                'field' => 'utp-course-date-start',
                                'title' => 'Start Date',
                            )
                        );
                        $grids = apply_filters('upside_member_single_custom_grid', $grids);
                        $list_course_title = esc_attr__('List courses taught by: ', 'upside-lite') . get_the_title();
                        ?>

                        <h4 class="widget-title widget-title-s10"><?php echo esc_html($list_course_title); ?></h4>

                        <div class="kopa-course-list-table">

                            <?php if ( $grids ) : ?>
                                <div class="table-header clearfix">
                                    <?php
                                    foreach ( $grids as $value ) {
                                        echo sprintf('<div class="%s">%s</div>', esc_attr($value['field']), esc_html($value['title']) );
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <ul class="table-list">
                                <?php while ( $results->have_posts() ) :
                                    $results->the_post();
                                    ?>
                                        <li class="clearfix">
                                            <?php
                                            if ( $grids ) {
                                                foreach ( $grids as $value ) {
                                                    if ( 'utp-course-title' == $value['field'] ) {
                                                        $gird_value =  get_the_title(get_the_ID());
                                                    } else {
                                                        $gird_value = get_post_meta(get_the_ID(), $value['field'], true);
                                                    }
                                                    echo sprintf('<div class="%s">%s</div>', esc_attr($value['field']), esc_html($gird_value) );
                                                }
                                            }
                                            ?>
                                        </li>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                            </ul>
                            <!-- table-list -->

                        </div>
                        <!-- kopa-course-list-table -->

                        <?php
                    }
                ?>
            </div>
            <!-- kopa-course-list-3-widget -->

        </div>
        <!-- col-md-9 -->
<?php
    endwhile;
    endif;?>

<?php if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) : ?>
    <div class="col-md-3 col-sm-3 col-xs-12" id="upside-right-sidebar">
        <?php
        add_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog' );
        dynamic_sidebar($upside_current_sidebar['sb_right']);
        remove_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog' );
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