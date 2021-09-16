<?php
global $post;
$upside_post_id = $post->ID;
?>
<span class="entry-date pull-left"><?php echo get_the_date(get_option('date_format'), $upside_post_id); ?></span>