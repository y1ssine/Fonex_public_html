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

<section class="kopa-area">

    <div class="container">

        <div class="row">

            <?php
            if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) {
                $upside_main_class = 'col-md-9 col-sm-9 col-xs-12';
            } else {
                $upside_main_class = 'col-md-12 col-sm-12 col-xs-12';
            }
            ?>
            <div class="<?php echo esc_attr($upside_main_class); ?>" id="upside-page-content">

                <?php
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();

                        the_content();

                        wp_link_pages(array(
                            'before'           => '<div class="pagination clearfix">',
                            'after'            => '</div>',
                            'next_or_number'   => 'number',
                            'nextpagelink'     => '<i class="fa fa-angle-double-right"></i>',
                            'previouspagelink' => '<i class="fa fa-angle-double-left"></i>',
                            'echo'             => 1
                        ));

                        if ( comments_open() ) :
                            comments_template();
                        endif;
                    }
                }
                ?>

            </div>

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
