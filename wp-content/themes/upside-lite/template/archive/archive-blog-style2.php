<?php
$upside_current_layout = upside_lite_get_template_setting();
$mat_current_sidebar = $upside_current_layout['sidebars'];
$mat_enable_page_header = apply_filters('upside_enable_show_page_header', 1);
if ( $mat_enable_page_header ) :
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
    if ( is_active_sidebar($mat_current_sidebar['sb_right']) ) {
        $mat_main_class = 'col-md-9 col-sm-9 col-xs-12';
    } else {
        $mat_main_class = 'col-md-12 col-sm-12 col-xs-12';
    }
?>

<div class="<?php echo esc_attr($mat_main_class); ?>">

<?php if ( have_posts() ) : ?>
    <div class="widget kopa-blog-list-2-widget">
        <?php
        while ( have_posts() ) {
            the_post();
            $mat_format = get_post_format();
            if ( false === $mat_format ) {
                $mat_format = 'standard';
            }
            $mat_post_class = 'entry-item clearfix';
            $mat_post_class .= ' ' . $mat_format.'-post';
            if ( is_sticky() ) {
                $mat_post_class .= ' sticky';
            }
            $post_title = get_the_title();

            $mat_limit = apply_filters('upside_lite_post_excerpt_length', esc_attr( get_theme_mod('blog_excerpt_length', '55') ) );
            $mat_show_author = esc_attr( get_theme_mod('blog_author', '1' ) );
            $mat_show_date = esc_attr( get_theme_mod('blog_date', '1' ) );
            $mat_show_category = esc_attr( get_theme_mod('blog_category', '1' ) );
            $mat_show_comment = esc_attr( get_theme_mod('blog_comment', '1' ) );
            $mat_show_readmore = esc_attr( get_theme_mod('blog_read_more', '1' ) );


            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class($mat_post_class); ?>>

                    <?php
                        $mat_gallery = get_post_meta(get_the_ID(), 'matteritix_gallery', true);
                        $mat_custom_content  = get_post_meta(get_the_ID(), 'matteritix_custom', true);

                        if( has_post_format('gallery') && !empty($mat_gallery) ){
                            get_template_part( 'template/module/blog/content', 'gallery' );
                        } elseif (!empty($mat_custom_content)){
                            get_template_part( 'template/module/blog/content', 'custom' );
                        } else {
                            get_template_part( 'template/module/blog/content' );
                        }
                    ?>

                    <?php if ( 'quote' != $mat_format ) : ?>
                    <div class="entry-content">

                        <h4 class="entry-title"><a href="<?php the_permalink() ;?>" title="<?php echo esc_attr($post_title); ?>"><?php echo esc_html($post_title); ?></a></h4>

                        <div class="meta-box clearfix">
                            <?php
                            $upside_parts = array();
                            if ( 1 == $mat_show_author ) {
                                $upside_parts[] = 'template/parts/common/author';
                            }
                            if ( 1 == $mat_show_date ) {
                                $upside_parts[] = 'template/parts/common/date';
                            }
                            if ( 1 == $mat_show_category ) {
                                $upside_parts[] = 'template/parts/common/category';
                            }
                            if ( 1 == $mat_show_comment && comments_open() ) {
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
                        <?php
                            upside_lite_get_excerpt_length($mat_limit);
                            add_filter('excerpt_length', 'upside_lite_set_excerpt_length' );
                            the_excerpt();
                            remove_filter( 'excerpt_length', 'upside_lite_set_excerpt_length' );

                            if ( 1 == $mat_show_readmore ) :
                        ?>
                            <a href="<?php the_permalink();?>" title="<?php the_title(); ?>" class="kopa-button pink-button kopa-button-icon small-button"><?php esc_html_e('Read more', 'upside-lite' );?></a>
                        <?php endif; ?>

                    </div>
                    <!-- entry-content -->
                    <?php endif; ?>
                </article>
                <!-- entry-item -->

            <?php
        }?>

    <?php get_template_part( 'template/pagination' ); ?>

    </div>
    <!-- widget -->
<?php endif; ?>
</div>
<!-- col-md-9 -->

<?php if ( is_active_sidebar($mat_current_sidebar['sb_right']) ) : ?>
    <div class="col-md-3 col-sm-3 col-xs-12" id="upside-right-sidebar">
        <?php
        add_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog' );
        dynamic_sidebar($mat_current_sidebar['sb_right']);
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
<?php if ( is_active_sidebar($mat_current_sidebar['sb_before_footer']) ) : ?>
    <section class="kopa-area-16 kopa-area-parallax">

        <div class="mask"></div>

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <?php dynamic_sidebar($mat_current_sidebar['sb_before_footer']); ?>

                </div>
                <!-- col-md-12 -->

            </div>
            <!-- row -->

        </div>
        <!-- container -->

    </section>
    <!-- kopa-area-16 -->
<?php endif;
