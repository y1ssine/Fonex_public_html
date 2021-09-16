<?php
$upside_current_layout = upside_lite_get_template_setting();
$upside_current_sidebar = $upside_current_layout['sidebars'];
global $wp_query;
?>

<header class="page-header">

    <div class="mask-pattern"></div>

    <div class="mask"></div>

    <div class="page-header-bg page-header-bg-1"></div>

    <div class="page-header-inner">

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <?php get_template_part(  'template/module/title' ); ?>

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

<?php get_template_part(  'template/module/breadcrumb' ); ?>

<section class="kopa-area kopa-area-31">
    <div class="container">
        <div class="row">
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <?php if ( have_posts() ) : ?>
                        <div class="widget kopa-course-list-3-widget">
                            <div class="kopa-result-search"><?php echo esc_html('Found ', 'upside-lite' ); ?><span><?php echo esc_html($wp_query->post_count); ?> <?php echo esc_html(' courses', 'upside-lite' ); ?></span> <?php echo esc_html(' according to your search...', 'upside-lite' ); ?></div>

                            <?php
                            $upside_grids = array(
                                array(
                                    'field' => 'utp-course-id',
                                    'title' => 'ID',
                                ),
                                array(
                                    'field' => 'utp-course-title',
                                    'title' => esc_attr__('Course Name', 'upside-lite'),
                                ),
                                array(
                                    'field' => 'utp-course-duration',
                                    'title' => esc_attr__('Duration', 'upside-lite'),
                                ),
                                array(
                                    'field' => 'utp-course-date-start',
                                    'title' => esc_attr__('Start Date', 'upside-lite'),
                                )
                            );
                            $upside_grids = apply_filters('upside_custom_course_grid', $upside_grids);
                            ?>
                            <div class="kopa-course-list-table">

                                <div class="table-header clearfix">
                                    <?php
                                    if ( $upside_grids ) {
                                        foreach ( $upside_grids as $value ) {
                                            echo sprintf('<div class="%s">%s</div>', esc_attr($value['field']), esc_html($value['title']) );
                                        }
                                    }
                                    ?>
                                </div>
                                <!-- table-header -->

                                <?php
                                if ( have_posts() ) :
                                    ?>
                                    <ul class="table-list">

                                        <?php
                                        while ( have_posts() ) {
                                            the_post();

                                            ?>

                                            <li class="clearfix">
                                                <?php
                                                if ( $upside_grids ) {
                                                    foreach ( $upside_grids as $value ) {
                                                        if ( 'utp-course-title' == $value['field'] ) {
                                                            $gird_value =  sprintf('<a href="%s" title="%s">%s</a>', get_permalink( get_the_ID() ), get_the_title(get_the_ID()), get_the_title(get_the_ID()) );
                                                        } else {
                                                            $gird_value = get_post_meta(get_the_ID(), $value['field'], true);
                                                        }
                                                        echo sprintf('<div class="%s">%s</div>', esc_attr($value['field']), wp_kses_post($gird_value) );
                                                    }
                                                }
                                                ?>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                    </ul>
                                    <!-- table-list -->
                                    <?php endif; ?>

                                <?php get_template_part(  'template/pagination' ); ?>
                            </div>
                            <!-- kopa-course-list-table -->

                        </div>
                        <!-- kopa-course-list-3-widget -->

                    <?php else: ?>
                        <div class="widget kopa-course-list-3-widget">
                            <div class="kopa-result-search"><?php echo esc_html('No courses according to your search...', 'upside-lite' ); ?></div>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- col-md-9 -->

            <?php if ( isset($upside_current_sidebar['sb_right']) && is_active_sidebar($upside_current_sidebar['sb_right']) ) : ?>
            <div class="col-md-3 col-sm-3 col-xs-12" id="upside-right-sidebar">
                <?php dynamic_sidebar($upside_current_sidebar['sb_right']);?>
            </div>
            <!-- col-md-3 -->
            <?php endif; ?>
        </div>
        <!-- row -->

    </div>
    <!-- container -->

</section>
<!-- kopa-area -->

<?php if ( is_active_sidebar($upside_current_sidebar['sb_before_footer'])) : ?>
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