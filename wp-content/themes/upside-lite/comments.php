<?php
// check if post is pwd protected
if ( post_password_required() ) {
    return;
}

if ( have_comments() && comments_open() ) { ?>
<div id="comments">
    <h4>
        <?php
        printf( _nx( '1 comment', '%s comments', get_comments_number(), 'comments title', 'upside-lite' ),
            number_format_i18n( get_comments_number() ) );
        ?>
    </h4>
    <ol class="comments-list clearfix">
        <?php
        wp_list_comments(array(
            'walker' => null,
            'style' => 'ul',
            'callback' => 'upside_lite_comments_callback',
            'end-callback' => null,
            'type' => 'all'
        ));
        ?>
    </ol>
    <?php
    $args = array(
        'prev_next'    => True,
        'prev_text' => '<i class="fa fa-angle-double-left"></i>',
        'next_text' => '<i class="fa fa-angle-double-right"></i>',
        'type'         => 'list',
    );
    ?>
    <div class="pagination kopa-comment-pagination"><?php paginate_comments_links($args); ?></div>
</div>
<?php } elseif ( ! comments_open() && post_type_supports(get_post_type(), 'comments') ) {
    return;
} // endif
comment_form(upside_lite_comment_form_args());
