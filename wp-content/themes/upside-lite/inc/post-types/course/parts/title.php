<?php
global $post;
$post_temp = $post;
if ( isset($overide_obj) ) { // check if override value
    $post_temp = $overide_obj;
}

if ( $post_temp && ! empty($post_temp->post_title) ) {
    echo sprintf('<h6 class="entry-title"><a href="%s" title="%s">%s</a></h6>', esc_url(get_permalink($post_temp)), esc_attr($post_temp->post_title), esc_html($post_temp->post_title));
}