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

<?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
?>

<section class="kopa-area-22">

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">


                <div class="widget clearfix widget_search">

                    <div class="search-box clearfix">

                        <?php if ( bbp_allow_search() ) : ?>

                            <?php bbp_get_template_part( 'form', 'search' ); ?>

                        <?php endif; ?>


                    </div><!--end:search-box-->
                </div>
                <!-- widget -->

            </div>
            <!-- col-md-12 -->

        </div>
        <!-- row -->

    </div>
    <!-- container -->

</section>
<!-- kopa-area-22 -->

<section class="kopa-area kopa-area-31">

<div class="container">

<div class="row">

<?php
if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) {
    $upside_main_cls = 'col-md-9 col-sm-9 col-xs-12';
} else {
    $upside_main_cls = 'col-md-12 col-sm-12 col-xs-12';
}
?>

<div class="<?php echo esc_attr($upside_main_cls); ?>">

    <?php the_content(); ?>

</div>
<!-- col-md-9 -->
<?php if ( is_active_sidebar($upside_current_sidebar['sb_right']) ) : ?>
<div class="col-md-3 col-sm-3 col-xs-12 sidebar" id="upside-right-sidebar">
    <?php dynamic_sidebar($upside_current_sidebar['sb_right']); ?>
</div>
<!-- col-md-3 -->
<?php endif;?>

</div>
<!-- row -->

</div>
<!-- container -->

</section>
<!-- kopa-area -->

<?php
        endwhile;
    endif;
?>

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