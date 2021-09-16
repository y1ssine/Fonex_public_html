<?php
if ( comments_open() ) {
    echo '<span class="entry-comment pull-left">';
    comments_popup_link( esc_attr__('No comments yet', 'upside-lite'), '1 ' . esc_attr__('comment', 'upside-lite'), '% ' . esc_attr__('comments', 'upside-lite'), 'entry-comments', esc_attr__('Comments are off for this post', 'upside-lite'));
    echo '</span>';
}
?>