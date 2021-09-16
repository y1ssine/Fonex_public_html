<?php
global $post;
$upside_sb_right   = apply_filters('materitix_get_sidebar', 'sb_right', 'pos_right');
global $upside_current_layout;
if ( $upside_current_layout ) {
    $mat_sidebar = $upside_current_layout['sidebars'];
    if ( isset($mat_sidebar['sb_right'] ) ) {
        $upside_sb_right = $mat_sidebar['sb_right'];
    }
}
if ( is_active_sidebar($upside_sb_right) ) {
    $image_info = 'upside-blog';
} else {
    $image_info = 'upside-blog-full';
}
if( $gallery = get_post_meta(get_the_ID(), 'matteritix_gallery', true) ):
    $ids = explode(',', $gallery);
    if(!empty($ids)){
        $slides = array();
        foreach($ids as $id){
            $thumb_caption = wp_get_attachment_image($id,'full');
            $image_caption = '';
            if (!empty($thumb_caption)) {
                $_thumb = array();
                $regex = '#<\s*img [^\>]*alt\s*=\s*(["\'])(.*?)\1#im';
                preg_match($regex, $thumb_caption, $_thumb);
                $image_caption = $_thumb[2];
            }

            if($image = wp_get_attachment_image_src( $id, $image_info )){
                $slides[] = sprintf('<div class="item"><a href="#" title="%s"><img src="%s" alt="%s"></a></div>', esc_attr($image_caption), esc_url($image[0]), esc_attr($image_caption));
            }

        }
        if(!empty($slides)){
            ?>
            <?php if ( is_single() ) : ?>
                <div class="entry-box"><div class="entry-content">
            <?php endif; ?>

                <div class="entry-thumb">
                    <?php
                        if ( is_sticky() && ! is_singular() ) {
                            echo '<span class="sticky-post-icon"><i class="fa fa-flash"></i></span>';
                        }
                    ?>
                        <div class="owl-carousel owl-carousel-6">
                            <?php echo implode('', $slides); ?>
                        </div>
                    <?php ?>

                    <?php if ( ! is_single() ) : ?>
                        <div class="mask"></div>
                    <?php endif; ?>

                </div>
                <!-- entry-thumb -->

            <?php if ( is_single() ) : ?>
                </div></div>
            <?php endif; ?>

        <?php
        }
    }
endif;

