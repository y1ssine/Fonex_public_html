<?php
if ( !class_exists( 'bbPress' ) ) {
    return;
}

add_filter('bbp_no_breadcrumb', '__return_true');
add_filter('bbp_get_topic_subscribe_link', '__return_null');
add_filter('bbp_get_forum_subscribe_link', '__return_null');

add_filter('bbp_get_forum_class', 'upside_lite_bbp_forum_class');
add_filter('bbp_get_topic_class', 'upside_lite_bbp_topic_class');

add_filter('kopa_layout_manager_settings', 'upside_lite_add_layout_forums');
add_filter('kopa_layout_manager_settings', 'upside_lite_add_layout_forum');
add_filter('kopa_layout_manager_settings', 'upside_lite_add_layout_topics');
add_filter('kopa_layout_manager_settings', 'upside_lite_add_layout_topic');

add_filter('kopa_custom_template_setting_id', 'upside_lite_forums_set_setting_id');
add_filter('kopa_custom_template_setting', 'upside_lite_forums_get_setting', 10, 2);

add_filter('upside_get_breadcrumb', 'upside_lite_bbp_get_breadcrumb');

add_filter('upside_is_override_default_template', 'upside_lite_get_template');

add_action('upside_lite_load_template', 'upside_lite_load_template');

add_filter('bbp_get_forum_last_active', 'upside_lite_get_topic_last_active', 10, 2);

function upside_lite_bbp_get_breadcrumb($breadcrumb){
	
	if (is_post_type_archive('forum')
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
		|| bbp_is_search_results()){					
		
		ob_start();

        ?>

    <div itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="" class="kopa-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $page_title = bbp_title('', '');
                    if ( ! empty($page_title) ) :
                        ?>
                        <div class="pull-left"><span><?php echo esc_html($page_title);?></span></div>
                        <?php endif; ?>
                    <div class="pull-right">
                        <a title="<?php esc_attr_e('Return to Home', 'upside-lite'); ?>" href="<?php echo esc_url(home_url('/')); ?>" itemprop="url">
                            <span itemprop="title"><?php esc_html_e('Home', 'upside-lite'); ?></span>
                        </a>
                        <span>&nbsp;/&nbsp;</span>
                        <?php echo bbp_title('', ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <?php
		$breadcrumb = ob_get_clean();

	}	

	return $breadcrumb;
}

function upside_lite_bbp_forum_class($classes){
	array_push($classes, 'bbp_forum_class', 'upside_forum_class');

	return $classes;
}

function upside_lite_bbp_topic_class($classes){
	array_push($classes, 'clearfix');

	return $classes;	
}

function upside_lite_add_layout_forums($options){
	$positions        = upside_lite_get_positions();
	$sidebars_default = upside_lite_get_sidebars();

	$layout = array(
		'title'     => esc_attr__( 'Forums', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
		'positions' => array(
			'sb_right',
			'sb_before_footer',
            'sb_footer_1'   => 'sb_footer_1',
            'sb_footer_2'   => 'sb_footer_2',
            'sb_footer_3'   => 'sb_footer_3',
            'sb_footer_4'   => 'sb_footer_4',
            'sb_copyright'   => 'sb_copyright'
		)
	);

	$options[] = array(
		'title'   => esc_attr__( 'Forums',  'upside-lite' ),
		'type' 	  => 'title',
		'id' 	  => 'forums',
	);

	$options[] = array(
		'title'     =>  esc_attr__( 'Forums',  'upside-lite' ),
		'type'      => 'layout_manager',
		'id'        => 'forums',
		'positions' => $positions,
		'layouts'   => array(
			'forums' => $layout			
		),
		'default' => array(
			'layout_id' => 'forums',
			'sidebars'  => array(
				'forums' => $sidebars_default 				
			),
		),
	);

	return $options;
}

function upside_lite_add_layout_forum($options){
	$positions        = upside_lite_get_positions();
	$sidebars_default = upside_lite_get_sidebars();

	$layout = array(
		'title'     => esc_attr__( 'Forum', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
		'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1'   => 'sb_footer_1',
            'sb_footer_2'   => 'sb_footer_2',
            'sb_footer_3'   => 'sb_footer_3',
            'sb_footer_4'   => 'sb_footer_4',
            'sb_copyright'   => 'sb_copyright'
		)
	);

	$options[] = array(
		'title'   => esc_attr__( 'Forum',  'upside-lite' ),
		'type' 	  => 'title',
		'id' 	  => 'forum',
	);

	$options[] = array(
		'title'     =>  esc_attr__( 'Forum',  'upside-lite' ),
		'type'      => 'layout_manager',
		'id'        => 'forum',
		'positions' => $positions,
		'layouts'   => array(
			'forum' => $layout			
		),
		'default' => array(
			'layout_id' => 'forum',
			'sidebars'  => array(
				'forum' => $sidebars_default 				
			),
		),
	);

	return $options;
}

function upside_lite_add_layout_topics($options){
	$positions        = upside_lite_get_positions();
	$sidebars_default = upside_lite_get_sidebars();

	$layout = array(
		'title'     => esc_attr__( 'Topics', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
		'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1'   => 'sb_footer_1',
            'sb_footer_2'   => 'sb_footer_2',
            'sb_footer_3'   => 'sb_footer_3',
            'sb_footer_4'   => 'sb_footer_4',
            'sb_copyright'   => 'sb_copyright'
		)
	);

	$options[] = array(
		'title'   => esc_attr__( 'Topics',  'upside-lite' ),
		'type' 	  => 'title',
		'id' 	  => 'topics',
	);

	$options[] = array(
		'title'     =>  esc_attr__( 'Topics',  'upside-lite' ),
		'type'      => 'layout_manager',
		'id'        => 'topics',
		'positions' => $positions,
		'layouts'   => array(
			'topics' => $layout			
		),
		'default' => array(
			'layout_id' => 'topics',
			'sidebars'  => array(
				'topics' => $sidebars_default 				
			),
		),
	);

	return $options;
}

function upside_lite_add_layout_topic($options){
	$positions        = upside_lite_get_positions();
	$sidebars_default = upside_lite_get_sidebars();

	$layout = array(
		'title'     => esc_attr__( 'Topic', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
		'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1'   => 'sb_footer_1',
            'sb_footer_2'   => 'sb_footer_2',
            'sb_footer_3'   => 'sb_footer_3',
            'sb_footer_4'   => 'sb_footer_4',
            'sb_copyright'   => 'sb_copyright'
		)
	);

	$options[] = array(
		'title'   => esc_attr__( 'Topic',  'upside-lite' ),
		'type' 	  => 'title',
		'id' 	  => 'topic',
	);

	$options[] = array(
		'title'     =>  esc_attr__( 'Topic',  'upside-lite' ),
		'type'      => 'layout_manager',
		'id'        => 'topic',
		'positions' => $positions,
		'layouts'   => array(
			'topic' => $layout			
		),
		'default' => array(
			'layout_id' => 'topic',
			'sidebars'  => array(
				'topic' => $sidebars_default 				
			),
		),
	);

	return $options;
}

function upside_lite_forums_set_setting_id($setting_id){	

	if (is_post_type_archive('forum') || bbp_is_forum_archive()) {
		$setting_id = 'forums';
	}elseif(is_post_type_archive('topic') || bbp_is_topic_tag() || bbp_is_topic_tag_edit()){
		$setting_id = 'topics';
	}elseif(bbp_is_single_forum()){
		$setting_id = 'forum';
	}elseif(bbp_is_single_topic()
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
		|| bbp_is_search_results()
        || is_bbpress()
		){					
		$setting_id = 'topic';
	}	

	return $setting_id;
}

function upside_lite_forums_get_setting($setting, $setting_id){
	if(empty($setting)){
		$layouts = array();

		if('forums' == $setting_id){
			$layouts = upside_lite_add_layout_forums(array());
		}elseif('topics' == $setting_id){
			$layouts = upside_lite_add_layout_topics(array());
		}elseif(is_singular('forum')){
			$layouts = upside_lite_add_layout_forum(array());
		}elseif(bbp_is_single_topic()
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
			|| bbp_is_search_results()){
			$layouts = upside_lite_add_layout_topic(array());
		}

		if(isset($layouts[1]['default'])){
			$setting = $layouts[1]['default'];
		}
	}	

	return $setting;
}

function upside_lite_get_template($override) {
    if ( is_bbpress() ){
        $override = true;
        return $override;
    }
    return false;
}

function upside_lite_load_template(){
    if ( is_bbpress() ){
        $layout = upside_lite_get_template_setting();
        if ( $layout ){
            $current_layout = $layout['layout_id'];
            get_template_part( '/template/archive/archive', $current_layout );
        }
    }
}

function upside_lite_get_topic_last_active($active_time, $forum_id) {
    $forum_id    = bbp_get_forum_id( $forum_id );
    $last_active = get_post_meta( $forum_id, '_bbp_last_active_time', true );

    if ( empty( $last_active ) ) {
        $reply_id = bbp_get_forum_last_reply_id( $forum_id );
        if ( !empty( $reply_id ) ) {
            $last_active = get_post_field( 'post_date', $reply_id );
        } else {
            $topic_id = bbp_get_forum_last_topic_id( $forum_id );
            if ( !empty( $topic_id ) ) {
                $last_active = bbp_get_topic_last_active_time( $topic_id );
            }
        }
    }
    return date('H:i, d M Y', strtotime($last_active));
}
