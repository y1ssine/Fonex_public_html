<?php


function itransform_customizer_config() {
	

    $url  = get_stylesheet_directory_uri() . '/inc/kirki/';
	
    /**
     * If you need to include Kirki in your theme,
     * then you may want to consider adding the translations here
     * using your textdomain.
     * 
     * If you're using Kirki as a plugin then you can remove these.
     */

    $strings = array(
        'background-color' => __( 'Background Color', 'i-transform' ),
        'background-image' => __( 'Background Image', 'i-transform' ),
        'no-repeat' => __( 'No Repeat', 'i-transform' ),
        'repeat-all' => __( 'Repeat All', 'i-transform' ),
        'repeat-x' => __( 'Repeat Horizontally', 'i-transform' ),
        'repeat-y' => __( 'Repeat Vertically', 'i-transform' ),
        'inherit' => __( 'Inherit', 'i-transform' ),
        'background-repeat' => __( 'Background Repeat', 'i-transform' ),
        'cover' => __( 'Cover', 'i-transform' ),
        'contain' => __( 'Contain', 'i-transform' ),
        'background-size' => __( 'Background Size', 'i-transform' ),
        'fixed' => __( 'Fixed', 'i-transform' ),
        'scroll' => __( 'Scroll', 'i-transform' ),
        'background-attachment' => __( 'Background Attachment', 'i-transform' ),
        'left-top' => __( 'Left Top', 'i-transform' ),
        'left-center' => __( 'Left Center', 'i-transform' ),
        'left-bottom' => __( 'Left Bottom', 'i-transform' ),
        'right-top' => __( 'Right Top', 'i-transform' ),
        'right-center' => __( 'Right Center', 'i-transform' ),
        'right-bottom' => __( 'Right Bottom', 'i-transform' ),
        'center-top' => __( 'Center Top', 'i-transform' ),
        'center-center' => __( 'Center Center', 'i-transform' ),
        'center-bottom' => __( 'Center Bottom', 'i-transform' ),
        'background-position' => __( 'Background Position', 'i-transform' ),
        'background-opacity' => __( 'Background Opacity', 'i-transform' ),
        'ON' => __( 'ON', 'i-transform' ),
        'OFF' => __( 'OFF', 'i-transform' ),
        'all' => __( 'All', 'i-transform' ),
        'cyrillic' => __( 'Cyrillic', 'i-transform' ),
        'cyrillic-ext' => __( 'Cyrillic Extended', 'i-transform' ),
        'devanagari' => __( 'Devanagari', 'i-transform' ),
        'greek' => __( 'Greek', 'i-transform' ),
        'greek-ext' => __( 'Greek Extended', 'i-transform' ),
        'khmer' => __( 'Khmer', 'i-transform' ),
        'latin' => __( 'Latin', 'i-transform' ),
        'latin-ext' => __( 'Latin Extended', 'i-transform' ),
        'vietnamese' => __( 'Vietnamese', 'i-transform' ),
        'serif' => _x( 'Serif', 'font style', 'i-transform' ),
        'sans-serif' => _x( 'Sans Serif', 'font style', 'i-transform' ),
        'monospace' => _x( 'Monospace', 'font style', 'i-transform' ),
    );	

	$args = array(
  
  	
        // Change the logo image. (URL) Point this to the path of the logo file in your theme directory
                // The developer recommends an image size of about 250 x 250
        'logo_image'   => get_template_directory_uri() . '/images/logo.png',
  		
		/*
        // The color of active menu items, help bullets etc.
        'color_active' => '#95c837',
		
		// Color used on slider controls and image selects
		'color_accent' => '#e7e7e7',
		
		// The generic background color
		//'color_back' => '#f7f7f7',
	
        // Color used for secondary elements and desable/inactive controls
        'color_light'  => '#e7e7e7',
  
        // Color used for button-set controls and other elements
        'color_select' => '#34495e',
		 */
        
        // For the parameter here, use the handle of your stylesheet you use in wp_enqueue
        'stylesheet_id' => 'customize-styles', 
		
        // Only use this if you are bundling the plugin with your theme (see above)
        'url_path'     => get_template_directory_uri() . '/inc/kirki/',

        'textdomain'   => 'i-transform',
		
        'i18n'         => $strings,		
		
		
	);
	
	
	return $args;
}
add_filter( 'kirki/config', 'itransform_customizer_config' );


/**
 * Create the customizer panels and sections
 */
add_action( 'customize_register', 'itransform_add_panels_and_sections' ); 
function itransform_add_panels_and_sections( $wp_customize ) {
	
	/*
	* Add panels
	*/
	
	$wp_customize->add_panel( 'slider', array(
		'priority'    => 140,
		'title'       => __( 'Slider', 'i-transform' ),
		'description' => __( 'Slides details', 'i-transform' ),
	) );	

    /**
     * Add Sections
     */
    $wp_customize->add_section('basic', array(
        'title'    => __('Basic Settings', 'i-transform'),
        'description' => '',
        'priority' => 130,
    ));
	
    $wp_customize->add_section('layout', array(
        'title'    => __('Layout Options', 'i-transform'),
        'description' => '',
        'priority' => 130,
    ));	
	
    $wp_customize->add_section('social', array(
        'title'    => __('Social Links', 'i-transform'),
        'description' => __('Insert full URL of your social link including http:// replacing #', 'i-transform'),
        'priority' => 130,
    ));		
	
    $wp_customize->add_section('blogpage', array(
        'title'    => __('Default Blog Page', 'i-transform'),
        'description' => '',
        'priority' => 150,
    ));	
	
	// slider sections
	
	$wp_customize->add_section('slidersettings', array(
        'title'    => __('Slide Settings', 'i-transform'),
        'description' => '',
        'panel' => 'slider',		
        'priority' => 140,
    ));		
	
	$wp_customize->add_section('slide1', array(
        'title'    => __('Slide 1', 'i-transform'),
        'description' => '',
        'panel' => 'slider',		
        'priority' => 140,
    ));	
	$wp_customize->add_section('slide2', array(
        'title'    => __('Slide 2', 'i-transform'),
        'description' => '',
        'panel' => 'slider',		
        'priority' => 140,
    ));	
	$wp_customize->add_section('slide3', array(
        'title'    => __('Slide 3', 'i-transform'),
        'description' => '',
        'panel' => 'slider',		
        'priority' => 140,
    ));	
	$wp_customize->add_section('slide4', array(
        'title'    => __('Slide 4', 'i-transform'),
        'description' => '',
        'panel' => 'slider',		
        'priority' => 140,
    ));	
	
	// WooCommerce Settings
    $wp_customize->add_section('woocomm', array(
        'title'    => __('WooCommerce', 'i-transform'),
        'description' => '',
        'priority' => 150,
    ));		
	
	// promo sections
	
	$wp_customize->add_section('nxpromo', array(
        'title'    => __('More About i-transform', 'i-transform'),
        'description' => '',
        'priority' => 170,
    ));				
	
}


function itransform_custom_setting( $controls ) {
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'top_phone',
        'label'    => __( 'Phone Number', 'i-transform' ),
        'section'  => 'basic',
        'default'  => of_get_option('top_bar_phone', '1-000-123-4567'),		
        'priority' => 1,
		'description' => __( 'Phone number that appears on top bar.', 'i-transform' ),
    );
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'top_email',
        'label'    => __( 'Email Address', 'i-transform' ),
        'section'  => 'basic',
        'default'  => sanitize_email(of_get_option('top_bar_email', 'email@i-create.com')),
        'priority' => 1,
		'description' => __( 'Email Id that appears on top bar.', 'i-transform' ),		
    );
	
	$controls[] = array(
		'type'        => 'upload',
		'setting'     => 'logo',
		'label'       => __( 'Site header logo', 'i-transform' ),
		'description' => __( 'Width 280px, height 72px max. Upload logo for header', 'i-transform' ),
        'section'  => 'basic',
        'default'  => of_get_option('itrans_logo_image', get_template_directory_uri() . '/images/logo.png'),		
		'priority'    => 1,
	);	
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'banner_text',
        'label'    => __( 'Banner Text', 'i-transform' ),
        'section'  => 'basic',
        'default'  => of_get_option('itrans_slogan', 'Banner Text Here'),
        'priority' => 1,
		'description' => __( 'if you are using a logo and want your site title or slogan to appear on the header banner', 'i-transform' ),		
    );	
	
	$controls[] = array(
		'type'        => 'color',
		'setting'     => 'primary_color',
		'label'       => __( 'Primary Color', 'i-transform' ),
		'description' => __( 'Choose your theme color', 'i-transform' ),
		'section'     => 'layout',
		'default'     => of_get_option('itrans_primary_color', '#3787be'),
		'priority'    => 1,
	);	
	
	$controls[] = array(
		'type'        => 'radio-image',
		'setting'     => 'blog_layout',
		'label'       => __( 'Blog Posts Layout', 'i-transform' ),
		'description' => __( '(Choose blog posts layout (one column/two column)', 'i-transform' ),
		'section'     => 'layout',
		'default'     => of_get_option('itrans_blog_layout', 'onecol'),
		'priority'    => 2,
		'choices'     => array(
			'onecol' => get_template_directory_uri() . '/images/onecol.png',
			'twocol' => get_template_directory_uri() . '/images/twocol.png',
		),
	);
	
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'full_content',
		'label'       => __( 'Show Full Content', 'i-transform' ),
		'description' => __( 'Show full content on blog pages', 'i-transform' ),
		'section'     => 'layout',
		'default'     => 1,		
		'priority'    => 3,
	);		
	
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'wide_layout',
		'label'       => __( 'Boxed Layout', 'i-transform' ),
		'description' => __( 'Turn ON/OFF boxed layout', 'i-transform' ),
		'section'     => 'layout',
		'default'     => of_get_option('boxed_type', 0),			
		'priority'    => 4,
	);
	
	$controls[] = array(
        'type'        => 'background',
        'settings'    => 'body_background',
        'label'       => __( 'Choose your body background', 'i-transform' ),
        'section'     => 'layout',
        'default'     => array(
            'color'    => 'rgba(224,224,224,1)',
            'image'    => '',
            'repeat'   => 'no-repeat',
            'size'     => 'cover',
            'attach'   => 'fixed',
            'position' => 'left-top',
        ),
        'priority'    => 4,
        'output'      => 'body',
        'required'  => array(
            array(
                'setting'  => 'wide_layout',
                'operator' => '==',
                'value'    => 1,
            ),
        )
    );	
	/*
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'sidebar_side',
		'label'       => __( 'Main Sidebar on left (default sidebar appears on right)', 'i-transform' ),
		'description' => __( 'move the main sidebar position to left', 'i-transform' ),
		'section'     => 'layout',
		'default'     => of_get_option('sidebar_side', 0),			
		'priority'    => 4,
	);	
	*/
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'itrans_extra_style',
		'label'       => __( 'Additional style', 'i-transform' ),
		'description' => __( 'add extra style(CSS) codes here', 'i-transform' ),
		'section'     => 'layout',
		'default'     => '',
		'default'     => of_get_option('itrans_extra_style', ''),		
		'priority'    => 10,
	);	
	
	/*
	$controls[] = array(
		'type'        => 'color',
		'setting'     => 'site_bg_color',
		'label'       => __( 'Background Color (Boxed Layout)', 'i-transform' ),
		'description' => __( 'Choose your background color', 'i-transform' ),
		'section'     => 'layout',
		'default'     => '#FFFFFF',
		'priority'    => 1,
	);
	*/	
	

	
	// social links
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_facebook',
        'label'    => __( 'Facebook', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),		
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_facebook', '#'),		
        'priority' => 1,
    );	
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_twitter',
        'label'    => __( 'Twitter', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),			
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_twitter', '#'),	
        'priority' => 1,
    );
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_flickr',
        'label'    => __( 'Flickr', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),			
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_flickr', '#'),	
        'priority' => 1,
    );	
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_feed',
        'label'    => __( 'RSS', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),			
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_feed', '#'),	
        'priority' => 1,
    );	
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_instagram',
        'label'    => __( 'Instagram', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),			
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_instagram', '#'),	
        'priority' => 1,
    );	
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_googleplus',
        'label'    => __( 'Google Plus', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),			
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_googleplus', '#'),	
        'priority' => 1,
    );	
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_social_youtube',
        'label'    => __( 'YouTube', 'i-transform' ),
		'description' => __( 'Empty the field to remove the icon', 'i-transform' ),			
        'section'  => 'social',
		'default'  => of_get_option('itrans_social_youtube', '#'),	
        'priority' => 1,
    );	
	
	// Slider

	$controls[] = array(
		'type'        => 'slider',
		'setting'     => 'itrans_sliderspeed',
		'label'       => __( 'Slide Duration', 'i-transform' ),
		'description' => __( 'Slide visibility in second', 'i-transform' ),
		'section'     => 'slidersettings',
		'default'     => 6,
		'priority'    => 1,
		'choices'     => array(
			'min'  => 1,
			'max'  => 30,
			'step' => 1
		),
	);
	$controls[] = array(
        'type'        => 'background',
        'settings'    => 'slider_background',
        'label'       => __( 'Choose a background', 'i-transform' ),
        'section'     => 'slidersettings',
        'default'     => array(
            'image'    => '',
            'repeat'   => 'no-repeat',
            'size'     => 'cover',
            'attach'   => 'fixed',
            'position' => 'left-top',
        ),
        'priority'    => 4,
        'output'      => '.ibanner',
    );		
	
	// Slide1
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide1_title',
        'label'    => __( 'Slide1 Title', 'i-transform' ),
        'section'  => 'slide1',
		'default'  => of_get_option('itrans_slide1_title', 'Multi-Purpose WP Theme'),			
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'itrans_slide1_desc',
		'label'       => __( 'Slide1 Description', 'i-transform' ),
		'section'     => 'slide1',
		'default'  => of_get_option('itrans_slide1_desc', 'To start setting up i-transform go to Appearance &gt; Customize. Make sure you have installed recommended plugin &rdquo;TemplatesNext Toolkit&rdquo; by going appearance &gt; install plugin.'),			
		'priority'    => 10,
	);
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide1_linktext',
        'label'    => __( 'Slide1 Link text', 'i-transform' ),
        'section'  => 'slide1',
		'default'  => of_get_option('itrans_slide1_linktext', 'Know More'),		
        'priority' => 1,
    );
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide1_linkurl',
        'label'    => __( 'Slide1 Link URL', 'i-transform' ),
        'section'  => 'slide1',
		'default'  => of_get_option('itrans_slide1_linkurl', 'http://templatesnext.org/itrans/'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'upload',
		'setting'     => 'itrans_slide1_image',
		'label'       => __( 'Slide1 Image', 'i-transform' ),
        'section'  	  => 'slide1',
		'default'  => of_get_option('itrans_slide1_image', get_template_directory_uri() . '/images/slide-1.jpg'),
		//'default'  => of_get_option('itrans_slide1_image'),			
		'priority'    => 1,
	);							
	
	
	// Slide2
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide2_title',
        'label'    => __( 'Slide2 Title', 'i-transform' ),
        'section'  => 'slide2',
		'default'  => of_get_option('itrans_slide2_title', 'Live Edit With Customizer'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'itrans_slide2_desc',
		'label'       => __( 'Slide2 Description', 'i-transform' ),
		'section'     => 'slide2',
		'default'  => of_get_option('itrans_slide2_desc', 'Setup your theme from Appearance &gt; Customize , boxed/wide layout, unlimited color, custom background, blog layout, social links, additiona css styling, phone number and email id, etc.'),		
		'priority'    => 10,
	);
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide2_linktext',
        'label'    => __( 'Slide2 Link text', 'i-transform' ),
        'section'  => 'slide2',
		'default'  => of_get_option('itrans_slide2_linktext', 'Know More'),		
        'priority' => 1,
    );
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide2_linkurl',
        'label'    => __( 'Slide2 Link URL', 'i-transform' ),
        'section'  => 'slide2',
		'default'  => of_get_option('itrans_slide2_linkurl', 'https://wordpress.org/'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'upload',
		'setting'     => 'itrans_slide2_image',
		'label'       => __( 'Slide2 Image', 'i-transform' ),
        'section'  	  => 'slide2',
		'default'  => of_get_option('itrans_slide2_image', get_template_directory_uri() . '/images/slide-2.jpg'),
		//'default'  => of_get_option('itrans_slide2_image'),					
		'priority'    => 1,
	);							
		
		
	// Slide3
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide3_title',
        'label'    => __( 'Slide3 Title', 'i-transform' ),
        'section'  => 'slide3',
		'default'  => of_get_option('itrans_slide3_title', 'Portfolio, Testimonial, Services...'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'itrans_slide3_desc',
		'label'       => __( 'Slide3 Description', 'i-transform' ),
		'section'     => 'slide3',
		'default'  => of_get_option('itrans_slide3_desc', 'Once you install and activate the plugin &rdquo; TemplatesNext Toolkit &rdquo; Use the [tx] button on your editor to create the columns, services, portfolios, testimonials and custom sliders.'),		
		'priority'    => 10,
	);
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide3_linktext',
        'label'    => __( 'Slide3 Link text', 'i-transform' ),
        'section'  => 'slide3',
		'default'  => of_get_option('itrans_slide3_linktext', 'Know More'),			
        'priority' => 1,
    );
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide3_linkurl',
        'label'    => __( 'Slide3 Link URL', 'i-transform' ),
        'section'  => 'slide3',
		'default'  => of_get_option('itrans_slide3_linkurl', 'https://wordpress.org/'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'upload',
		'setting'     => 'itrans_slide3_image',
		'label'       => __( 'Slide3 Image', 'i-transform' ),
        'section'  	  => 'slide3',
		'default'  => of_get_option('itrans_slide3_image', get_template_directory_uri() . '/images/slide-3.jpg'),
		//'default'  => of_get_option('itrans_slide3_image'),					
		'priority'    => 1,
	);							
	
	
	// Slide2
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide4_title',
        'label'    => __( 'Slide4 Title', 'i-transform' ),
        'section'  => 'slide4',
		'default'  => of_get_option('itrans_slide4_title', 'Customize Your pages'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'textarea',
		'setting'     => 'itrans_slide4_desc',
		'label'       => __( 'Slide4 Description', 'i-transform' ),
		'section'     => 'slide4',
		'default'  => of_get_option('itrans_slide4_desc', 'Customize your pages with page options (meta). Use default theme slider or itrans slider or any 3rd party slider on any page'),		
		'priority'    => 10,
	);
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide4_linktext',
        'label'    => __( 'Slide4 Link text', 'i-transform' ),
        'section'  => 'slide4',
		'default'  => of_get_option('itrans_slide4_linktext', 'Know More'),		
        'priority' => 1,
    );
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'itrans_slide4_linkurl',
        'label'    => __( 'Slide4 Link URL', 'i-transform' ),
        'section'  => 'slide4',
		'default'  => of_get_option('itrans_slide4_linkurl', 'https://wordpress.org/'),		
        'priority' => 1,
    );
	$controls[] = array(
		'type'        => 'upload',
		'setting'     => 'itrans_slide4_image',
		'label'       => __( 'Slide4 Image', 'i-transform' ),
        'section'  	  => 'slide4',
		'default'  => of_get_option('itrans_slide4_image', get_template_directory_uri() . '/images/slide-4.jpg'),
		//'default'  => of_get_option('itrans_slide4_image'),			
		'priority'    => 1,
	);
	
	// Blog page setting
	
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'slider_stat',
		'label'       => __( 'Turn ON/OFF i-transform Slider', 'i-transform' ),
		'description' => __( 'Turn Off or On to hide/show default i-transform slider', 'i-transform' ),
		'section'     => 'blogpage',
		'default'  => 1,		
		'priority'    => 1,
	);
	
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'other_front_slider',
        'label'    => __( 'Other Slider Shortcode', 'i-transform' ),
        'section'  => 'blogpage',
		'default'  => '',		
        'priority' => 1,
		'description' => __( 'Enter a 3rd party slider shortcode, ex. meta slider, smart slider 2, wow slider, etc.', 'i-transform' ),		
    );	
	
	// WooCommerce Settings
/*	
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'hide_login',
		'label'       => __( 'Hide Topnav Login', 'i-transform' ),
		'description' => __( 'Hide login menu item from top nav', 'i-transform' ),
		'section'     => 'woocomm',
		'default'  => of_get_option('hide_login', ''),		
		'priority'    => 1,
	);
	
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'hide_cart',
		'label'       => __( 'Hide Topnav Cart', 'i-transform' ),
		'description' => __( 'Hide cart from top nav', 'i-transform' ),
		'section'     => 'woocomm',
		'default'  => of_get_option('hide_cart', ''),		
		'priority'    => 1,
	);
	
	$controls[] = array(
		'type'        => 'switch',
		'setting'     => 'normal_search',
		'label'       => __( 'Turn On Normal Search', 'i-transform' ),
		'description' => __( 'Product only search will be turned off.', 'i-transform' ),
		'section'     => 'woocomm',
		'default'  => of_get_option('normal_search', ''),		
		'priority'    => 1,
	);			
	*/
	/*
    $controls[] = array(
        'type'     => 'text',
        'setting'  => 'blogslide_scode',
        'label'    => __( 'Other Slider Shortcode', 'i-transform' ),
        'section'  => 'blogpage',
        'default'  => '',
		'description' => __( 'Enter a 3rd party slider shortcode, ex. meta slider, smart slider 2, wow slider, etc.', 'i-transform' ),
        'priority' => 2,
    );
	

	
	
	// Off
	$controls[] = array(
		'type'        => 'toggle',
		'setting'     => 'toggle_demo',
		'label'       => __( 'This is the label', 'i-transform' ),
		'description' => __( 'This is the control description', 'i-transform' ),
		'section'     => 'blogpage',
		'default'     => 1,
		'priority'    => 10,
	);	
	
	*/
	// promos
	$controls[] = array(
		'type'        => 'custom',
		'settings'    => 'custom_demo',
		'label' => __( 'TemplatesNext Promo', 'i-transform' ),
		'section'     => 'nxpromo',
		'default'	  => '<div class="promo-box">
        <div class="promo-2">
        	<div class="promo-wrap">
            	<a href="http://templatesnext.org/itrans/" target="_blank">i-transform Demo</a>
                <a href="https://www.facebook.com/templatesnext" target="_blank">Facebook</a> 
                <a href="http://templatesnext.org/ispirit/landing/forums/" target="_blank">Support</a>                                 
                <!-- <a href="http://templatesnext.org/itrans/docs">Documentation</a> -->
                <a href="http://templatesnext.org/ispirit/landing/" target="_blank">Go Premium</a>                
                <div class="donate">                
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="M2HN47K2MQHAN">
                    <table>
                    <tr><td><input type="hidden" name="on0" value="If you like my work, you can buy me">If you like my work, you can buy me</td></tr><tr><td><select name="os0">
                        <option value="a cup of coffee">1 cup of coffee $10.00 USD</option>
                        <option value="2 cup of coffee">2 cup of coffee $20.00 USD</option>
                        <option value="3 cup of coffee">3 cup of coffee $30.00 USD</option>
                    </select></td></tr>
                    </table>
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </div>                                                                          
            </div>
        </div>
		</div>',
		'priority' => 10,
	);	
	
    return $controls;
}
add_filter( 'kirki/controls', 'itransform_custom_setting' );







