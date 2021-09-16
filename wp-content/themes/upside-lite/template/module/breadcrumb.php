<?php
$upside_enable_breadcrumb = esc_attr( get_theme_mod('header-enable-breadcrumb', '1') );
if ( ! $upside_enable_breadcrumb ) {
    return;
}

global $post, $wp_query;
$upside_current_class = 'current-page';
$upside_prefix        = ' <span>&nbsp;/&nbsp;</span> ';

$upside_breadcrumb = '';

if (is_archive()) {
    if (is_tag()) {
        $term = get_term(get_queried_object_id(), 'post_tag');
        $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, $term->name);
    } else if (is_category()) {
        $terms_link = explode($upside_prefix, substr(get_category_parents(get_queried_object_id(), TRUE, $upside_prefix), 0, (strlen($upside_prefix) * -1)));
        $n = count($terms_link);
        if ($n > 1) {
            for ($i = 0; $i < ($n - 1); $i++) {
                $upside_breadcrumb .= $upside_prefix . $terms_link[$i];
            }
        }
        $upside_breadcrumb .= sprintf('<span itemprop="title" class="%1$s">%2$s</span>', $upside_current_class, get_the_category_by_ID(get_queried_object_id()));
    } elseif ( is_tax('course-category') ) {
        $term = get_term_by('id', get_queried_object_id(), 'course-category');
        if ( $term ) {
            $upside_breadcrumb .= sprintf('<span itemprop="title" class="%1$s">%2$s</span>', $upside_current_class, esc_html($term->name));
        }
    } elseif ( is_post_type_archive('k_course') ) {
        if ( isset($_GET['post_type']) && 'k_course' == $_GET['post_type'] ) {
            $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, esc_html__('Search Courses', 'upside-lite'));
        }
    }
    elseif ( is_post_type_archive('portfolio') ) {
        $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, esc_html__('Portfolios', 'upside-lite'));
    } elseif ( is_post_type_archive('k_course') ) {
        $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, esc_html__('Courses', 'upside-lite'));
    }
    elseif ( is_post_type_archive('product') ) {
        $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, esc_html__('Shop', 'upside-lite'));
    } elseif ( is_post_type_archive('k_member') ) {
        $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, esc_html__('Professor', 'upside-lite'));
    }
    else if (is_year() || is_month() || is_day()) {
        $upside_breadcrumb .= sprintf('<span itemprop="title" class="%1$s">%2$s</span></a>', $upside_current_class, get_the_archive_title(''));
    }else if (is_author()) {
        $author_id = get_queried_object_id();
        $upside_breadcrumb .= sprintf('<span itemprop="title">%2$s</span></a>', $upside_current_class, esc_html(sprintf(esc_attr__('Posts created by %1$s', 'upside-lite'), esc_attr(get_the_author_meta('display_name', $author_id)))) );
    } else if(is_tax()){
        $term = get_queried_object();
        if(isset($term->taxonomy)){
            $upside_breadcrumb .= sprintf('<a class="%1$s" itemprop="url" title="%2$s"><span itemprop="title">%2$s</span></a>', $upside_current_class, esc_html($term->name));
        }
    }
} else if (is_search()) {
    $s = get_search_query();
    $upside_breadcrumb .= sprintf('<span itemprop="title" class="%1$s">%2$s</span>', $upside_current_class, esc_html__('Search: ', 'upside-lite')  . esc_html($s));
} else if (is_singular()) {
    if (is_page()) {
        if (is_front_page()) {
            $upside_breadcrumb = NULL;
        } else {
            $upside_breadcrumb .= sprintf('<span itemprop="title" class="%1$s">%2$s</span>', $upside_current_class, esc_html(get_the_title(get_queried_object_id())));
        }
    } else {
        if ( is_singular('product') ) {
            $upside_terms = get_the_terms(get_queried_object_id(), 'product_cat');
            if ( $upside_terms && ! is_wp_error( $upside_terms )) {
                foreach ($upside_terms as $term) {
                    if ( isset($term) ) {
                        $upside_breadcrumb .= sprintf('<span><a href="%1$s" itemprop="url" title="%3$s"><span itemprop="title">%2$s</span></a></span>', get_term_link($term->term_id, 'product_cat'), esc_html($term->name), wp_kses_post($term->name));
                        $upside_breadcrumb .= '<span>&nbsp;/&nbsp;</span>';
                    }
                }
            }
        } elseif ( is_singular('portfolio') ) {
            $upside_terms = get_the_terms(get_queried_object_id(), 'portfolio-category');
            if ( $upside_terms && ! is_wp_error( $upside_terms )) {
                foreach ($upside_terms as $term) {
                    if ( isset($term) ) {
                        $upside_breadcrumb .= sprintf('<span><a href="%1$s" itemprop="url" title="%3$s"><span itemprop="title">%2$s</span></a></span>', get_term_link($term->term_id, 'portfolio-category'), esc_html($term->name), wp_kses_post($term->name));
                        $upside_breadcrumb .= '<span>&nbsp;/&nbsp;</span>';
                    }
                }
            }
        }
        elseif (is_single()){
            $categories = get_the_category(get_queried_object_id());
            if ($categories) {
                foreach ($categories as $category) {
                    $upside_breadcrumb .= sprintf('<span><a href="%1$s" itemprop="url" title="%3$s"><span itemprop="title">%2$s</span></a></span>', get_category_link($category->term_id), esc_html($category->name), wp_kses_post($category->name));
                    $upside_breadcrumb .= '<span>&nbsp;/&nbsp;</span>';
                }
            }
        }
        $current_title = wp_kses_post(get_the_title(get_queried_object_id()));
        if ( empty($current_title) ) {
            $current_title = esc_attr__('No title', 'upside-lite');
        }
        $upside_breadcrumb .= sprintf('<span itemprop="title" class="%1$s">%2$s</span>', $upside_current_class, $current_title );
    }
} else if (is_404()) {
    $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, esc_html__('Page not found', 'upside-lite') );
} else if(is_home()) {
    $enable_custom = apply_filters('mat_custom_title_for_home', 1);
    if ( $enable_custom ) {
        $page_title = esc_html__('Blog page', 'upside-lite');
    } else {
        $page_title = get_bloginfo('title');
    }
    $upside_breadcrumb .= sprintf('<span class="%1$s" itemprop="title">%2$s</span>', $upside_current_class, $page_title);
}

ob_start();

?>
<div itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="" class="kopa-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php 
                    $page_title = upside_lite_get_page_title();
                    if ( ! empty($page_title) ) : 
                ?>
                    <div class="pull-left"><span><?php echo wp_kses_post($page_title);?></span></div>
                <?php endif; ?>
                <div class="pull-right">
                    <a title="<?php esc_attr_e('Return to Home', 'upside-lite'); ?>" href="<?php echo esc_url(home_url('/')); ?>" itemprop="url">
                        <span itemprop="title"><?php esc_html_e('Home', 'upside-lite'); ?></span>
                    </a>
                    <span>&nbsp;/&nbsp;</span>
                    <?php echo wp_kses_post($upside_breadcrumb);?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$html = ob_get_clean();
do_action('upside_breadcrumb');
echo apply_filters('upside_get_breadcrumb', $html);