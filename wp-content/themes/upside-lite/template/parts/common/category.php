<?php
global $post;
$upside_post_id = $post->ID;

$upside_post_cats = get_the_category( $upside_post_id );
$upside_post_cats_html = array();
if ( $upside_post_cats ) {
    foreach ( $upside_post_cats as $category ) {
        $upside_post_cats_html[] = sprintf('<a href="%1$s" class="pull-left" title="%2$s" rel="category">%3$s</a>', esc_url(get_category_link($category)), esc_attr($category->name), esc_html($category->name));
    }

    echo '<span class="entry-categories pull-left clearfix">';
    echo implode('<span class="pull-left">,&nbsp;</span>', $upside_post_cats_html);
    echo '</span>';
}
