<?php
global $post;
$upside_post_id = $post->ID;

$upside_post_author_name = get_the_author_meta( 'display_name' );
$upside_post_author_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
?>
<p class="entry-author pull-left"><a href="<?php echo esc_url($upside_post_author_link);?>" title="<?php echo esc_attr($upside_post_author_name);?>"><?php echo esc_html($upside_post_author_name);?></a></p>
