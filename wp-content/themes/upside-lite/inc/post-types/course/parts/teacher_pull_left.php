<?php
    global $post;
    $post_temp = $post;
    if ( isset($overide_obj) ) { // check if override value
        $post_temp = $overide_obj;
    }

    $upc_teacher = get_post_meta($post_temp->ID, 'utp-course-instructors', true);
    if ( $upc_teacher ) {
        if ( ! ( 1 == count($upc_teacher) && empty($upc_teacher[0]) ) ) {
?>
        <span class="entry-author pull-left">
            <?php
                $upt_teacher = array();
                foreach ( $upc_teacher as $id ) {
                    $post_teacher = get_post($id);
                    if ( $post_teacher ) {
                        $upt_teacher[] =  sprintf('<a href="%s" title="%s">%s</a>', esc_url(get_permalink($id)), esc_attr($post_teacher->post_title), esc_html($post_teacher->post_title));
                    }
                }
                echo implode(', ', $upt_teacher);
            ?>
        </span>
<?php
        }
    }