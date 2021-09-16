<?php
global $post;
$upside_lite_show_page_title = get_post_meta($post->ID, 'upside-show-page-title', true);
$upside_lite_show_page_breadcrumb = get_post_meta($post->ID, 'upside-show-breadcrumb', true);
$upside_lite_show_document_search = get_post_meta($post->ID, 'upside-page-show-document-search', true);
get_header();

if ( 1 == $upside_lite_show_page_title ) : ?>
<header class="page-header">

    <div class="mask-pattern"></div>

    <div class="mask"></div>

    <div class="page-header-bg page-header-bg-1"></div>

    <div class="page-header-inner">

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <?php  if ( 1 == $upside_lite_show_document_search || ( isset($_GET['type']) && 'document' == $_GET['type'] ) ) {
                    get_template_part( 'template/module/title2' );
                    ?>
                    <div class="search-box clearfix">
                        <form action="<?php echo esc_url(trailingslashit(home_url('/'))); ?>" class="search-form clearfix" method="get">
                            <input type="text" onblur="if (this.value == '')
                                        this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue)
                                        this.value = '';" value="<?php esc_html_e('Search', 'upside-lite'); ?>" name="s" class="search-text">
                            <button type="submit" class="search-submit"><?php esc_html_e('Search', 'upside-lite'); ?></button>
                            <input type="hidden" name="type" value="document" />
                        </form>
                        <!-- search-form -->
                    </div>
                    <?php } else {
                    get_template_part( '/template/module/title' );
                    }
                    ?>
                </div>
                <!-- col-md-12 -->
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- page-header-inner -->
</header>
<?php
endif;

if ( 1 == $upside_lite_show_page_breadcrumb ) {
    get_template_part( 'template/module/breadcrumb' );
}

$upside_lite_setting = upside_lite_get_template_setting();
if ( isset($upside_lite_setting['layout_id']) ){
    $upside_lite_template  = apply_filters( 'upside_lite_get_page_template', sprintf('template/page/%s', $upside_lite_setting['layout_id']) );
    if ( class_exists('bbPress') && is_bbpress() ) {
        $upside_lite_template  = apply_filters( 'upside_lite_get_page_template', sprintf('template/archive/archive-%s', $upside_lite_setting['layout_id']) );
    }
    get_template_part($upside_lite_template);
} else {
    get_template_part( 'template/page/default' );
}

get_footer();
