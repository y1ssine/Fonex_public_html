<?php
    global $post;
    $upc_teacher = get_post_meta($post->ID, 'utp-course-instructors', true);
    if ( $upc_teacher ) {
        if ( ! ( 1 == count($upc_teacher) && empty($upc_teacher[0]) ) ) {
?>
        <div class="course-teacher">
            <span><?php esc_html_e('Teaching', 'upside-lite'); ?></span>
            <?php esc_html_e('by ', 'upside-lite'); ?>
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
        </div>
<?php
        }
    }