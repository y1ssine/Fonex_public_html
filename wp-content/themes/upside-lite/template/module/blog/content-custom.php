<?php
global $post;
$upside_custom  = get_post_meta($post->ID, 'matteritix_custom', true);
$upside_current_format = get_post_format($post->ID);
?>

<?php if( $upside_custom): ?>
<div class="entry-thumb upside-content-custom">
    <?php
    if ( is_sticky() && ! is_singular() ) {
        echo '<span class="sticky-post-icon"><i class="fa fa-flash"></i></span>';
    }
    ?>
    <?php
        if ( 'video' == $upside_current_format ) {
            echo '<div class="video-wrapper">';
        }
    ?>
    <?php echo apply_filters('the_content', $upside_custom); ?>

    <?php
    if ( 'video' == $upside_current_format ) {
        echo '</div>';
    }
    ?>
</div>
<?php endif;
