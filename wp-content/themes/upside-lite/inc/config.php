<?php
/** Setup basic for theme */
add_action('after_setup_theme', 'upside_lite_after_setup_theme');

/**
 * Setup basic for theme
 */
function upside_lite_after_setup_theme() {
    global $content_width;
    $content_width = 770;

    add_editor_style();
    add_theme_support('html5');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('loop-pagination');
    add_theme_support('automatic-feed-links');
    add_theme_support('woocommerce');
    add_theme_support( 'custom-logo' );
    load_theme_textdomain( 'upside-lite', get_template_directory() . '/languages' );

    if ( is_admin() ) {
        #widget-custom-field
        add_filter('kopa_widget_form_field_link_icon', 'upside_lite_widget_field_link_icon', 10, 5);

        #admin css
        add_action( 'admin_enqueue_scripts', 'upside_lite_load_custom_wp_admin_style' );
    }

    add_filter('wp_video_shortcode', 'upside_lite_make_video_shortcode_responsive');
    add_filter('embed_handler_html', 'upside_lite_youtube_settings');
    add_filter('embed_oembed_html', 'upside_lite_youtube_settings');

    add_filter('kopa_admin_metabox_advanced_field', 'upside_lite_custom_metabox_advanced_field');
    add_filter('kopa_front_enable_register_flaticon_font', 'upside_lite_enable_register_flaticon_font');

    add_filter('the_title', 'upside_lite_get_the_title');
    add_filter( 'excerpt_more', '__return_false' );

    add_filter( 'embed_oembed_html', 'upside_lite_remove_oembed_attributes', 10, 4 );
    add_filter( 'comment_reply_link', 'upside_lite_edit_reply_link', 10, 4 );
    add_filter( 'edit_comment_link', 'upside_lite_edit_comment_link', 10, 3 );

    #ADD NEXT PREVIOUS TO wp_link_pages FOR SINGLE POST
    add_filter('wp_link_pages_args', 'upside_lite_wp_link_pages_args_add_prevnext');
    add_filter( 'wp_link_pages_link', 'upside_lite_custom_link_pages' );

    add_theme_support('post-formats', array('gallery', 'audio', 'video', 'quote'));
    add_filter('kopa_customization_init_options', 'upside_lite_init_options');
    add_action('tgmpa_register', 'upside_lite_register_required_plugins');

    register_nav_menus(array(
        'top-nav'   => esc_attr__('Icon Menu', 'upside-lite'),
        'main-nav'   => esc_attr__('Main Menu', 'upside-lite'),
        'mobile-nav' => esc_attr__('Mobile Menu', 'upside-lite'),
    ));

    if ( !is_admin() ) {
        add_action('wp_enqueue_scripts', 'upside_lite_enqueue_scripts');
        add_filter('body_class', 'upside_lite_set_body_class');
        add_filter( 'wpcf7_form_class_attr', 'upside_lite_custom_form_class_attr' );
        add_filter( 'wp_nav_menu_objects', 'upside_lite_add_menu_parent_flip_back_class' ); # Add class flipback to menu item
    }
}

/**
 * Register image sizes
 * @return mixed|void
 */
function upside_lite_get_image_sizes() {
    $image_sizes = array(
        array(
            'slug' => 'upside-blog',
            'info' => array(848, 452),
            'enable_custom' => true,
            'widget_title' => esc_attr__('Blog', 'upside-lite'),
            'widget_description' => esc_attr__('Default size 848 x 452 px.', 'upside-lite')
        ),
        array(
            'slug' => 'upside-blog-full',
            'info' => array(1110, 591),
            'enable_custom' => true,
            'widget_title' => esc_attr__('Blog featured full', 'upside-lite'),
            'widget_description' => esc_attr__('Default size 1110 x 591 px.', 'upside-lite')
        ),
        array(
            'slug' => 'upside-widget-recent-post',
            'info' => array(84, 70),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-single-full',
            'info' => array(825, 460),
            'enable_custom' => true,
            'widget_title' => esc_attr__('Image display in top of single page', 'upside-lite'),
            'widget_description' => esc_attr__('Default size 825 x 460 px.', 'upside-lite')
        ),
        array(
            'slug' => 'upside-single-prev-nex',
            'info' => array(412, 135),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-single-course-prev-nex',
            'info' => array(276, 150),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-single-related',
            'info' => array(256, 210),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-post-type-thumb',
            'info' => array(40, 40),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),

        array(
            'slug' => 'upside-portfolio-relate',
            'info' => array(255, 180),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-course-thumb-350-260',
            'info' => array(350, 260),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-course-thumb-175-175',
            'info' => array(175, 175),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-course-teacher-thumb-70-70',
            'info' => array(70, 70),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),

        array(
            'slug' => 'upside-member-list-255-255',
            'info' => array(255, 255),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-event-list-398-230',
            'info' => array(398, 230),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-featured-post-350-210',
            'info' => array(350, 210),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-list-post-140-110',
            'info' => array(140, 110),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-list-post-160-100',
            'info' => array(160, 100),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-course-thumb-350-161',
            'info' => array(350, 161),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
        array(
            'slug' => 'upside-slide-one-1366-602',
            'info' => array(1366, 602),
            'enable_custom' => false,
            'widget_title' => '',
            'widget_description' => ''
        ),
    );

    return apply_filters('upside_lite_get_image_sizes', $image_sizes);

}

/**
 * Get detail of image info
 * @param $slug
 * @return array
 */
function upside_lite_get_image_info($slug) {
    $image_info = array();
    $image_sizes = upside_lite_get_image_sizes();
    if ( $image_sizes ) {
        foreach( $image_sizes as $image ) {
            if ( $slug == $image['slug'] ) {
                $image_info = $image;
                break;
            }
        }
    }
    return $image_info;
}

/**
 * Print post thumbnail
 * @param $post_id
 * @param $image_slug
 * @param array $options
 */
function upside_lite_the_post_thumbnail($post_id, $image_slug, $options = array()) {
    $image_info = upside_lite_get_image_info($image_slug);
    $custom_image = get_post_meta( $post_id, $image_slug, true );

    if ( isset($custom_image) && ! empty($custom_image) ) {
        echo sprintf('<img src="%s" alt="%s" />', esc_url($custom_image), esc_attr(get_the_title($post_id)));
    } elseif ( $image_info ) {
        if ( isset($image_info['info']) ) {
            if ( ! isset($options['alt']) ) {
                $options['alt'] = esc_attr(get_the_title($post_id));
            }
            echo get_the_post_thumbnail($post_id, $image_slug, $options);
        }
    }
}

/**
 * Get default of thumbnail
 * @param $post_id
 * @param $image_slug
 */
function upside_lite_the_default_thumbnail($post_id, $image_slug) {
    $image_info = upside_lite_get_image_info($image_slug);
    if ( isset($image_info['info']) ) {
        echo sprintf('<img src="//placehold.it/%sx%s" alt="%s" />', esc_attr($image_info['info'][0]), esc_attr($image_info['info'][1]), esc_attr(get_the_title($post_id)));
    }
}