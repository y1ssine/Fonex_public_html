<?php

#LAYOUT
add_filter( 'kopa_layout_manager_settings', 'upside_lite_course_register_layouts');
add_filter('kopa_custom_template_setting_id', 'upside_lite_course_get_layout_setting', 10, 2);
add_filter('kopa_custom_layout_arguments', 'upside_lite_course_edit_custom_layout_feature');

function upside_lite_course_register_layouts( $options ) {
    $positions = upside_lite_get_positions();
    $sidebars = upside_lite_get_sidebars();

    #8: COURSE
    $layout_1 = array(
        'title'     => esc_attr__( 'Course full-width grid 4 columns', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/inc/post-types/layout-images/course-1.png',
        'positions' => array(
            'sb_before_footer',
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        ));

    $options[] = array(
        'title'   => esc_attr__( 'Course Archive', 'upside-lite' ),
        'type' 	  => 'title',
        'id' 	  => 'course-archive-title'
    );

    $options['course-archive'] = array(
        'title'     =>  esc_attr__( 'Course Archive',  'upside-lite' ),
        'type'      => 'layout_manager',
        'id'        => 'course-archive',
        'positions' => $positions,
        'layouts'   => array(
            'course-archive-full-width-grid-four-col' => $layout_1,
        ),
        'default' => array(
            'layout_id' => 'course-archive-full-width-grid-four-col',
            'sidebars'  => array(
                'course-archive-full-width-grid-four-col' => $sidebars,
            )
        ),
    );

    $layout_course_single = array(
        'title'     => esc_attr__( 'Course Single', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/inc/post-types/layout-images/course-2.png',
        'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        ));

    $options[] = array(
        'title'   => esc_attr__( 'Course Single', 'upside-lite' ),
        'type' 	  => 'title',
        'id' 	  => 'course-single'
    );

    $options['course-single'] = array(
        'title'     =>  esc_attr__( 'Course Single',  'upside-lite' ),
        'type'      => 'layout_manager',
        'id'        => 'course-single',
        'positions' => $positions,
        'layouts'   => array(
            'course-single' => $layout_course_single
        ),
        'default' => array(
            'layout_id' => 'course-single',
            'sidebars'  => array(
                'course-single' => $sidebars
            ),
        ),
    );

    return $options;
}

function upside_lite_course_get_layout_setting($setting_id){
    if(is_singular('k_course')){
        $setting_id = 'course-single';
    }elseif (is_post_type_archive('k_course') || is_tax('course-category')) {
        $setting_id = 'course-archive';
    }
    return $setting_id;
}

function upside_lite_course_edit_custom_layout_feature( $args ) {
    $args[] = array(
        'screen'   => 'k_course',
        'taxonomy' => true,
        'layout'   => 'course-single',
    );

    $args[] = array(
        'screen'   => 'course-category',
        'taxonomy' => true,
        'layout'   => 'course-archive',
    );

    return $args;
}








