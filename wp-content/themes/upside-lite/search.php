<?php
get_header();
if( $upside_lite_current_layout = upside_lite_get_template_setting() ){
    if ( isset($_GET['type']) && 'k_course' == $_GET['type'] ) {
        get_template_part( 'template/archive/archive-search-course');
    } else {
        get_template_part( 'template/archive/archive-' . $upside_lite_current_layout['layout_id']);
    }
} else {
    get_template_part( 'template/archive/archive-search-page');
}
get_footer();