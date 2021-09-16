<?php
global $post;
$upside_fields = array(
    array(
        'field' => 'utp-course-date-start',
        'display' => 'text',
        'title' => 'Start',
        'icon' => ''
    ),
    array(
        'field' => 'utp-course-date-end',
        'display' => 'text',
        'title' => 'End',
        'icon' => ''
    ),
    array(
        'field' => 'utp-course-address',
        'display' => 'icon',
        'title' => '',
        'icon' => 'fa fa-map-marker'
    ),
    array(
        'field' => 'utp-course-phone',
        'display' => 'icon',
        'title' => '',
        'icon' => 'fa fa-phone'
    ),
    array(
        'field' => 'utp-course-email',
        'display' => 'icon',
        'title' => '',
        'icon' => 'fa fa-envelope'
    )
);
$upside_fields = apply_filters('upside_course_single_metadata', $upside_fields);
if ( $upside_fields ) :
?>
<div class="event-detail">
    <ul class="clearfix">
        <?php
            foreach ( $upside_fields as $field ) {
                $field_label = '';
                $field_value = '';
                if ( 'text' == $field['display'] ) {
                    $field_label = '<strong>' . $field['title'] . '</strong>';
                } elseif ( 'icon' == $field['display'] ) {
                    $field_label = '<i class="' . $field['icon'] . '"></i>';
                } else {
                    $field_label = apply_filters('upside_course_single_custom_label_display', '', $field['display'], $field['title'], $field['icon']);
                }
                $field_value = get_post_meta($post->ID, $field['field'], true);
                if ( ! empty($field_value) ) {
                    if ( 'utp-course-email' == $field['field'] ) {
                        echo sprintf('<li>%s<span><a href="mailto:%s">%s</a></span></li>', wp_kses_post($field_label), wp_kses_post($field_value), wp_kses_post($field_value));
                    } else {
                        echo sprintf('<li>%s<span>%s</span></li>', wp_kses_post($field_label), wp_kses_post($field_value));
                    }
                }
            }
        ?>
    </ul>

</div>
<!-- event-detail -->
<?php endif;