<?php
global $post;
$upside_post_id = $post->ID;
?>
<a href="<?php echo esc_url(get_permalink($upside_post_id)); ?>" class="more-link clearfix" title="<?php echo esc_attr(get_the_title($upside_post_id)); ?>"><span class="pull-left"><?php esc_html_e('Read more', 'upside-lite'); ?></span><i class="fa fa-angle-right pull-left"></i></a>
