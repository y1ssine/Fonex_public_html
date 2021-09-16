<?php
get_header();
$upside_lite_current_layout = upside_lite_get_template_setting();
if ( $upside_lite_current_layout ) {
    $layout_id = $upside_lite_current_layout['layout_id'];
    get_template_part( 'template/'. $upside_lite_current_layout['layout_id']);
} else {
    get_template_part( 'template/error-404');
}
get_footer();