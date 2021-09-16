<?php
add_action('wp_enqueue_scripts', 'upside_lite_customize_page_title_bg', 100);
add_action('wp_enqueue_scripts', 'upside_lite_customize_error_bg', 100);

function upside_lite_customize_page_title_bg() {
    $image_bg = esc_attr( get_theme_mod('header-page-title-bg', '') );
    if ( ! empty($image_bg) ) {
        $page_header_css = sprintf(".page-header .page-header-bg.page-header-bg-1 {background-image: url(%s);}", esc_url($image_bg));
        wp_add_inline_style('upside-lite-main-style', $page_header_css);
    }
}

function upside_lite_customize_error_bg() {
    $image_bg = esc_attr( get_theme_mod('error_image_bg', '') );
    if ( ! empty($image_bg) ) {
        $image_css = sprintf(".kopa-area-404 {background-image: url(%s);}", esc_url($image_bg));
        wp_add_inline_style('upside-lite-main-style', $image_css);
    }
}

/**
 * Register elements for theme customizer
 * @param $options
 * @return mixed
 */
function upside_lite_init_options($options){
    #Panels

    $options['panels'][] = array(
        'id'    => 'upside_panel_theme_option',
        'title' => esc_attr__('Theme options', 'upside-lite'));

    #Sections

    $options['sections'][] = array(
        'id'    => 'upside_section_custom_header',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('Header options', 'upside-lite'));

    $options['sections'][] = array(
        'id'    => 'upside_section_custom_blog',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('Archive Page', 'upside-lite'));

    $options['sections'][] = array(
        'id'    => 'upside_section_custom_single_post',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('Single Page', 'upside-lite'));

    $options['sections'][] = array(
        'id'    => 'upside_section_follow_social',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('Social Network Links', 'upside-lite'));

    $options['sections'][] = array(
        'id'    => 'upside_section_custom_single_product',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('Shop Page', 'upside-lite'));

    if ( class_exists('Upside_Lite_Toolkit_Course') ) {
        $options['sections'][] = array(
            'id'    => 'upside_section_custom_course_archive',
            'panel' => 'upside_panel_theme_option',
            'title' => esc_attr__('Course Archive Page', 'upside-lite'));

        $options['sections'][] = array(
            'id'    => 'upside_section_custom_course_single',
            'panel' => 'upside_panel_theme_option',
            'title' => esc_attr__('Course Single Page', 'upside-lite'));
    }

    if ( class_exists('Upside_Lite_Toolkit_Member') ) {
        $options['sections'][] = array(
            'id'    => 'upside_section_custom_member_archive',
            'panel' => 'upside_panel_theme_option',
            'title' => esc_attr__('Member archive', 'upside-lite'));

        $options['sections'][] = array(
            'id'    => 'upside_section_custom_member_single',
            'panel' => 'upside_panel_theme_option',
            'title' => esc_attr__('Member Single Page', 'upside-lite'));
    }

    $options['sections'][] = array(
        'id'    => 'upside_section_custom_font',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('Fonts', 'upside-lite'));

    $options['sections'][] = array(
        'id'    => 'upside_section_custom_error_page',
        'panel' => 'upside_panel_theme_option',
        'title' => esc_attr__('404 Error page', 'upside-lite'));

    #Settings

    #Header option
    $options['settings'][] = array(
        'settings'          => 'header-enable-search',
        'label'       => esc_attr__('Search form', 'upside-lite'),
        'description' => esc_attr__('Show search form on the top of the header.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_header',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'header-enable-breadcrumb',
        'label'       => esc_attr__('Breadcrumb', 'upside-lite'),
        'description' => esc_attr__('Show breadcrumb on the sub pages.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_header',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'header-page-title-bg',
        'label'       => esc_attr__('Page header background', 'upside-lite'),
        'description' => esc_attr__('upload page header background.', 'upside-lite'),
        'default'     => '',
        'type'        => 'image',
        'section'     => 'upside_section_custom_header',
        'transport'   => 'refresh');

    #Blog options
    $options['settings'][] = array(
        'settings'          => 'blog_excerpt_length',
        'label'       => esc_attr__('Excerpt lenght (E.g: 30)', 'upside-lite'),
        'description' => '',
        'default'     => '',
        'type'        => 'text',
        'section'     => 'upside_section_custom_blog',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'blog_author',
        'label'       => esc_attr__('Author', 'upside-lite'),
        'description' => esc_attr__('Show author of the post.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_blog',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'blog_date',
        'label'       => esc_attr__('Date', 'upside-lite'),
        'description' => esc_attr__('Show published date of the post.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_blog',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'blog_category',
        'label'       => esc_attr__('Categories', 'upside-lite'),
        'description' => esc_attr__('Show categories of the post.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_blog',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'blog_comment',
        'label'       => esc_attr__('Comment', 'upside-lite'),
        'description' => esc_attr__('Show number of comments.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_blog',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'blog_read_more',
        'label'       => esc_attr__('Read more', 'upside-lite'),
        'description' => esc_attr__('Show "read more" button.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_blog',
        'transport'   => 'refresh');

    #Single post option
    $options['settings'][] = array(
        'settings'          => 'single_featured_image',
        'label'       => esc_attr__('Featured image', 'upside-lite'),
        'description' => esc_attr__('Show featured image.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_date',
        'label'       => esc_attr__('Date', 'upside-lite'),
        'description' => esc_attr__('Show published date of the post.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_author_top',
        'label'       => esc_attr__('Author', 'upside-lite'),
        'description' => esc_attr__('Show author of the post.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_category',
        'label'       => esc_attr__('Categories', 'upside-lite'),
        'description' => esc_attr__('Show categories of the post.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_comment',
        'label'       => esc_attr__('Comment number', 'upside-lite'),
        'description' => esc_attr__('Show number of comments', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_tag',
        'label'       => esc_attr__('Tags', 'upside-lite'),
        'description' => esc_attr__('Show tags of the post', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_author_full',
        'label'       => esc_attr__('About author', 'upside-lite'),
        'description' => esc_attr__('Show about author information.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_nex_prev',
        'label'       => esc_attr__('Next & previous post', 'upside-lite'),
        'description' => esc_attr__('Show Next and Previour post links.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_relate_get_by',
        'label'       => esc_attr__('Related posts by', 'upside-lite'),
        'description' => '',
        'default'     => 'post_tag',
        'choices'     => array(
            'category' => esc_attr__('Category', 'upside-lite'),
            'post_tag' => esc_attr__('Tag', 'upside-lite'),
        ),
        'type'        => 'select',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_relate_limit',
        'label'       => esc_attr__('Number of related post', 'upside-lite'),
        'description' => esc_attr__('Enter 0 to not allow showing related posts.', 'upside-lite'),
        'default'     => '6',
        'type'        => 'text',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_relate_excerpt',
        'label'       => esc_attr__('Related posts excerpt lenght', 'upside-lite'),
        'description' => esc_attr__('Number of word (E.g: 18)', 'upside-lite'),
        'default'     => '18',
        'type'        => 'text',
        'section'     => 'upside_section_custom_single_post',
        'transport'   => 'refresh');

    #Follow social
    $socials = upside_lite_get_socials();
    if ( $socials ) {
        foreach ( $socials as $v ) {
            $options['settings'][] = array(
                'settings'    => 'upside_lite_social_share_' . esc_attr($v['id']),
                'label'       => esc_html( $v['title'] ),
                'description' => esc_html__('URL of your ', 'upside-lite') . esc_html($v['title']),
                'default'     => '',
                'type'        => 'text',
                'section'     => 'upside_section_follow_social',
            );
        }
    }

    #Single product
    $options['settings'][] = array(
        'settings'          => 'single_product_socials_status',
        'label'       => esc_attr__('Enable share via socials network', 'upside-lite'),
        'description' => esc_attr__('Check this option to display social network sharing.', 'upside-lite'),
        'default'     => '1',
        'type'        => 'checkbox',
        'section'     => 'upside_section_custom_single_product',
        'transport'   => 'refresh');

    $options['settings'][] = array(
        'settings'          => 'single_product_relate_number',
        'label'       => esc_attr__('Number of related products', 'upside-lite'),
        'description' => '',
        'default'     => '4',
        'type'        => 'text',
        'section'     => 'upside_section_custom_single_product',
        'transport'   => 'refresh');

    
    if ( class_exists('Upside_Lite_Toolkit_Course') ) {
        # Course archive
        $options['settings'][] = array(
            'settings'          => 'upside-course-posts_per-page',
            'label'       => esc_attr__('Course per page', 'upside-lite'),
            'description' => esc_attr__('Number of course per page.', 'upside-lite'),
            'default'     => '6',
            'type'        => 'text',
            'section'     => 'upside_section_custom_course_archive',
            'transport'   => 'refresh');

        $options['settings'][] = array(
            'settings'          => 'course_archive_thumb',
            'label'       => esc_attr__('Thumbnail', 'upside-lite'),
            'description' => esc_attr__('Show thumbnails image of the courses.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_archive',
            'transport'   => 'refresh');

        $options['settings'][] = array(
            'settings'          => 'course_archive_rate',
            'label'       => esc_attr__('Rating', 'upside-lite'),
            'description' => esc_attr__('Show rating of the courses.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_archive',
            'transport'   => 'refresh');

        $options['settings'][] = array(
            'settings'          => 'course_archive_hot',
            'label'       => esc_attr__('Hot labels', 'upside-lite'),
            'description' => esc_attr__('Show "hot label" for featured courses.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_archive',
            'transport'   => 'refresh');

        $options['settings'][] = array(
            'settings'          => 'course_archive_teacher',
            'label'       => esc_attr__('Instructor', 'upside-lite'),
            'description' => esc_attr__('Show instructor of the courses.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_archive',
            'transport'   => 'refresh');
        $options['settings'][] = array(
            'settings'          => 'course_archive_price',
            'label'       => esc_attr__('Price', 'upside-lite'),
            'description' => esc_attr__('Show price of the courses.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_archive',
            'transport'   => 'refresh');

        # Course single

        $options['settings'][] = array(
            'settings'          => 'course_single_thumbnail',
            'label'       => esc_attr__('Thumbnails', 'upside-lite'),
            'description' => esc_attr__('Show thubnails image.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_cat',
            'label'       => esc_attr__('Categories', 'upside-lite'),
            'description' => esc_attr__('Show course categories.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_author',
            'label'       => esc_attr__('About author', 'upside-lite'),
            'description' => esc_attr__('Show about author information.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_next_prev',
            'label'       => esc_attr__('Next & Previous', 'upside-lite'),
            'description' => esc_attr__('Show Next and Previous course links.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_relate_limit',
            'label'       => esc_attr__('Number of related courses', 'upside-lite'),
            'description' => esc_attr__('Enter 0 to not allow showing related course.', 'upside-lite'),
            'default'     => '2',
            'type'        => 'text',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh');

        $options['settings'][] = array(
            'settings'          => 'course_single_relate_excerpt',
            'label'       => esc_attr__('Excerpt lenght of related courses', 'upside-lite'),
			'description' => esc_attr__('Number of words (E.g: 18)', 'upside-lite'),
            'default'     => '18',
            'type'        => 'text',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh');

        $options['settings'][] = array(
            'settings'          => 'course_single_teacher',
            'label'       => esc_attr__('Instructor', 'upside-lite'),
            'description' => esc_attr__('Show insntructor of the course.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_meta',
            'label'       => esc_attr__('Course information', 'upside-lite'),
            'description' => esc_attr__('Show course start date, end date, place and contact information. ', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_price',
            'label'       => esc_attr__('Price', 'upside-lite'),
            'description' => esc_attr__('Show price of the course.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_join_btn',
            'label'       => esc_attr__('Join button', 'upside-lite'),
            'description' => esc_attr__('Show "Join Button". This button will be linked to product in Woocommerce.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'course_single_download_btn',
            'label'       => esc_attr__('Dowload button', 'upside-lite'),
            'description' => esc_attr__('Show "Download Document" button.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_course_single',
            'transport'   => 'refresh'
        );


    }

    if ( class_exists('Upside_Lite_Toolkit_Staff') ) {

        #Member archive
        $options['settings'][] = array(
            'settings'          => 'upside-staff-posts_per-page',
            'label'       => esc_attr__('Number staff per page', 'upside-lite'),
            'description' => '',
            'default'     => '6',
            'type'        => 'text',
            'section'     => 'upside_section_custom_member_archive',
            'transport'   => 'refresh');
        $options['settings'][] = array(
            'settings'          => 'member_archive_thumbnail',
            'label'       => esc_attr__('Show thumbnail', 'upside-lite'),
            'description' => esc_attr__('Check this option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_archive',
            'transport'   => 'refresh'
        );
        $options['settings'][] = array(
            'settings'          => 'member_archive_position',
            'label'       => esc_attr__('Show position', 'upside-lite'),
            'description' => esc_attr__('Check this option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_archive',
            'transport'   => 'refresh'
        );
        $options['settings'][] = array(
            'settings'          => 'member_archive_excerpt',
            'label'       => esc_attr__('Show excerpt', 'upside-lite'),
            'description' => esc_attr__('Check this option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_archive',
            'transport'   => 'refresh'
        );
        $options['settings'][] = array(
            'settings'          => 'member_archive_social',
            'label'       => esc_attr__('Show share socials', 'upside-lite'),
            'description' => esc_attr__('Check this option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_archive',
            'transport'   => 'refresh'
        );

        #Member single

        $options['settings'][] = array(
            'settings'          => 'member_single_thumbnail',
            'label'       => esc_attr__('Show thumbnail', 'upside-lite'),
            'description' => esc_attr__('Check this option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'member_single_position',
            'label'       => esc_attr__('Show position', 'upside-lite'),
            'description' => esc_attr__('Check this option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'member_single_excerpt',
            'label'       => esc_attr__('Show excerpt', 'upside-lite'),
            'description' => esc_attr__('Check position option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'member_single_share_socials',
            'label'       => esc_attr__('Show share socials', 'upside-lite'),
            'description' => esc_attr__('Check position option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_single',
            'transport'   => 'refresh'
        );

        $options['settings'][] = array(
            'settings'          => 'member_single_list_course',
            'label'       => esc_attr__('Show list courses', 'upside-lite'),
            'description' => esc_attr__('Check position option to display.', 'upside-lite'),
            'default'     => '1',
            'type'        => 'checkbox',
            'section'     => 'upside_section_custom_member_single',
            'transport'   => 'refresh'
        );

    }

    $options['settings'][] = array(
        'settings'          => 'error_image_bg',
        'label'       => esc_attr__('Image', 'upside-lite'),
        'default'     => '',
		 'description' => esc_attr__('Upload image for 404 error page.', 'upside-lite'),
        'type'        => 'image',
        'section'     => 'upside_section_custom_error_page',
        'transport'   => 'refresh');


    return $options;
}