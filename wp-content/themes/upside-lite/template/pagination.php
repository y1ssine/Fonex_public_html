<?php
echo '<div class="pagination clearfix">';
the_posts_pagination( array(
    'prev_text'          => '<i class="fa fa-angle-double-left"></i>',
    'next_text'          => '<i class="fa fa-angle-double-right"></i>',
    'screen_reader_text' => '',
    'type'      => 'list',
) );
echo '</div>';