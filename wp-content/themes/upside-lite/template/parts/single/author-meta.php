<?php
$upside_post_author_name = get_the_author_meta( 'display_name' );
$upside_post_author_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
$upside_email = get_the_author_meta('user_email');
$upside_description = get_the_author_meta('description');
?>
<div class="about-author">
    <div class="author-avatar pull-left"><a href="<?php echo esc_url($upside_post_author_link); ?>"><?php echo get_avatar($upside_email, 93); ?></a></div>
    <div class="author-content">
        <h5><a href="<?php echo esc_url($upside_post_author_link); ?>"><?php echo esc_html($upside_post_author_name); ?></a></h5>
        <?php if ( ! empty($upside_description) ) : ?>
            <p><?php echo esc_textarea($upside_description); ?></p>
        <?php endif; ?>

        <?php do_action('upside_lite_add_profile_share_follow'); ?>
    </div>
</div>
<!-- about-author -->
