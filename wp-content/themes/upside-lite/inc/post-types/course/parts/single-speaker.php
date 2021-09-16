<?php
    global $post;
    $utp_meta_key = 'utp-course-instructors';
    if ( is_singular('event') ) {
        $utp_meta_key = 'utp-event-speakers';
    }
    $upc_teacher = get_post_meta($post->ID, $utp_meta_key, true);
    if ( $upc_teacher ) {
        if ( ! ( 1 == count($upc_teacher) && empty($upc_teacher[0]) ) ) {
            ?>

            <div class="event-speaker">
                <h6><?php esc_html_e('Speakers', 'upside-lite'); ?></h6>
                <ul class="clearfix">
                    <?php
                    foreach ( $upc_teacher as $id ) {
                        $post_teacher = get_post($id);
                        if ( $post_teacher ) {
                            $t_position = get_post_meta($id, 'utp-member-position', true);
                            ?>

                            <li class="clearfix">
                                <?php if ( has_post_thumbnail($id) ) : ?>
                                    <div class="speaker-avatar pull-left">
                                        <a href="<?php echo esc_url(get_permalink($id)); ?>" title="<?php echo esc_attr($post_teacher->post_title); ?>">
                                            <?php upside_lite_the_post_thumbnail($id, 'upside-course-teacher-thumb-70-70'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="speaker-detail">
                                    <h6><a href="<?php echo esc_url(get_permalink($id)); ?>" title="<?php echo esc_attr($post_teacher->post_title); ?>"><?php echo esc_html($post_teacher->post_title); ?></a></h6>
                                    <?php if ( ! empty($t_position) ) : ?>
                                    <span><?php echo esc_html($t_position); ?></span>
                                    <?php endif; ?>
                                </div>
                            </li>

                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php
        }
    }
?>
