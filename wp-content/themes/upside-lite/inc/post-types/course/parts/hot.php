<?php
global $post;
$is_hot = get_post_meta($post->ID, 'utp-course-is-featured', true);
if ( 1 == $is_hot ):
?>
    <span class="entry-hot"><?php esc_html_e('Hot', 'upside-lite'); ?></span>
<?php endif;