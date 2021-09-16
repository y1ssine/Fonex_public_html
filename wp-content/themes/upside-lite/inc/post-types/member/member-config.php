<?php
#LAYOUT
add_filter( 'kopa_layout_manager_settings', 'upside_lite_member_register_layouts');
add_filter('kopa_custom_template_setting_id', 'upside_lite_member_get_layout_setting', 10, 2);
add_filter('kopa_custom_layout_arguments', 'upside_lite_member_edit_custom_layout_feature');

function upside_lite_member_register_layouts( $options ) {
    $positions = upside_lite_get_positions();
    $sidebars = upside_lite_get_sidebars();

    #8: MEMBER
    $layout_1 = array(
        'title'     => esc_attr__( 'Member 3 columns', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/blog.png',
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
        'title'   => esc_attr__( 'Member Archive', 'upside-lite' ),
        'type' 	  => 'title',
        'id' 	  => 'member-archive-title'
    );

    $options['member-archive'] = array(
        'title'     =>  esc_attr__( 'Member Archive',  'upside-lite' ),
        'type'      => 'layout_manager',
        'id'        => 'member-archive',
        'positions' => $positions,
        'layouts'   => array(
            'member-3-col' => $layout_1,
        ),
        'default' => array(
            'layout_id' => 'member-archive',
            'sidebars'  => array(
                'member-3-col' => $sidebars,
            ),
        ),
    );

    $layout = array(
        'title'     => esc_attr__( 'Member Single', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/inc/post-types/layout-images/portfolio-single.png',
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
        'title'   => esc_attr__( 'Member Single', 'upside-lite' ),
        'type' 	  => 'title',
        'id' 	  => 'member-single'
    );

    $options['member-single'] = array(
        'title'     =>  esc_attr__( 'Member Single',  'upside-lite' ),
        'type'      => 'layout_manager',
        'id'        => 'member-single',
        'positions' => $positions,
        'layouts'   => array(
            'member-single' => $layout
        ),
        'default' => array(
            'layout_id' => 'member-single',
            'sidebars'  => array(
                'member-single' => $sidebars
            ),
        ),
    );

    return $options;
}

function upside_lite_member_get_layout_setting($setting_id){
    if(is_singular('k_member')){
        $setting_id = 'member-single';
    }elseif (is_post_type_archive('k_member') || is_tax('k-department')) {
        $setting_id = 'member-archive';
    }
    return $setting_id;
}

function upside_lite_member_edit_custom_layout_feature( $args ) {
    $args[] = array(
        'screen'   => 'k_member',
        'taxonomy' => true,
        'layout'   => 'member-single',
    );

    $args[] = array(
        'screen'   => 'k-department',
        'taxonomy' => true,
        'layout'   => 'member-archive',
    );

    return $args;
}






