<?php
/**
 * enqueue css, js for theme
 * @package  upside
 * @version 1.0.0
 * @return null
 */
function upside_lite_enqueue_scripts(){
    $dir = get_template_directory_uri();
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'upside-lite' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Source Sans Pro:400,300,300italic,400italic,600,600italic,700italic,700&subset=latin,latin-ext|Raleway:400,300,500,600,700,800' ), "//fonts.googleapis.com/css" );
        wp_enqueue_style( 'upside-fonts', $font_url, array(), '1.0.0' );
    }
    wp_enqueue_script('jquery-form');
    wp_enqueue_script('jquery-ui-core ');
    wp_enqueue_script('jquery-ui-spinner');
    wp_enqueue_script('jquery-ui-progressbar');
    wp_enqueue_script('jquery-effects-core');

    if (is_singular()) { wp_enqueue_script('comment-reply'); }

    if(wp_style_is('kopa_font_awesome')){
        wp_enqueue_style('kopa_font_awesome');
    }else{
        wp_enqueue_style('font-awesome', get_template_directory_uri() . "/css/font-awesome.css", array(), NULL);
    }

    wp_enqueue_script('maps-api', 'http://maps.google.com/maps/api/js?key=AIzaSyBJCPvlXhlu-dH1aNYqzqZphNQ-HtAhT9Q&v=3', NULL, NULL, TRUE);

    /** Include Style files */
    wp_enqueue_style('bootstrap', "{$dir}/css/bootstrap.css", array(), NULL);
    wp_enqueue_style('superfish', "{$dir}/css/superfish.css", array(), NULL);
    wp_enqueue_style('owl-carousel', "{$dir}/css/owl.carousel.css", array(), NULL);
    wp_enqueue_style('owl-theme', "{$dir}/css/owl.theme.css", array(), NULL);
    wp_enqueue_style('jquery-navgoco', "{$dir}/css/jquery.navgoco.css", array(), NULL);
    wp_enqueue_style('jquery-ui', "{$dir}/css/jquery.ui.css", array(), NULL);
    wp_enqueue_style('magnific-popup', "{$dir}/css/magnific.popup.css", array(), NULL);
    wp_enqueue_style('upside-lite-main-style', get_stylesheet_uri(), array(), NULL);
    wp_enqueue_style('upside-responsive', "{$dir}/css/responsive.css", array(), NULL);

    /** Include Script files  */
    wp_enqueue_script( "bootstrap", "{$dir}/js/bootstrap.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "gmaps", "{$dir}/js/gmaps.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "imagesloaded", "{$dir}/js/imagesloaded.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "imgliquid", "{$dir}/js/imgliquid.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jflickrfeed", "{$dir}/js/jflickrfeed.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jquery-fitvids", "{$dir}/js/jquery.fitvids.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jquery-magnific-popup", "{$dir}/js/jquery.magnific.popup.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jquery-match-height", "{$dir}/js/jquery.match.height.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jquery-navgoco", "{$dir}/js/jquery.navgoco.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jquery-validate", "{$dir}/js/jquery.validate.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "jquery-wookmark", "{$dir}/js/jquery.wookmark.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "masonry-pkgd", "{$dir}/js/masonry.pkgd.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "modernizr", "{$dir}/js/modernizr.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "owl-carousel", "{$dir}/js/owl.carousel.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "visible", "{$dir}/js/visible.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "probars", "{$dir}/js/probars.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "superclick", "{$dir}/js/superclick.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "superfish", "{$dir}/js/superfish.js", array('jquery'), NULL, TRUE );
    wp_enqueue_script( "upside-lite-custom", "{$dir}/js/custom.js", array('jquery'), NULL, TRUE );

    wp_localize_script('upside-lite-custom', 'kopa_variable', array(
        'url' => array(
            'template_directory_uri' => get_template_directory_uri() . '/',
            'ajax' => admin_url('admin-ajax.php')
        ),
        'template' => array(
            'post_id' => (is_singular()) ? get_queried_object_id() : 0
        ),
        'validate' => array(
            'name' => array(
                'required' => esc_attr__('Please enter your name.', 'upside-lite'),
                'minlength' => esc_attr__('At least {0} characters required.', 'upside-lite')
            ),
            'email' => array(
                'required' => esc_attr__('Please enter your email.', 'upside-lite'),
                'email' => esc_attr__('Please enter a valid email.', 'upside-lite')
            ),
            'message' => array(
                'required' => esc_attr__('Please enter a message.', 'upside-lite'),
                'minlength' => esc_attr__('At least {0} characters required.', 'upside-lite')
            ),
            'sending' => esc_attr__('Sending...', 'upside-lite'),
            'submit' => esc_attr__('Submit now', 'upside-lite'),

        ),
        'event' => array(
            'day_str' => esc_attr__('days', 'upside-lite'),
            'hour_str' => esc_attr__('hours', 'upside-lite'),
            'min_str' => esc_attr__('mins', 'upside-lite'),
            'min_sec' => esc_attr__('secs', 'upside-lite'),
        )
    ));
}

/**
 * Custom body class
 * @param $classes
 * @return array
 */
function upside_lite_set_body_class($classes){
    if( $upside_current_layout = upside_lite_get_template_setting() ){
        array_push($classes,  'upside-layout-'.$upside_current_layout['layout_id']);
        switch ($upside_current_layout['layout_id']) {
            case 'blog-style2':
                array_push($classes,  'kopa-blog-page-2');
                break;
            case 'portfolio-archive-3':
                array_push($classes,  'kopa-portfolio-4col');
                break;
            case 'portfolio-archive-2':
                array_push($classes,  'kopa-portfolio-3col');
                break;
            case 'portfolio-archive-1':
                array_push($classes,  'kopa-portfolio-2col');
                break;
            case 'course-archive-full-width-grid-four-col':
            case 'course-archive-full-width-grid-three-col':
                array_push($classes,  'kopa-course-list-1');
                break;
            default:
                break;
        }
    }
	return $classes;
}


/**
 * Get title for page
 * @return mixed|void
 */
function upside_lite_get_page_title() {
    $enable_custom = apply_filters('mat_custom_title_for_home', 1);
    $page_title = '';
    if ( class_exists('bbPress') &&
        ( is_post_type_archive('forum')
        || is_post_type_archive('topic')
        || bbp_is_forum_archive()
        || bbp_is_topic_tag()
        || bbp_is_topic_tag_edit()
        || bbp_is_single_forum()
        || bbp_is_single_topic()
        || bbp_is_single_reply()
        || bbp_is_topic_edit()
        || bbp_is_topic_merge()
        || bbp_is_topic_split()
        || bbp_is_reply_edit()
        || bbp_is_reply_move()
        || bbp_is_single_view()
        || bbp_is_single_user()
        || bbp_is_user_home()
        || bbp_is_user_home_edit()
        || bbp_is_topics_created()
        || bbp_is_replies_created()
        || bbp_is_favorites()
        || bbp_is_subscriptions()
        || bbp_is_search()
        || bbp_is_search_results() ) ) {
        $page_title = bbp_title('', '');
    }
    elseif ( is_home() ) {
        if ( $enable_custom ) {
            $page_title = esc_attr__('Blog page', 'upside-lite');
        } else {
            $page_title = get_bloginfo('title');
        }
    } elseif (is_archive()) {
        if ( is_tag() || is_category() || is_tax() ) {
            $term = get_queried_object();
            $page_title = $term->name;
        } else if (is_year() || is_month() || is_day()) {
            $page_title = get_the_archive_title('');
        } else if (is_author()) {
            $author_id = get_queried_object_id();
            $page_title = get_the_author_meta('display_name', $author_id);
        } elseif ( is_post_type_archive('portfolio') ) {
            $page_title = esc_attr__('Portfolios', 'upside-lite');
        } elseif ( is_post_type_archive('k_course') ) {
            if ( isset($_GET['post_type']) && 'k_course' == $_GET['post_type'] && isset( $_GET['s'] ) ) {
                $page_title = esc_attr__('Search Courses', 'upside-lite');
            } else {
                $page_title = esc_attr__('Recent Courses', 'upside-lite');
            }
        }
        if ( is_post_type_archive('product') ) {
            $page_title = esc_attr__('Shop', 'upside-lite');
        } elseif ( is_post_type_archive('k_member') ) {
            $page_title = esc_attr__('Professor', 'upside-lite');
        } elseif ( is_post_type_archive('k_member') ) {
            $page_title = esc_attr__('Professor', 'upside-lite');
        }
    } else if ( is_search() ) {
        $s = get_search_query();
        $page_title = sprintf(esc_attr__('Search Results for: %s', 'upside-lite'), $s);
    } else if (is_singular()) {
        global $post;
        $title = get_the_title($post->ID);
        $custom_title = get_post_meta($post->ID, 'upside-page-title', true);
        if ($custom_title) {
            $title = $custom_title;
        }
        $page_title = $title;
    } else if (is_404()) {
        $page_title = esc_attr__('Page not found...', 'upside-lite');
    }

    return apply_filters('upside_get_page_title', $page_title);
}


/**
 * Get description for page
 * @return mixed|void
 */
function upside_lite_get_page_descritpion() {
    $enable_custom = apply_filters('mat_custom_description_for_home', 1);
    $page_description = '';
    if ( is_home() ) {
        if ( $enable_custom ) {
            $page_description = esc_attr__('why our clients love to work with us.', 'upside-lite');
        } else {
            $page_description = get_bloginfo('description');
        }
    } elseif (is_archive()) {
        if (is_tag() || is_category() || is_tax()) {
            $term = get_queried_object();
            $page_description = $term->description;
        }
    } elseif (is_singular()) {
        global $post;
        $description = '';
        $custom_description = get_post_meta($post->ID, 'upside-page-description', true);

        if ($custom_description) {
            $description = $custom_description;
        }
        $page_description = $description;
    }

    return apply_filters('upside_get_page_description', $page_description);
}

/**
 * Get image by image id
 * @param $img_id
 * @param string $size
 * @return string
 */
function upside_lite_get_image_by_id($img_id, $size='full'){
    $thumb = wp_get_attachment_image($img_id,$size);
    if (!empty($thumb)) {
        $_thumb = array();
        $regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
        preg_match($regex, $thumb, $_thumb);
        $thumb = $_thumb[2];
    }
    return $thumb;
}

/**
 * Get image by post id
 * @param int $post_id
 * @param string $size
 * @return string
 */
function upside_lite_get_image_by_post_id($post_id = 0, $size = 'full') {
    $thumb = get_the_post_thumbnail($post_id, $size);
    if (!empty($thumb)) {
        $_thumb = array();
        $regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
        preg_match($regex, $thumb, $_thumb);
        $thumb = $_thumb[2];
    } else {
        $thumb = '';
    }
    return $thumb;
}

/**
 * Custom class form
 * @param $class
 * @return string
 */
function upside_lite_custom_form_class_attr( $class ) {
    $class .= ' comment-form clearfix';
    return $class;
}

/*
 * Comments call back function
 */
function upside_lite_comments_callback($comment, $args, $depth) {

    $GLOBALS['comment'] = $comment;

    if ( 'pingback' == get_comment_type() || 'trackback' == get_comment_type() ) { ?>

    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment clearfix' ); ?>>
        <article class="comment-wrap clearfix">
            <div class="comment-avatar pull-left">
                <?php echo get_avatar( $comment->comment_author_email, 50 ); ?>
            </div>
            <div class="comment-body">
                <div class="comment-content">
                    <?php echo get_comment_author_link(); ?>
                </div>

                <footer class="clearfix">
                    <div class="pull-left">
                        <h6><?php echo get_comment_type(); ?></h6>
                    </div>
                    <div class="pull-right clearfix">
                        <span class="entry-date pull-left"><?php comment_date(get_option('date_format') ); ?> <?php esc_html_e('At', 'upside-lite'); ?> <?php comment_date(get_option('time_format') ); ?></span>
                        <div class="comment-button pull-left clearfix">
                            <?php
                            comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                            if ( current_user_can( 'moderate_comments' ) ) {
                                echo '<span class="pull-left">&nbsp;/&nbsp;</span>';
                                edit_comment_link( esc_attr__( 'Edit', 'upside-lite' ) );
                            }
                            ?>
                        </div>
                    </div>
                </footer>
            </div><!--comment-body -->
        </article>

    <?php } elseif ( 'comment' == get_comment_type() ) { ?>

    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment clearfix' ); ?>>
        <article class="comment-wrap clearfix">
            <div class="comment-avatar pull-left">
                <?php echo get_avatar( $comment->comment_author_email, 50 ); ?>
            </div>
            <div class="comment-body">
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <footer class="clearfix">
                    <div class="pull-left">
                        <h6><?php echo get_comment_author(); ?></h6>
                    </div>
                    <div class="pull-right clearfix">
                        <span class="entry-date pull-left"><?php comment_date(get_option('date_format') ); ?> <?php esc_html_e('At', 'upside-lite'); ?> <?php comment_date(get_option('time_format') ); ?></span>
                        <div class="comment-button pull-left clearfix">
                            <?php
                            comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                            if ( current_user_can( 'moderate_comments' ) ) {
                                if ( $depth < $args['max_depth'] ) {
                                    echo '<span class="pull-left">&nbsp;/&nbsp;</span>';
                                }
                                edit_comment_link( esc_attr__( 'Edit', 'upside-lite' ) );
                            }
                            ?>
                        </div>
                    </div>
                </footer>
            </div><!--comment-body -->
        </article>

    <?php
    } // endif check comment type
}

/**
 * @return array
 */
function upside_lite_comment_form_args() {
    $form_name = esc_attr__('Name *', 'upside-lite');
    $form_email = esc_attr__('Email *', 'upside-lite');
    $form_web = esc_attr__('Website', 'upside-lite');
    $form_comment = esc_attr__('Message *', 'upside-lite');
    $fields = array(
        'author' => '<div class="row">
                        <div class="col-md-4 col-sm-4">
                            <p class="input-block">
                                <label class="required" for="comment_name"><i class="fa fa-user"></i></label>
                                <input type="text" class="valid" name="author" id="comment_name" value="" placeholder="' . esc_attr($form_name) . '">
                            </p>
                        </div>',
        'email' => '<div class="col-md-4 col-sm-4">
                        <p class="input-block">
                            <label class="required" for="comment_email"><i class="fa fa-envelope"></i></label>
                            <input type="text" class="valid" name="email" id="comment_email" value="" placeholder="' . esc_attr($form_email) . '">
                        </p>
                    </div>',
        'url' => '<div class="col-md-4 col-sm-4">
                    <p class="input-block">
                        <label class="required" for="comment_url"><i class="fa fa-globe"></i></label>
                        <input type="text" id="comment_url" value="" class="valid" name="url" placeholder="' . esc_attr($form_web) . '">
                    </p>
                  </div>
                 </div>'

    );

    $comment_field = '
        <div class="row">
            <div class="col-md-12">
                <p class="textarea-block">
                    <label class="required" for="comment_message"><i class="fa fa-list-ul"></i></label>
                    <textarea rows="6" cols="88" id="comment_message" name="comment" style="overflow:auto;resize:vertical ;" placeholder="' . esc_attr($form_comment) . '"></textarea>
                </p>
            </div>
        </div>
           ';

    $args = array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => $comment_field,
        'submit_field' => '<div class="row"><div class="col-md-12"><p class="form-submit comment-button clearfix">%1$s %2$s</div></div>',
        'comment_notes_before' => '',
        'comment_notes_after' => '',
        'id_form' => 'comments-form',
        'id_submit' => 'submit-comment',
        'comment_notes_before' => '<p class="c-note">' . esc_attr__( 'Your email address will not be published. Required fields are marked.', 'upside-lite' ) . '<span>*</span></p>',
        'title_reply' => esc_attr__('Leave a comment', 'upside-lite'),
        'label_submit' =>esc_attr__('Post Comment', 'upside-lite'),
    );

    return $args;
}

/**
 * @param $items
 * @return mixed
 */
function upside_lite_add_menu_parent_flip_back_class( $items ) {
    $parents = array();
    foreach ( $items as $item ) {
        if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
            $parents[] = $item->menu_item_parent;
        }
    }
    foreach ( $items as $item ) {
        if ( in_array( $item->ID, $parents ) ) {
            $item->classes[] = 'flip-back';
        }
    }

    return $items;
}

/**
 * @return mixed|void
 */
function upside_lite_get_search_param() {
    $search_params = array(
        array(
            'title'        => esc_attr__('Course title', 'upside-lite'),
            'element-type' => 'text',
            'data'         => 'default',
            'id'           => 's',
            'backend_title'        => esc_attr__('Course title', 'upside-lite'),
        ),
        array(
            'title'        => esc_attr__('Course ID', 'upside-lite'),
            'element-type' => 'text',
            'data'         => 'metabox',
            'id'           => 'utp-course-id',
            'backend_title'        => esc_attr__('Course ID', 'upside-lite'),
        ),
        array(
            'title'        => esc_attr__('-- All categories --', 'upside-lite'),
            'element-type' => 'select',
            'data'         => 'taxonomy',
            'id'           => 'course-category-slug',
            'data-source'  => 'course-category',
            'backend_title'        => esc_attr__('Course category', 'upside-lite'),
        ),
        array(
            'title'        => esc_attr__('-- All instructors --', 'upside-lite'),
            'element-type' => 'select',
            'data'         => 'metabox',
            'data-source'  => 'k_member',
            'id'           => 'utp-course-instructors',
            'backend_title'        => esc_attr__('Course instructors', 'upside-lite'),
        ),
    );
    return apply_filters('upside_custom_search_params', $search_params);
}

/**
 *
 * @return mixed|void
 */
function upside_lite_share_via_socials() {
    $socials = array(
        'facebook' => array(
            'class' => 'fa fa-facebook',
            'title' => esc_attr__('Facebook', 'upside-lite')
        ),
        'twitter' => array(
            'class' => 'fa fa-twitter',
            'title' => esc_attr__('Twitter', 'upside-lite')
        ),
        'google' => array(
            'class' => 'fa fa-google-plus',
            'title' => esc_attr__('Google plus', 'upside-lite')
        ),
        'instagram' => array(
            'class' => 'fa fa-instagram',
            'title' => esc_attr__('Instagram', 'upside-lite')
        ),
    );
    return apply_filters('upside_filter_portfolio_via_socials', $socials);
}

/*
* --------------------------------------------------
* Get allowed tags for wp_kses
* --------------------------------------------------
*/
function upside_lite_get_allowed_tags() {
    $allowed_tag = wp_kses_allowed_html( 'post' );

    $allowed_tag['div']['data-place']         = array();
    $allowed_tag['div']['data-latitude']      = array();
    $allowed_tag['div']['data-longitude']     = array();

    $allowed_tag['iframe']['src']             = array();
    $allowed_tag['iframe']['height']          = array();
    $allowed_tag['iframe']['width']           = array();
    $allowed_tag['iframe']['frameborder']     = array();
    $allowed_tag['iframe']['allowfullscreen'] = array();

    $allowed_tag['input']['class']            = array();
    $allowed_tag['input']['id']               = array();
    $allowed_tag['input']['name']             = array();
    $allowed_tag['input']['value']            = array();
    $allowed_tag['input']['type']             = array();
    $allowed_tag['input']['checked']          = array();

    $allowed_tag['select']['class']           = array();
    $allowed_tag['select']['id']              = array();
    $allowed_tag['select']['name']            = array();
    $allowed_tag['select']['value']           = array();
    $allowed_tag['select']['type']            = array();

    $allowed_tag['option']['selected']        = array();

    $allowed_tag['style']['types']            = array();

    $microdata_tags = array( 'div', 'section', 'article', 'a', 'span', 'img', 'time', 'figure' );
    foreach ( $microdata_tags as $tag ) {
        $allowed_tag[ $tag ]['itemscope'] = array();
        $allowed_tag[ $tag ]['itemtype']  = array();
        $allowed_tag[ $tag ]['itemprop']  = array();
    }

    return apply_filters( 'upside_store_get_allowed_tags', $allowed_tag );
}

/**
 * Register plugins for TGM
 */
function upside_lite_register_required_plugins(){
    $plugins = array(
        array(
            'name' => esc_attr__( 'Kopa Framework', 'upside-lite' ),
            'slug' => 'kopatheme',
            'required' => false,
            'force_activation' => false,
            'force_deactivation' => false,
        ),
        array(
            'name' => esc_attr__ ( 'Upside Lite Toolkit', 'upside-lite' ),
            'slug' => 'upside-lite-toolkit',
            'required' => false,
            'force_activation' => false,
            'force_deactivation' => false,
        ),
        array(
            'name' => esc_attr__( 'Kopa Page Builder', 'upside-lite' ),
            'slug' => 'kopa-page-builder',
            'required' => false,
            'force_activation' => false,
            'force_deactivation' => false,
        ),
        array(
            'name'     => esc_attr__( 'WooCommerce', 'upside-lite' ),
            'slug'     => 'woocommerce',
            'required' => false
        ),
        array(
            'name'     => esc_attr__( 'Contact Form 7', 'upside-lite' ),
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
    );

    $config = array(
        'has_notices'  => true,
        'is_automatic' => false
    );

    tgmpa($plugins, $config);
}

/**                  
 * Get social follow
 * @return mixed|void
 */
function upside_lite_get_socials() {
    $socials = array(
        array(
            'title' => esc_html__( 'Twitter', 'upside-lite' ),
            'id'    => 'twitter',
            'icon'  => 'fa fa-twitter',
        ),
        array(
            'title' => esc_html__( 'Facebook', 'upside-lite' ),
            'id'    => 'facebook',
            'icon'  => 'fa fa-facebook',
        ),
        array(
            'title' => esc_html__( 'Google plus', 'upside-lite' ),
            'id'    => 'google',
            'icon'  => 'fa fa-google-plus',
        ),
        array(
            'title' => esc_html__( 'Linkedin', 'upside-lite' ),
            'id'    => 'linkedin',
            'icon'  => 'fa fa-linkedin',
        ),
        array(
            'title' => esc_html__( 'Pinterest', 'upside-lite' ),
            'id'    => 'pinterest',
            'icon'  => 'fa fa-pinterest',
        ),

    );

    return apply_filters( 'upside_lite_custom_socials_default', $socials );
}

/**
 * Get selected follow social in theme customize
 * @return array
 */
function upside_lite_get_selected_follow_social() {
    $socials = upside_lite_get_socials();
    $socials_data = array();
    if ( $socials ) {
        foreach ( $socials as $v ) {
            $curr_value = get_theme_mod( 'upside_lite_social_share_' . esc_attr( $v['id'] ),'' );
            if ( ! empty( $curr_value ) ) {
                $v['url'] = $curr_value;
                $socials_data[] = $v;
            }
        }
    }
    return $socials_data;
}