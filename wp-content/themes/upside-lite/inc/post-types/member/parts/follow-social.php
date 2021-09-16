<?php
global $post;
$post_id = $post->ID;
if ( isset($overide_obj) ) {
    $post_id = $overide_obj->ID;
}
$utp_socials = upside_lite_get_profile_socials();
$utp_socials = apply_filters('utp_member_social_custom', $utp_socials);
$utp_socials_value = array();
if ( $utp_socials ) {
    foreach ( $utp_socials as $k => $v ) {
        $filled_value = get_post_meta($post_id, 'utp-k-member-social-'.$k, 'true');
        if ( ! empty($filled_value) ) {
            $v['value'] = $filled_value;
            $utp_socials_value[] = $v;
        }
    }
}
if ( $utp_socials_value ) :
?>
    <ul class="social-links clearfix">
        <?php
            foreach ( $utp_socials_value as $k => $v ) {
                echo sprintf('<li><a class="%s" href="%s" rel="nofollow"></a></li>', esc_attr($v['class']), esc_url($v['value']));
            }
        ?>
    </ul>
<?php endif;