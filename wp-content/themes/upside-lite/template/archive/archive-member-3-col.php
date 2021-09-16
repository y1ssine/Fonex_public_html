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

    <?php
        $post_members = array();
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                global $post;
                $post_members[] = $post;
            }

            $utm_thumb = esc_attr( get_theme_mod('member_archive_thumbnail', '1' ) );
            $utm_position = esc_attr( get_theme_mod('member_archive_position', '1' ) );
            $utm_excerpt = esc_attr( get_theme_mod('member_archive_excerpt', '1' ) );
            $utm_social = esc_attr( get_theme_mod('member_archive_social', '1' ) );

            $post_members_chunk = array_chunk($post_members, 3);
            if ( $post_members_chunk ) {
                ?>

                <div class="widget kopa-professor-list-1-widget">

                    <?php
                        foreach ( $post_members_chunk as $value_group ) {
                            echo '<div class="row">';

                            if ( $value_group ) {
                                foreach ( $value_group as $value ) {
                                    $overide_obj = $value;
                                    $member_postition = get_post_meta($value->ID, 'utp-member-position', 'true' );
                                    $upside_limit = (int) esc_attr( get_theme_mod('member_excerpt_length', '55' ) );
                                    ?>

                                    <div class="col-md-4 col-sm-4 col-xs-12">

                                        <article class="entry-item">
                                            <?php if ( 1 == $utm_thumb ) : ?>
                                                <div class="entry-thumb">
                                                    <?php include( get_template_directory() . '/inc/post-types/course/parts/thumbnail.php' );?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="entry-content">
                                                <header>
                                                    <?php if ( ! empty($value->post_title) ) : ?>
                                                        <h4 class="entry-title"><a href="<?php echo esc_url(get_permalink($value->ID)); ?>" title="<?php echo esc_attr($value->post_title); ?>"><?php echo esc_html($value->post_title); ?></a></h4>
                                                    <?php endif; ?>
                                                    <?php if ( ! empty($member_postition) && 1 == $utm_position ) : ?>
                                                        <span><?php echo esc_html($member_postition); ?></span>
                                                    <?php endif; ?>
                                                </header>

                                                <?php
                                                    if ( 1 == $utm_excerpt ){
                                                        upside_lite_get_excerpt_length($upside_limit);
                                                        add_filter('excerpt_length', 'upside_lite_set_excerpt_length' );
                                                        the_excerpt();
                                                        remove_filter( 'excerpt_length', 'upside_lite_set_excerpt_length' );
                                                    }
                                                    if ( 1 == $utm_social ) {
                                                        include( get_template_directory() . '/inc/post-types/member/parts/follow-social.php' );
                                                    }
                                                ?>

                                            </div>
                                        </article>
                                        <!-- entry-item -->
                                    </div>
                                    <?php
                                }
                            }
                            echo '</div>';
                        }
                    get_template_part( 'pagination' ); ?>
                </div>
                <?php
            }
        }
    ?>
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
<!-- container -->

</div>
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
