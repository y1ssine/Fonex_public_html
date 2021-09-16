<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'itrans_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function itrans_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'itrans_';
	
	$itrans_template_url = get_template_directory_uri();

	// 1st meta box
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'heading',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Page Heading Options', 'i-transform' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post', 'page' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			// Hide Title
			array(
				'name' => __( 'Hide Titlebar', 'i-transform' ),
				'id'   => "{$prefix}hidetitle",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'class' => 'hide-ttl',
			),
						
			/**/	
			array(
				'name' => __( 'Show Default i-transform Slider', 'i-transform' ),
				'id'   => "{$prefix}show_slider",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'class' => 'show-slider',
			),			
				
			// hide breadcrum
			array(
				'name' => __( 'Hide breadcrumb', 'i-transform' ),
				'id'   => "{$prefix}hide_breadcrumb",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
			),
			
			// Other Slider Shortcode
			array(
				// Field name - Will be used as label
				'name'  => __( 'Other Slider Plugin Shortcode', 'i-transform' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}other_slider",
				// Field description (optional)
				'desc'  => __( 'Enter a 3rd party slider shortcode, ex. meta slider, smart slider 2, wow slider, etc. <br />Recommended width 1632px or 100%', 'i-transform' ),
				'type'  => 'text',
				// Default value (optional)
				'std'   => __( '', 'i-transform' ),
				// CLONES: Add to make the field cloneable (i.e. have multiple value)
				//'clone' => true,
				'class' => 'cust-ttl',
			),
			
			array(
				'name'            => __( 'Smart Slider 3', 'i-transform' ),
				'id'              => "{$prefix}smart_slider",
				'type'            => 'select',
				// Array of 'value' => 'Label' pairs
				'options'         => nx_smartslider_list (),
				// Allow to select multiple value?
				'multiple'        => false,
				// Placeholder text
				'placeholder'     => __( 'Select a smart slider', 'i-transform' ),
				'after'			  => '',
				// Display "Select All / None" button?
				'select_all_none' => false,
			),				
			

		)
	);
	
	
	
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'portfoliometa',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Portfolio Meta', 'i-transform' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'portfolio' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			// Side bar

			// ITEM DETAILS OPTIONS SECTION
			array(
				'type' => 'heading',
				'name' => __( 'Portfolio Additinal Details', 'i-transform' ),
				'id'   => 'fake_id_pf1', // Not used but needed for plugin
			),
			// Slide duration
			array(
				'name'  => __( 'Subtitle', 'i-transform' ),
				'id'    => "{$prefix}portfolio_subtitle",
				'desc'  => __( 'Enter a subtitle for use within the portfolio item index (optional).', 'i-transform' ),				
				'type'  => 'text',
			),
			
			array(
				'name'  => __( 'Portfolio Link(External)', 'i-transform' ),
				'id'    => "{$prefix}portfolio_url",
				'desc'  => __( 'Enter an external link for the item (optional) (NOTE: INCLUDE HTTP://).', 'i-transform' ),				
				'type'  => 'text',
			),

		)
	);		
	

	// 1st meta box
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'pageoptions',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'Page Options', 'i-transform' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'page' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				'name' => __( 'Hide Title Text', 'i-transform' ),
				'id'   => "{$prefix}hide_title_text",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'class' => 'hide-ttl-text',
			),
			array(
				'name' => __( 'Remove Top and Bottom Padding/Margin', 'i-transform' ),
				'id'   => "{$prefix}page_nopad",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Remove the spaces/padding from top and bottom of the page/post', 'i-transform'),
			),
			
			// Hide page header
			array(
				'name' => __( 'Show Transparent Header', 'i-transform' ),
				'id'   => "{$prefix}trans_header",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Show transparent header on pages/posts. This will hide the page/post titlebar as well', 'i-transform'),
			),				
			
			// Hide page header
			array(
				'name' => __( 'Hide Page Header', 'i-transform' ),
				'id'   => "{$prefix}no_page_header",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('In case you are building the page without the top navigation and logo', 'i-transform'),
			),										

			// Hide page header
			array(
				'name' => __( 'Hide Top Utilitybar', 'i-transform' ),
				'id'   => "{$prefix}no_ubar",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Hide top bar with email, phone and social links', 'i-transform'),
			),
			// Hide page header
			array(
				'name' => __( 'Hide Footer Widget Area', 'i-transform' ),
				'id'   => "{$prefix}no_footer",
				'type' => 'checkbox',
				// Value can be 0 or 1
				'std'  => 0,
				'desc' => __('Hide bottom footer widget area', 'i-transform'),
			),	
			
			// Custom page primary color			
			array(
				'name'  => __( 'Custom Primary Color', 'i-transform' ),
				'id'    => "{$prefix}page_color",
				'type'  => 'color',
				'std'   => '',
				'desc' => __('Choose a custom primary color for this page', 'i-transform'),
			),
			
			// Custom page primary color			
			array(
				'name'  => __( 'Topbar Background Color', 'i-transform' ),
				'id'    => "{$prefix}topbar_bg_color",
				'type'  => 'color',
				'std'   => '',
				'desc' => __('Top bar with phone, email and social link background color', 'i-transform'),
			),	
									
			// additional page class			
			array(
				'name'  => __( 'Additional Page Class', 'i-transform' ),
				'id'    => "{$prefix}page_class",
				'type'  => 'text',
				'std'   => __( '', 'i-transform' ),
				'desc' => __('Enter an additional page class, will be added to body. "hide-page-header" for no header, "boxed" for boxed page for wide layout.', 'i-transform'),
			),			

		)
	);	
	
	
	
	return $meta_boxes;
}


function nx_get_category_list_key_array($category_name) {
			
	$get_category = get_categories( array( 'taxonomy' => $category_name	));
	$category_list = array( 'all' => 'Select Category');
		
	foreach( $get_category as $category ){
		if (isset($category->slug)) {
			$category_list[$category->slug] = $category->cat_name;
		}
	}
	return $category_list;
}	


function nx_smartslider_list () {
	
	global $wpdb;
	$smartslider = array();
	//$smartslider[0] = 'Select a slider';
	
	if(class_exists('SmartSlider3')) {
		$get_sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'nextend2_smartslider3_sliders');
		if($get_sliders) {
			foreach($get_sliders as $slider) {
				$smartslider[$slider->id] = $slider->title;
			}
		}
	}
	return $smartslider;

}	
