<?php
global $post;
$post_url = get_permalink($post->ID);
$post_title = get_the_title($post->ID);
$port_socials = upside_lite_share_via_socials();
$port_show_share = esc_attr( get_theme_mod('single_product_socials_status', '1') );
if ( $port_socials && 1 == $port_show_share ) :
    ?>

<div class="social-box clearfix">
    <span class="pull-left"><?php esc_html_e('Share on:', 'upside-lite'); ?></span>
    <ul class="social-links clearfix pull-left">
        <?php
        foreach ( $port_socials as $k => $v ) {
            if ( 'instagram' == $k ) {
                continue;
            }
            $u_social_class = $v['class'];
            $u_social_text_hover = esc_attr__('Share via ', 'upside-lite');
            $u_social_text_hover .= $v['title'];
            switch ( $k ) {
                case 'facebook':
                    $u_social_link = sprintf('//www.facebook.com/share.php?u=%s', urlencode($post_url) );
                    break;
                case 'twitter':
                    $u_social_link = sprintf('//twitter.com/home?status=%s+%s', $post_title, $post_url);
                    break;
                case 'google':
                    $u_social_link = sprintf('//plus.google.com/share?url=%s', $post_url);
                    break;
                default:
                    $u_social_link = '';
                    break;
            }
            echo sprintf('<li><a href="%ss" title="%s" class="%s" rel="nofollow" target="_blank"></a></li>', esc_url($u_social_link), esc_attr($u_social_text_hover), esc_attr($v['class']));
        }
        ?>
    </ul>
    <!-- social-links -->
</div>


<?php endif; ?>
