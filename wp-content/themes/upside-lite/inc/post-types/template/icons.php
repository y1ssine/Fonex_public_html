<?php
    global $post;
?>
<div class="click-box">
    <?php
    $port_show_link = esc_attr( get_theme_mod('portfolio_archive_icon_link', '1') );
    $port_show_popup = esc_attr( get_theme_mod('portfolio_archive_icon_popup', '1') );

    if ( 1 == $port_show_link ):
        ?>
        <a class="portfolio-link fa fa-link" href="<?php the_permalink(); ?>"></a>
    <?php endif; ?>

    <?php
    $img_src = upside_lite_get_image_by_post_id($post->ID, 'full');
    if ( ! empty($img_src) && 1 == $port_show_popup ):
        ?>
        <a class="portfolio-gallery fa fa-search popup-icon" href="<?php echo esc_url($img_src); ?>"></a>
        <?php endif; ?>
</div>