<?php
add_filter( 'kopa_sidebar_default', 'upside_lite_set_sidebar_default' );
function upside_lite_set_sidebar_default( $options ) {
    $options = upside_lite_set_sidebars();
    return $options;
}

add_filter( 'kopa_sidebar_default_attributes', 'upside_lite_set_sidebar_default_attributes' );
function upside_lite_set_sidebar_default_attributes($wrap) {
    $wrap['before_widget'] = '<div id="%1$s" class="widget %2$s">';
    $wrap['after_widget']  = '</div>';
    $wrap['before_title']  = '<h3 class="widget-title">';
    $wrap['after_title']   = '</h3>';

    return $wrap;
}

function upside_lite_set_sidebars() {
    $sidebar = array(
        'sb_right' => array('name' => esc_attr__( 'Blog right', 'upside-lite')),
        'sb_footer_1' => array('name' => esc_attr__( 'Footer 1', 'upside-lite')),
        'sb_footer_2' => array('name' => esc_attr__( 'Footer 2', 'upside-lite')),
        'sb_footer_3' => array('name' => esc_attr__( 'Footer 3', 'upside-lite')),
        'sb_footer_4' => array('name' => esc_attr__( 'Footer 4', 'upside-lite')),
        'sb_copyright' => array('name' => esc_attr__( 'Copyright', 'upside-lite')),
        'sb_before_footer' => array('name' => esc_attr__( 'Blog bottom', 'upside-lite')),
    );

    $sidebar['sb_content'] = array('name' => esc_attr__( 'Course Content', 'upside-lite'));
    return $sidebar;
}

add_action('widgets_init', 'upside_lite_register_fixed_sidebar');
function upside_lite_register_fixed_sidebar(){
    if ( ! class_exists('Kopa_Framework') ) {
        $wrap = upside_lite_set_sidebar_default_attributes(array());
        $fixed_sidebars  = upside_lite_set_sidebars();

        foreach($fixed_sidebars as $id => $value){
            $args         = $wrap;
            $args['id']   = $id;
            $args['name'] = $value['name'];

            register_sidebar($args);
        }
    }
}

function upside_lite_apply_sidebar_params_blog($params){
    $params[0]['before_title'] = '<h4 class="widget-title widget-title-s10">';
    $params[0]['after_title']  = '</h4>';
    return $params;
}

add_filter('upside_lite_get_sidebar_by_position', 'upside_lite_set_sidebar_by_position', 10, 2);
function upside_lite_set_sidebar_by_position( $sidebar, $position ) {
    $setting = upside_lite_get_template_setting();
    if ( isset($setting['sidebars'][$position]) ) {
        $sidebar = $setting['sidebars'][$position];
    }
    return $sidebar;
}