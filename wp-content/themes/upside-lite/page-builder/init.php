<?php
if( class_exists('Kopa_Page_Builder') ){

	if( !class_exists('Upside_Lite_Builder') ){

		class Upside_Lite_Builder_Layout{

			public static function get_layout_ultimate(){

				$layout = array(
					'title'     => esc_attr__('Ultimate', 'upside-lite'),
					'preview'   => get_template_directory_uri() . '/page-builder/images/ultimate.png',
					'customize' => array(),
					'section'   => array()
				);	

				$layout['customize']['custom']['title']         = esc_attr__('Custom', 'upside-lite');
				$layout['customize']['custom']['params']['css'] = array(
					'type'    => 'textarea',
					'title'   => esc_attr__('CSS code', 'upside-lite'),
					'default' => '',
					'rows'    => 10   
				);

				$layout['section']['row-1'] = array(
					'title'        => esc_attr__('Row 1', 'upside-lite'),
					'description'  => '',
					'grid'         => array(12),
					'grid_classes' => array('col-xs-12'),
					'customize'    => array(),        		
					'area'         => array('area-1'),					
				);

				$layout['section']['row-2'] = array(
					'title'        => esc_attr__('Row 2', 'upside-lite'),
					'description'  => '',
					'grid'         => array(4,8),
					'grid_classes' => array( 'col-md-4 col-xs-12', 'col-md-8 col-xs-12' ),
					'customize'    => array(),        		
					'area'         => array('area-2-1', 'area-2-2'),
				);

                $layout['section']['row-3'] = array(
                    'title'        => esc_attr__('Row 3', 'upside-lite'),
                    'description'  => '',
                    'grid'         => array(8,4),
                    'grid_classes' => array( 'col-md-8 col-xs-12', 'col-md-4 col-xs-12' ),
                    'customize'    => array(),
                    'area'         => array('area-3-1', 'area-3-2'),
                );

                $layout['section']['row-4'] = array(
                    'title'        => esc_attr__('Row 4', 'upside-lite'),
                    'description'  => '',
                    'grid'         => array(12),
                    'grid_classes' => array('col-xs-12'),
                    'customize'    => array(),
                    'area'         => array('area-4'),
                );

				$layout['section']['row-5'] = array(
					'title'        => esc_attr__('Row 5', 'upside-lite'),
					'description'  => '',
					'grid'         => array(12),
					'grid_classes' => array('col-xs-12'),
					'customize'    => array(),        		
					'area'         => array('area-5'),
				);

				$layout['section']['row-6'] = array(
					'title'        => esc_attr__('Row 6', 'upside-lite'),
					'description'  => '',
					'grid'         => array(12),
					'grid_classes' => array( 'col-xs-12' ),
					'customize'    => array(),        		
					'area'         => array('area-6'),
				);

				$layout['section']['row-7'] = array(
					'title'        => esc_attr__('Row 7', 'upside-lite'),
					'description'  => '',
					'grid'         => array(12),
					'grid_classes' => array('col-xs-12'),
					'customize'    => array(),        		
					'area'         => array('area-7'),
				);

				$layout['section']['row-8'] = array(
					'title'       => esc_attr__('Row 8', 'upside-lite'),
					'description' => '',
					'grid'        => array(6,6),
					'grid_classes' => array( 'col-md-6 col-xs-12', 'col-md-6 col-xs-12'),
					'customize'   => array(),        		
					'area'        => array('area-8-1', 'area-8-2'),
				);

                $layout['section']['row-9'] = array(
                    'title'        => esc_attr__('Row 9', 'upside-lite'),
                    'description'  => '',
                    'grid'         => array(12),
                    'grid_classes' => array('col-xs-12'),
                    'customize'    => array(),
                    'area'         => array('area-9'),
                );

				return $layout;
			}

		}

		class Upside_Lite_Builder{

			public function __construct(){
				add_action( 'init', array($this, 'init') );	
				add_filter( 'body_class', array($this, 'body_class') );
				add_filter( 'kopa_page_builder_get_areas', array('Upside_Lite_Builder', 'set_areas') );
				add_filter( 'kopa_page_builder_get_layouts', array('Upside_Lite_Builder', 'set_layouts') );
				add_filter( 'kopa_page_builder_get_section_fields', array('Upside_Lite_Builder', 'set_section_fields') );			
				add_filter( 'kopa_page_builder_get_customize_fields', array('Upside_Lite_Builder', 'set_widget_fields') );
				
				add_action( 'kopa_page_builder_after_save_widget', array('Upside_Lite_Builder', 'clear_cache'), 10, 1 );
				add_action( 'kopa_page_buider_after_save_grid', array('Upside_Lite_Builder', 'clear_cache'), 10, 1 );
				add_action( 'kopa_page_builder_after_save_section_customize', array('Upside_Lite_Builder', 'clear_cache'), 10, 1 );
				add_action( 'kopa_page_builder_after_save_layout_customize', array('Upside_Lite_Builder', 'clear_cache'), 10, 1 );

				add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script'), 20);
				add_action( 'template_redirect', array( $this, 'template_redirect' ) );
			}

			public function init(){
				add_filter('upside_lite_get_page_template', array($this, 'get_page_template'));
			}

			public function get_page_template($template){
				if(is_page()){
					global $post;
					$layout = Kopa_Page_Builder::get_current_layout($post->ID);
					if( ! in_array( $layout, array( '', 'disable' ) ) ){
				 		$template = sprintf( 'page-builder/layouts/%s', $layout );
				 	}	
				}

				return $template;
			}

			public function body_class($classes){
				array_push($classes, 'upside-lite-builder');
				return $classes;
			}

			public function get_spacing( $element, $type='margin', $data=array( 'top'=>'', 'bottom' => '', 'left' => '', 'right' => '' ) ){
				$css = '';
				foreach( $data as $key => $value ){
                    if( '' !== $value){
                        $css .= sprintf( '%s-%s: %spx;', $type, $key, $value );
					}
				}
				return $css;
			}

			public function template_redirect(){
				if( is_page() ){
					global $post;

					$is_use_map_api = false;
					$css            = '';						
					$cache          = get_transient( self::get_cache_key( $post->ID ) );

					if( empty( $cache ) ){

						$layout_slug = Kopa_Page_Builder::get_current_layout( $post->ID );

				        if( ! empty($layout_slug) && ( 'disable' !== $layout_slug ) ){

				        	$data  = Kopa_Page_Builder::get_current_layout_data( $post->ID );													
							$layout_customize = wp_parse_args( Kopa_Page_Builder::get_layout_customize_data( $post->ID, $layout_slug ), Upside_Lite_Builder::get_layout_default_args() );	

							$layouts  = apply_filters( 'kopa_page_builder_get_layouts', array() );
							$layout   = $layouts[$layout_slug];							
							$sections = $layout['section'];			

							if( !empty( $layout_customize['custom']['css'] ) ){
								$css .= $layout_customize['custom']['css'];
							}
							
							if( count($sections) > 0 ){
							
								foreach($sections as $section_slug => $section){
									
									$container_data = $data[$section_slug];

									if( !empty( $container_data ) ){
										$container = Kopa_Page_Builder::get_current_wrapper_data( $post->ID, $layout_slug, $section_slug );								
										$container = wp_parse_args( $container, Upside_Lite_Builder::get_section_default_args() );

										// begin: container.
                                        $container_css  = '';

                                        if( !empty( $container ) ){
                                            $container_css .= $this->get_spacing( 'ID', 'margin', $container['margin'] );
                                            $container_css .= $this->get_spacing( 'ID', 'padding', $container['padding'] );
                                        }

                                        if( ! empty( $container['bg']['bg_image'] ) ){
                                            $container_css .= sprintf( 'background-image: url("%s");', esc_url( do_shortcode( $container['bg']['bg_image'] ) ) );
                                        }

                                        if( ! empty( $container['bg']['bg_color'] ) ){
                                            $container_css .= sprintf( 'background-color: %s;', esc_attr( $container['bg']['bg_color'] ) );
                                        }

                                        if( ! empty( $container['bg']['bg_repeat'] ) ){
                                            if ( 'cover' === $container['bg']['bg_repeat'] ) {
                                                $container_css .= 'background-repeat:no-repeat;';
                                            } elseif ( 'contain' === $container['bg']['bg_repeat'] ) {
                                                $container_css .= 'background-repeat:no-repeat;';
                                            } elseif ( 'no-repeat' === $container['bg']['bg_repeat'] ) {
                                                $container_css .= 'background-repeat: no-repeat;';
                                            }
                                        }

                                        if( ! empty( $container['bg']['bg_size'] ) ){
                                            $container_css .= sprintf( 'background-size: %s;', esc_attr( $container['bg']['bg_size'] ) );

                                        }



                                        if( ! empty( $container_css ) ){
                                            $container_css = sprintf('%s { %s }', 'ID', $container_css);
                                            $container_css = str_replace('ID', sprintf( '#upside-lite-%s', $section_slug ), $container_css );
                                            $css           .= $container_css;
                                        }

                                        // Overlay color
                                        if ( isset($container['container']['overlay_color']) && ! empty( $container['container']['overlay_color'] ) ){
                                            $opacity = 1;
                                            if ( isset($container['container']['overlay_color_opacity']) && ! empty( $container['container']['overlay_color_opacity'] ) ){
                                                $opacity = $container['container']['overlay_color_opacity'];
                                            }
                                            $overlay_css = '
                                                .kopa-span-bg {
                                                    position: absolute;
                                                    top: 0;
                                                    left: 0;
                                                    width: 100%;
                                                    height: 100%;
                                                    background-color: ' . esc_attr($container['container']['overlay_color']) . ';
                                                    opacity:' . esc_attr($opacity) . ';
                                                }
                                            ';
                                            $css .= $overlay_css;
                                        }

                                        if ( isset($container['custom']['css']) && ! empty($container['custom']['css']) ) {
                                            $container_custom_css = str_replace('ID', sprintf( '#upside-lite-%s', $section_slug ), $container['custom']['css'] );
                                            $css .= $container_custom_css;
                                        }

										// end: container.

										$areas = $section['area'];									
										foreach( $areas as $area ){
											if( isset( $container_data[$area] ) && !empty( $container_data[$area] ) ){
												$widgets = $container_data[$area];

												foreach( $widgets as $widget_id => $widget ){

													if( ! $is_use_map_api ){
														$map_class_names = apply_filters('upside_lite_get_map_class_name', array());

														if( in_array( $widget['class_name'], $map_class_names ) ){
															$is_use_map_api = true;	
														}
													}

													$widget_css  = '';

													$widget_data = get_post_meta( $post->ID, $widget_id, true );
													$widget_data = wp_parse_args( $widget_data['customize'], Upside_Lite_Builder::get_widget_default_args() );

													$widget_css .= $this->get_spacing( 'ID', 'margin', $widget_data['margin'] );
													$widget_css .= $this->get_spacing( 'ID', 'padding', $widget_data['padding'] );

													if( ! empty( $widget_data['container']['bg_image'] ) ){
														$widget_css .= sprintf( 'background-image: url("%s");', esc_url( $widget_data['container']['bg_image'] ) );
													}

													if( ! empty( $widget_data['container']['bg_color'] ) ){
														$widget_css .= sprintf( 'ID { background-color: %s; }', esc_attr( $widget_data['container']['bg_color'] ) );
													}

                                                    $widget_css = sprintf('%s { %s }', 'ID', $widget_css);
													if( ! empty( $widget_css ) ){
														$widget_css = str_replace('ID', sprintf( '#%s', $widget_id ), $widget_css );
														$css        .= $widget_css;
													}

                                                    if ( ! empty($widget_data['custom']['css']) ) {
                                                        $widget_custom_css = str_replace('ID', sprintf( '#%s', $widget_id ), $widget_data['custom']['css'] );
                                                        $css        .= $widget_custom_css;
                                                    }
												}
											}
										}
									}
								}

							}

					        $cache = array();
							$cache['css']                      = $css;
							$cache['config']['is_use_map_api'] = $is_use_map_api;
							$cache['layout']['customize']      = $layout_customize;						

					        set_transient( self::get_cache_key( $post->ID ) , $cache, 365 * 7 * 24 * 60 * 60 );

				        }		          	
				    
					}
				
				}
			}

			public function enqueue_script(){
				if( is_page() ){
					global $post;
					
					$is_use_map_api = false;
					$css            = '';						
					$cache          = get_transient( self::get_cache_key( $post->ID ) );					

					if( ! empty( $cache ) ){

						if( isset( $cache['css'] ) ){
							$css = $cache['css'];
						}

						if( isset( $cache['config']['is_use_map_api'] ) ){
							$is_use_map_api = $cache['config']['is_use_map_api'];
						}
						wp_add_inline_style( 'upside-lite-main-style', $css );

						if( $is_use_map_api ){
							wp_enqueue_script('upside-lite-maps-api', 'http://maps.google.com/maps/api/js?sensor=true', array('jquery'), NULL, TRUE);
	                		wp_enqueue_script('upside-lite-maps', get_template_directory_uri() . '/assets/js/gmap.js', array('jquery'), NULL, TRUE);
	                		wp_enqueue_script('upside-lite-maps-init', get_template_directory_uri() . '/assets/js/gmap.init.js', array('jquery'), NULL, TRUE);
						}

					}
			    }
			}	

			public static function set_areas($areas){

				$areas['area-1']    = esc_attr__('Area 1', 'upside-lite');
				$areas['area-2-1']  = esc_attr__('Area 2.1', 'upside-lite');
				$areas['area-2-2']  = esc_attr__('Area 2.2', 'upside-lite');
				$areas['area-3-1']    = esc_attr__('Area 3.1', 'upside-lite');
				$areas['area-3-2']    = esc_attr__('Area 3.2', 'upside-lite');
				$areas['area-4']  = esc_attr__('Area 4', 'upside-lite');
				$areas['area-5']    = esc_attr__('Area 5', 'upside-lite');
				$areas['area-6']  = esc_attr__('Area 6', 'upside-lite');
				$areas['area-7']    = esc_attr__('Area 7', 'upside-lite');
				$areas['area-8-1']  = esc_attr__('Area 8.1', 'upside-lite');
				$areas['area-8-2']  = esc_attr__('Area 8.2', 'upside-lite');
				$areas['area-9']    = esc_attr__('Area 9', 'upside-lite');
				return $areas;
			}
			
			public static function set_layouts($layouts){
			    $layouts['disable'] = array(
			       'title' => esc_attr__('-- Disable --', 'upside-lite')        
			    );			    
				$layouts['ultimate']  = Upside_Lite_Builder_Layout::get_layout_ultimate();

			    return $layouts;
			}

			public static function set_section_fields($fields){
				// CONTAINER.
				$fields['container']['title']  = esc_attr__('Container', 'upside-lite');	
				$fields['container']['params'] = array();

				$fields['container']['params']['is_boxed'] = array(
					'type'    => 'radio',
					'title'   => esc_attr__('Boxed', 'upside-lite'),
					'default' => 'false',
                    'help' => 'This option is to choose whether the content inside the row is "boxed" or "full-width". It will wrap the content inside the row with &lt;div class="container"&gt;&lt;/div&gt; HTLM tag',
					'options' => array(
						'true'  => esc_attr__('Yes', 'upside-lite'),
						'false' => esc_attr__('No', 'upside-lite'),
					)
				);

                $fields['container']['params']['is_parallax'] = array(
                    'type'    => 'radio',
                    'title'   => esc_attr__( 'Parallax background ', 'upside-lite' ),
                    'default' => 'false',
                    'options' => array(
                        'true'  => esc_attr__( 'Yes', 'upside-lite' ),
                        'false' => esc_attr__( 'No', 'upside-lite' ),
                    )
                );

                $fields['container']['params']['overlay_color'] = array(
                    'type'    => 'color',
                    'title'   => esc_attr__( 'Overlay color', 'upside-lite' ),
                    'default' => ''
                );

                $fields['container']['params']['overlay_color_opacity'] = array(
                    'type'    => 'text',
                    'title'   => esc_attr__( 'Overlay opacity', 'upside-lite' ),
                    'default' => 0.7
                );

				// BACKGROUND.
				$fields['bg']['title'] = esc_attr__('Background', 'upside-lite');

				$fields['bg']['params']['bg_image'] = array(
					'type'    => 'image',
					'title'   => esc_attr__( 'Background image', 'upside-lite' ),
					'default' => ''
				);				

				$fields['bg']['params']['bg_color'] = array(
					'type'    => 'color',
					'title'   => esc_attr__( 'Background color', 'upside-lite' ),
					'default' => ''
				);

                $fields['bg']['params']['bg_repeat'] = array(
                    'type'    => 'select',
                    'title'   => esc_attr__( 'Background repeat', 'upside-lite' ),
                    'default' => 'cover',
                    'options' => array(
                        ''    => esc_attr__( 'Theme defaults', 'upside-lite' ),
                        'no-repeat' => esc_attr__( 'No Repeat', 'upside-lite' ),
                        'repeat'   => esc_attr__( 'Repeat', 'upside-lite' ),
                    )
                );

                $fields['bg']['params']['bg_size'] = array(
                    'type'    => 'select',
                    'title'   => esc_attr__( 'Background size', 'upside-lite' ),
                    'default' => 'cover',
                    'options' => array(
                        ''    => esc_attr__( 'Theme defaults', 'upside-lite' ),
                        'cover'  => esc_attr__( 'Cover', 'upside-lite' ),
                        'contain'  => esc_attr__( 'Contain', 'upside-lite' ),
                    )
                );

				// MARGIN.
				$fields['margin']['title']  = esc_attr__('Margin', 'upside-lite');
				$fields['margin']['params'] = array();	

				$fields['margin']['params']['top'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Top', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['margin']['params']['bottom'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Bottom', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['margin']['params']['left'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Left', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['margin']['params']['right'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Right', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);

				// PADDING.
				$fields['padding']['title']  = esc_attr__('Padding', 'upside-lite');	
				$fields['padding']['params'] = array();	

				$fields['padding']['params']['top'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Top', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['padding']['params']['bottom'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Bottom', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['padding']['params']['left'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Left', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['padding']['params']['right'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Right', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);

				// CUSTOM.
				$fields['custom']['title']         =  esc_attr__('Custom', 'upside-lite');	
				$fields['custom']['params']        = array();			

				$fields['custom']['params']['css'] = array(
					'type'    => 'textarea',
					'title'   => esc_attr__('CSS code', 'upside-lite'),
					'default' => '',
					'rows'    => 5,       
					'class'   => 'kpb-ui-textarea-guide-line',
					'help'    => htmlspecialchars_decode( esc_html__('This advanced option is for those who know CSS code. You can enter custom CSS code here. Example: <code>ID a {color: red; }</code> <br/> ID: The ID of section.', 'upside-lite') ),
				);							

				return $fields;
			}

			public static function set_widget_fields($fields){
				// MARGIN.
				$fields['margin']['title']  = esc_attr__('Margin', 'upside-lite');	
				$fields['margin']['params'] = array();	
				$fields['margin']['params']['top'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Top', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['margin']['params']['bottom'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Bottom', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['margin']['params']['left'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Left', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['margin']['params']['right'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Right', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);

				// PADDING.
				$fields['padding']['title']  = esc_attr__('Padding', 'upside-lite');	
				$fields['padding']['params'] = array();	
				$fields['padding']['params']['top'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Top', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['padding']['params']['bottom'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Bottom', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['padding']['params']['left'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Left', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);
				$fields['padding']['params']['right'] = array(
					'type'    => 'number',
					'title'   => esc_attr__('Right', 'upside-lite'),
					'default' => '',
					'affix'   => 'px'
				);				

				// CUSTOM.
				$fields['custom']['title']         =  esc_attr__('Custom', 'upside-lite');	
				$fields['custom']['params']        = array();				
				$fields['custom']['params']['css'] = array(
					'type'    => 'textarea',
					'title'   => esc_attr__('CSS code', 'upside-lite'),
					'default' => '',
					'rows'    => 5,       
					'class'   => 'kpb-ui-textarea-guide-line',
					'help'    => htmlspecialchars_decode( esc_html__('This advanced option is for those who know CSS code. You can enter custom CSS code here. Example: <code>ID a {color: red; }</code> <br/> ID: The ID of widget.', 'upside-lite') ),
					
				);

				return $fields;
			}

			public static function get_layout_default_args(){
				$data = array();


				$data['custom'] = array(
					'css' => ''
				);

				return $data;
			}

			public static function get_section_default_args(){
				$data = array(
					'container' => array(
						'is_boxed' => '',
						'is_parallax' => '',
						'overlay_color' => '',
						'opacity' => '0.7',
					),
					'bg' => array(
						'bg_image'      => '',
						'bg_color'      => '',
						'bg_repeat'     => 'repeat',
						'bg_position'   => 'center center',
						'bg_attachment' => 'scroll',
					),
					'margin' => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',
					),
					'padding' => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',	
					),
					'custom' => array(
						'css' => ''
					)
				);
				return $data;
			}

			public static function get_widget_default_args(){
				$data = array(
					'margin' => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',
					),
					'padding' => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',	
					),
					'custom' => array(
						'css' => '',
					),
				);
				return $data;
			}

			public static function print_before_section( $layout, $section_slug ){
				global $post;
				$section = Kopa_Page_Builder::get_current_wrapper_data($post->ID, $layout, $section_slug);
				$section = wp_parse_args($section, Upside_Lite_Builder::get_section_default_args());
                $section_class = 'kopa-area ul_section';
                if( isset($section['container']['is_parallax']) && 'true' === $section['container']['is_parallax'] ){
                    $section_class .= ' kopa-area-parallax';
                }

                echo sprintf( '<section id="upside-lite-%s" class="%s">', esc_attr($section_slug), esc_attr($section_class) );

                if( isset($section['container']['overlay_color']) && '' !== $section['container']['overlay_color'] ){
                    echo '<div class="kopa-span-bg"></div>';
                }

                if( isset( $section['container']['is_boxed'] ) && 'true' == $section['container']['is_boxed'] ){
                    echo '<div class="container">';
                }
			}

			public static function print_after_section( $layout, $section_slug ){
				global $post;
				$section = Kopa_Page_Builder::get_current_wrapper_data($post->ID, $layout, $section_slug);
				$section = wp_parse_args($section, Upside_Lite_Builder::get_section_default_args());

                if( isset($section['container']['is_boxed']) && 'true' === $section['container']['is_boxed'] ){
                    echo '</div>';
                }
                echo '</section>';
			}

			public static function print_area( $post_id, $data ){
				if( $data ){
					foreach($data as $widget_id => $widget){

						if($widget_data = get_post_meta($post_id, $widget_id, true)){
							
							$class_name = $widget['class_name'];	                                    

							if(class_exists($class_name)){

								$instance    = $widget_data['widget'];
								$obj         = new $class_name;
								$obj->id     = $widget_id;
								$obj->number = rand(0, 9999);

					            $widget_wrap = array(
									'before_widget' => '<div id="%1$s" class="widget %2$s">',
									'after_widget'  => '</div>',
									'before_title'  => '<h3 class="widget-title">',
									'after_title'   => '</h3>'
					            );

				            	$widget_wrap['before_widget'] = sprintf( $widget_wrap['before_widget'], $obj->id, apply_filters('kopa_page_bulder_set_classname', $obj->widget_options['classname'], $widget_data['widget']) );
				            	$obj->widget( $widget_wrap, $instance );
							}
						}
					}
				}
			}

			public static function print_page( $post_id, $info ){
				$layout = Kopa_Page_Builder::get_current_layout( $post_id );
				$data   = Kopa_Page_Builder::get_current_layout_data( $post_id );
				foreach ($data as $row_slug => $row) {
					if( ! empty( $data[$row_slug] ) ){
						$grid        = $info['section'][$row_slug]['grid'];
						Upside_Lite_Builder::print_before_section( $layout, $row_slug );
						echo '<div class="row">';
						$loop_index = 0;
						foreach ( $row as $col_slug => $col ) {
							$col_classes = $info['section'][$row_slug]['grid_classes'];
							printf( '<div class="col-lg-%s %s">', $grid[ $loop_index ], $col_classes[$loop_index] );
							if( isset( $data[ $row_slug ][ $col_slug ] ) ){
								Upside_Lite_Builder::print_area( $post_id, $data[$row_slug][$col_slug] ); 
							}
							echo '</div>';
							$loop_index++;
						}
						echo '</div>';
						Upside_Lite_Builder::print_after_section( $layout, $row_slug);
					}
				}
			}

			public static function clear_cache( $post_id ){								
				delete_transient( self::get_cache_key( $post_id ) );
			}	

			public static function get_cache_key( $post_id ){
				return sprintf( 'upside_lite_page_builder_cache_%s', $post_id );
			}		
		}

		new Upside_Lite_Builder();
	}
}