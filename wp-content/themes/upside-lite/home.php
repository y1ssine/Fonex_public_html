<?php
get_header();
if( $upside_lite_current_layout = upside_lite_get_template_setting() ){
    get_template_part( 'template/archive/archive', $upside_lite_current_layout['layout_id']);
} else {
    get_template_part( 'template/archive/archive');
}
get_footer();

