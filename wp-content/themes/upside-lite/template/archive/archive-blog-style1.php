<?php
$upside_right_sb = apply_filters('upside_lite_get_sidebar_by_position', 'sb_right', 'sb_right');
$upside_bottom_sb = apply_filters('upside_lite_get_sidebar_by_position', 'sb_before_footer', 'sb_before_footer');

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
    if ( is_active_sidebar( $upside_right_sb ) ) {
        $upside_main_class = 'col-md-9 col-sm-9 col-xs-12';
    } else {
        $upside_main_class = 'col-md-12 col-sm-12 col-xs-12';
    }
?>
<div class="<?php echo esc_attr($upside_main_class); ?>">

    <?php
    if ( have_posts() ) {
        echo '<div class="widget kopa-blog-list-1-widget">';
        while ( have_posts() ) {
            the_post();
            $upside_format = get_post_format();
            if ( false === $upside_format ) {
                $upside_format = 'standard';
            }
            $upside_post_class = 'entry-item';
            $upside_post_class .= ' ' . $upside_format.'-post';
            if ( is_sticky() ) {
                $upside_post_class .= ' sticky';
            }
            $upside_limit = apply_filters( 'upside_lite_post_excerpt_length', esc_attr( get_theme_mod('blog_excerpt_length', '55') ) );
            $upside_show_author = esc_attr( get_theme_mod('blog_author', '1') );
            $upside_show_date = esc_attr( get_theme_mod('blog_date', '1') );
            $upside_show_category = esc_attr( get_theme_mod('blog_category', '1') );
            $upside_show_comment = esc_attr( get_theme_mod('blog_comment', '1') );
            $upside_show_readmore = esc_attr( get_theme_mod('blog_read_more', '1') );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class($upside_post_class); ?>>
                <?php
                $upside_gallery = get_post_meta(get_the_ID(), 'matteritix_gallery', true);
                $upside_custom_content  = get_post_meta(get_the_ID(), 'matteritix_custom', true);

                if( has_post_format('gallery') && !empty($upside_gallery) ){
                    get_template_part( 'template/module/blog/content', 'gallery' );
                } elseif (!empty($upside_custom_content)){
                    get_template_part( 'template/module/blog/content', 'custom' );
                } else {
                    get_template_part( '/template/module/blog/content' );
                }

                if ( 'quote' != $upside_format ) :
                    ?>

                    <div class="entry-content">

                        <h4 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();?></a></h4>

                        <div class="meta-box clearfix">
                            <?php
                                $upside_parts = array();
                                if ( 1 == $upside_show_author ) {
                                    $upside_parts[] = 'template/parts/common/author';
                                }
                                if ( 1 == $upside_show_date ) {
                                    $upside_parts[] = 'template/parts/common/date';
                                }
                                if ( 1 == $upside_show_category ) {
                                    $upside_parts[] = 'template/parts/common/category';
                                }
                                if ( 1 == $upside_show_comment && comments_open() ) {
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
                            upside_lite_get_excerpt_length($upside_limit);
                            add_filter('excerpt_length', 'upside_lite_set_excerpt_length');
                            the_excerpt();
                            remove_filter( 'excerpt_length', 'upside_lite_set_excerpt_length' );
                        ?>
                        <?php
                        if ( 1 == $upside_show_readmore ) : ?>
                            <a href="<?php the_permalink();?>" title="<?php the_title(); ?>" class="kopa-button pink-button kopa-button-icon small-button"><?php esc_html_e('Read more', 'upside-lite');?></a>
                        <?php endif; ?>
                    </div>
                    <!-- entry-content -->

                    <?php endif; ?>

            </article>

            <?php
        }
        get_template_part(  'template/pagination' );
        echo '</div>';
    }
    ?>

</div>
<!-- col-md-9 -->
<?php if ( is_active_sidebar( $upside_right_sb ) ) : ?>
    <div class="col-md-3 col-sm-3 col-xs-12" id="upside-right-sidebar">
        <?php
            add_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog');
            dynamic_sidebar( $upside_right_sb );
            remove_filter('dynamic_sidebar_params', 'upside_lite_apply_sidebar_params_blog');
        ?>
    </div>
<?php endif; ?>

</div>
<!-- row -->

</div>
<!-- container -->

</section>
<!-- kopa-area -->

<?php if ( is_active_sidebar( $upside_bottom_sb ) ) : ?>
    <section class="kopa-area-16 kopa-area-parallax">
        <div class="mask"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php dynamic_sidebar( $upside_bottom_sb ); ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>



