<?php
/*
* --------------------------------------------------
* Make video shortcode responsive
* --------------------------------------------------
*/
function upside_lite_make_video_shortcode_responsive($html){
    if (!empty($html)) {
        $out = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);
        $out = preg_replace('/(width|height)="\d*"\s/', "", $out);
        return $out;
    }

    return $html;
}

/*
* --------------------------------------------------
* Custom youtube settings
* --------------------------------------------------
*/
function upside_lite_youtube_settings($code){
    if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
        return preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&autohide=1&showinfo=0&hd=1&rel=0&theme=light&controls=2", $code);
    }

    return $code;
}

/*
* --------------------------------------------------
* Get icons for euducation
* --------------------------------------------------
*/
function upside_lite_get_icons_for_education() {
    $icons = array(
        "fa fa-twitter"              => "twitter",
        "fa fa-facebook"             => "facebook",
        "fa fa-google-plus"          => "google plus",
        "fa fa-linkedin"             => "linkedin",
        "fa fa-pinterest"            => "pinterest",
    );
    return apply_filters('upside_lite_get_icons_for_education', $icons);
}

/*
* --------------------------------------------------
* Enable/disable add advanced field to meta box
* --------------------------------------------------
*/
function upside_lite_custom_metabox_advanced_field() {
    return true;
}

/*
* --------------------------------------------------
* Enable/disable load flaticon font
* --------------------------------------------------
*/
function upside_lite_enable_register_flaticon_font() {
    return true;
}

/*
* --------------------------------------------------
* Load admin style
* --------------------------------------------------
*/
function upside_lite_load_custom_wp_admin_style() {
    wp_enqueue_style( 'upside_wp_admin_css', get_template_directory_uri() . '/css/admin-style.css', false, '1.0.0' );
}

/*
* --------------------------------------------------
* Assign default when title empty
* --------------------------------------------------
*/
function upside_lite_get_the_title($title) {
    if ( empty($title) ) {
        $title = esc_attr__('Untitle', 'upside-lite');
    }
    return $title;
}

/*
* --------------------------------------------------
* Custom excerpt length
* --------------------------------------------------
*/
function upside_lite_get_excerpt_length( $excerpt_length ) {
    if ( ! isset($excerpt_length) || empty($excerpt_length) ) {
        $excerpt_length = 55;
    }
    $GLOBALS['upside_excerpt_length'] = (int) $excerpt_length;
}

/*
* --------------------------------------------------
* Set excerpt length
* --------------------------------------------------
*/
function upside_lite_set_excerpt_length($length) {
    $length = $GLOBALS['upside_excerpt_length'];
    return $length;
}

/*
* --------------------------------------------------
* Remove some attributes of iframe in embbed content
* For validate w3c
* --------------------------------------------------
*/
function upside_lite_remove_oembed_attributes( $html, $url, $attr, $post_ID ) {
    $return = str_replace('frameborder="no"', 'style="border: none"', $html);
    $return = str_replace('frameborder="0"', 'style="border: none"', $return);
    $return = str_replace('allowfullscreen', '', $return);
    $return = str_replace('scrolling="no"', '', $return);
    $return = str_replace('webkit', '', $return);
    $return = str_replace('moz', '', $return);
    $post_curr_format = get_post_format( $post_ID );
    if ( is_archive() && 'video' != $post_curr_format ) {
        $pattern = '/(width|height)="[0-9]*"/i';
        $return = preg_replace($pattern, "", $return);
    } elseif ( ! is_single() ) {
        $pattern = '/height="[0-9]*"/i';
        $return = preg_replace($pattern, "height=470", $return);
    } elseif ( 'audio' == $post_curr_format ) {
        $pattern = '/(width|height)="[0-9]*"/i';
        $return = preg_replace($pattern, "", $return);
    }

    return $return;
}

/*
* --------------------------------------------------
* Edit class of reply link
* --------------------------------------------------
*/
function upside_lite_edit_reply_link( $html, $args, $comment, $post ) {
    $return = str_replace('comment-reply-link', 'comment-reply-link pull-left', $html);
    return $return;
}

/*
* --------------------------------------------------
* Edit class of comment link
* --------------------------------------------------
*/
function upside_lite_edit_comment_link( $link, $comment_ID, $text ) {
    $return = str_replace('comment-edit-link', 'comment-edit-link pull-left', $link);
    return $return;
}

/*
* --------------------------------------------------
 * Custom WP_LINK_PAGES for post content
 * @param $args
 * @return array|string
* --------------------------------------------------
*/
function upside_lite_wp_link_pages_args_add_prevnext($args)
{
    global $page, $numpages, $more;

    if (!$args['next_or_number'] == 'next_and_number')
        return $args;

    $args['next_or_number'] = 'number';
    if (!$more)
        return $args;

    if( $page-1) { # there is a previous page
        $args['before'] .= _wp_link_page($page-1)
            . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
        ;
        $args['before'] = $args['before'];
    }

    if ( $page<$numpages ) { # there is a next page
        $args['after'] = _wp_link_page($page+1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after']
        ;
        $args['after'] = $args['after'];
    }
    return $args;
}

/*
* --------------------------------------------------
 * Add current class to WP_LINK_PAGES
 * @param $link
 * @return string
* --------------------------------------------------
*/
function upside_lite_custom_link_pages( $link ) {
    if ( ctype_digit( $link ) ) {
        return '<span class="current">' . $link . '</span>';
    }else{
        return $link;
    }
    return $link;
}

/*
* --------------------------------------------------
* Get current layout information
* --------------------------------------------------
*/
function upside_lite_get_template_setting($default = null) {
    if(function_exists('kopa_get_template_setting')){
        return kopa_get_template_setting();
    }
    return $default;
}

/*
* --------------------------------------------------
* Get default profile socials
* --------------------------------------------------
*/
function upside_lite_get_profile_socials() {
    $socials = array(
        'facebook' => array(
            'class' => 'fa fa-facebook',
            'title' => esc_attr__('Facebook', 'upside-lite')
        ),
        'twitter' => array(
            'class' => 'fa fa-twitter',
            'title' => esc_attr__('Twitter', 'upside-lite')
        ),
        'google' => array(
            'class' => 'fa fa-google-plus',
            'title' => esc_attr__('Google plus', 'upside-lite')
        ),
        'instagram' => array(
            'class' => 'fa fa-instagram',
            'title' => esc_attr__('Instagram', 'upside-lite')
        ),
    );
    return apply_filters('upside_filter_socials', $socials);
}