<?php
global $post;
$upside_post_temp = $post;
if ( isset($overide_obj) ) { // check if override value
    $upside_post_temp = $overide_obj;
}

if ( $upside_post_temp ) {
    $upside_post_id = $upside_post_temp->ID;
    $upside_current_layout = upside_lite_get_template_setting();
    switch( $upside_current_layout['layout_id'] ) {
        case 'course-archive-full-width-grid-four-col':
        case 'course-archive-left-sidebar-grid-three-col':
            $upside_image_slug = 'upside-portfolio-relate';
            break;
        case 'course-archive-full-width-grid-three-col':
            $upside_image_slug = 'upside-course-thumb-350-260';
            break;
        case 'course-archive-left-sidebar-grid-two-col':
            $upside_image_slug = 'upside-course-thumb-175-175';
            break;
        case 'member-3-col':
        case 'event-full-2-col':
        $upside_image_slug = 'upside-member-list-255-255';
            break;
        case 'event-sidebar-2-col':
            $upside_image_slug = 'upside-event-list-398-230';
            break;
        default:
            $upside_image_slug = 'upside-portfolio-relate';
            break;
    } ?>
    <a href="<?php echo esc_url(get_permalink($upside_post_id)); ?>" title="<?php echo esc_attr(get_the_title($upside_post_id)); ?>">
        <?php
            if ( has_post_thumbnail($upside_post_id) ) {
                upside_lite_the_post_thumbnail($upside_post_id, $upside_image_slug);
            } else {
                upside_lite_the_default_thumbnail($upside_post_id, $upside_image_slug);
            }
        ?>
    </a>
<?php }