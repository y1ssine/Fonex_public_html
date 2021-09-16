<?php
add_filter( 'kopa_layout_manager_settings', 'upside_lite_register_layouts');
function upside_lite_get_positions(){
    $u_positions = array(
        'sb_right'         => esc_attr__( 'RIGHT', 'upside-lite'),
        'sb_before_footer' => esc_attr__( 'BOTTOM', 'upside-lite'),
        'sb_bottom_left'   => esc_attr__( 'BOTTOM LEFT', 'upside-lite'),
        'sb_bottom_right'  => esc_attr__( 'BOTTOM RIGHT', 'upside-lite'),
        'sb_footer_1'      => esc_attr__( 'FOOTER 1', 'upside-lite'),
        'sb_footer_2'      => esc_attr__( 'FOOTER 2', 'upside-lite'),
        'sb_footer_3'      => esc_attr__( 'FOOTER 3', 'upside-lite'),
        'sb_footer_4'      => esc_attr__( 'FOOTER 4', 'upside-lite'),
        'sb_copyright'     => esc_attr__( 'COPYRIGHT', 'upside-lite'),
        'sb_content'       => esc_attr__( 'COURSE CONTENT', 'upside-lite')
    );

	return apply_filters('upside_lite_get_positions', $u_positions);
}

function upside_lite_get_sidebars(){
    $upside_sidebars = array(
        'sb_right'         => 'sb_right',
        'sb_before_footer' => 'sb_before_footer',
        'sb_footer_1'      => 'sb_footer_1',
        'sb_footer_2'      => 'sb_footer_2',
        'sb_footer_3'      => 'sb_footer_3',
        'sb_footer_4'      => 'sb_footer_4',
        'sb_copyright'     => 'sb_copyright',
        'sb_content'       => 'sb_content',
    );

	return apply_filters('upside_lite_get_sidebars', $upside_sidebars);
}

function upside_lite_register_layouts( $options ) {
	$positions = upside_lite_get_positions();
	$sidebars = upside_lite_get_sidebars();

	#1: Archive
	$blog_1 = array(
		'title'     => esc_attr__( 'Blog style 1', 'upside-lite' ),
		'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
		'positions' => array(					
			'sb_right',
			'sb_before_footer',
			'sb_footer_1',
			'sb_footer_2',
			'sb_footer_3',
			'sb_footer_4',
			'sb_copyright'
			));

	$options['blog-layout']['positions'] = $positions;
	$options['blog-layout']['layouts'] = array(		
		'blog-style1' => $blog_1
    );

	$options['blog-layout']['default'] = array(
		'layout_id' => 'blog-style1',
		'sidebars'  => array(			
			'blog-style1' => $sidebars
        ));

    $blog_2 = array(
        'title'     => esc_attr__( 'Blog style 2', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
        'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        )
    );
    $options['blog-layout']['layouts']['blog-style2'] = $blog_2;
    $options['blog-layout']['default']['sidebars']['blog-style2'] = $sidebars;

	#2: Single
	$single_post = array(
		'title'     => esc_attr__( 'Single post', 'upside-lite' ),
		'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
		'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        ));

	$options['post-layout']['positions'] = $positions;
	$options['post-layout']['layouts'] = array(
		'single-post'     => $single_post,
    );

	$options['post-layout']['default'] = array(
		'layout_id' => 'single-post',
		'sidebars'  => array(
			'single-post'     => $sidebars,
            'single-gallery'     =>$sidebars
        ));

    #4: Static Page
    $static_page = array(
        'title'     => esc_attr__('Static page', 'upside-lite'),
        'preview'   => get_template_directory_uri() . '/images/layouts/static-page.png',
        'positions' => array(
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        )
    );

    $page_layout = array(
        'static-page' => $static_page,
    );

    $page_layout_default = array(
        'static-page' => $sidebars,
    );

    $options['page-layout']['positions'] = $positions;
    $options['page-layout']['layouts'] = $page_layout;

    $options['page-layout']['default'] = array(
        'layout_id' => 'static-page',
        'sidebars'  => $page_layout_default
    );

    $options['frontpage-layout']['positions'] = $positions;
    $options['frontpage-layout']['layouts'] = $page_layout;

    $options['frontpage-layout']['default'] = array(
        'layout_id' => 'static-page',
        'sidebars'  => $page_layout_default
    );

    #5: Search Page
    $search = array(
        'title'     => esc_attr__( 'Search layout', 'upside-lite' ),
        'preview'   => get_template_directory_uri() . '/images/layouts/blog.png',
        'positions' => array(
            'sb_right',
            'sb_before_footer',
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        )
    );
    $options['search-layout']['positions'] = $positions;
    $options['search-layout']['layouts'] = array(
        'search-page' => $search,
    );

    $options['search-layout']['default'] = array(
		'layout_id' => 'search-page',
		'sidebars'  => array(
            'search-page' => $sidebars,
            )
		);

	#6: Error 404
	$error_404 = array(
		'title'     => esc_attr__('Error page - 404', 'upside-lite'),
		'preview'   => get_template_directory_uri() . '/images/layouts/error-404.png',
		'positions' => array(
            'sb_footer_1',
            'sb_footer_2',
            'sb_footer_3',
            'sb_footer_4',
            'sb_copyright'
        ));

    $options['error404-layout']['positions'] = $positions;
    $options['error404-layout']['layouts'] = array(
        'error-404' => $error_404);

    $options['error404-layout']['default'] = array(
		'layout_id' => 'error-404',
		'sidebars'  => array(
            'error-404' => $sidebars
        ));

	return apply_filters('upside_lite_custom_layouts', $options);
}