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

<section class="kopa-area kopa-area-31">

<div class="container">

<div class="row">

<div class="col-md-9 col-sm-9 col-xs-12">

    <?php if ( have_posts()) :
    $utm_thumb = esc_attr( get_theme_mod('member_archive_thumbnail', '1' ) );
    $utm_position = esc_attr( get_theme_mod('member_archive_position', '1' ) );
    $utm_excerpt = esc_attr( get_theme_mod('member_archive_excerpt', '1' ) );
    $utm_social = esc_attr( get_theme_mod('member_archive_social', '1' ) );
    ?>

    <div class="widget kopa-professor-list-2-widget">

        <ul class="clearfix">

            <?php
                while ( have_posts() ) {
                    the_post();
                    $member_title = get_the_title();
                    $member_postition = get_post_meta(get_the_ID(), 'utp-member-position', 'true' );
                    $upside_limit = (int)get_theme_mod('member_excerpt_length', '55' );
                    ?>
                        <li>
                            <article class="entry-item row clearfix">
                                <?php if ( has_post_thumbnail() && 1 == $utm_thumb ) : ?>
                                    <div class="entry-thumb col-md-4 col-sm-4 col-xs-12">
                                        <?php include( UPSIDE_PATH . '/inc/post-types/course/parts/thumbnail.php' );?>
                                    </div>
                                <?php endif; ?>

                                <div class="entry-content col-md-8 col-sm-8 col-xs-12">
                                    <header>
                                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($member_title); ?>"><?php echo esc_html($member_title); ?></a></h2>
                                        <?php if ( ! empty($member_postition) && 1 == $utm_position ) : ?>
                                            <span><?php echo esc_html($member_postition);?></span>
                                        <?php endif; ?>
                                    </header>
                                    <?php
                                        if ( 1 == $utm_excerpt ) {
                                            upside_lite_get_excerpt_length($upside_limit);
                                            add_filter('excerpt_length', 'upside_lite_set_excerpt_length' );
                                            the_excerpt();
                                            remove_filter( 'excerpt_length', 'upside_lite_set_excerpt_length' );
                                        }

                                        if ( 1 == $utm_social ) {
                                            include( UPSIDE_PATH . '/inc/post-types/member/parts/follow-social.php' );
                                        }
                                    ?>
                                </div>

                            </article>
                            <!-- entry-item -->
                        </li>
                    <?php
                }
            ?>

        </ul>

        <?php get_template_part( 'template/pagination' ); ?>

    </div>
    <!-- widget -->

    <?php endif; ?>
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
<!-- col-md-3 -->

</div>
<!-- row -->

</div>
<!-- container -->

</section>
<!-- kopa-area -->

<?php if ( isset($upside_current_sidebar['sb_before_footer']) && is_active_sidebar($upside_current_sidebar['sb_before_footer']) ) : ?>
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
