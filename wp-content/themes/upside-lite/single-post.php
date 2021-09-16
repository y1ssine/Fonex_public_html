<?php
get_header();
$upside_lite_setting = upside_lite_get_template_setting();
if( $upside_lite_setting ){
    $layout_id = $upside_lite_setting['layout_id'];
    get_template_part( '/template/singular/' . $upside_lite_setting['layout_id']);
}else{
    get_template_part( '/template/singular/single');
}
get_footer();