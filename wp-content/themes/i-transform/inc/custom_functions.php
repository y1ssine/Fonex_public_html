<?php 
/*-----------------------------------------------------------------------------------*/
/* Social icons																		*/
/*-----------------------------------------------------------------------------------*/
function itransform_social_icons () {
	
	$socio_list = '';
	$siciocount = 0;
    $services = array ('facebook','twitter','pinterest','flickr','feed','instagram','googleplus','youtube');
    
		$socio_list .= '<ul class="social">';	
		foreach ( $services as $service ) :
			
			$active[$service] = esc_url( get_theme_mod('itrans_social_'.$service, of_get_option ('itrans_social_'.$service)) );
			if ($active[$service]) { 
				$socio_list .= '<li><a href="'.$active[$service].'" title="'.$service.'" target="_blank"><i class="genericon socico genericon-'.$service.'"></i></a></li>';
				$siciocount++;
			}
			
		endforeach;
		$socio_list .= '</ul>';
		
		if($siciocount>0)
		{	
			return $socio_list;
		} else
		{
			return;
		}
}

/*-----------------------------------------------------------------------------------*/
/* ibanner Slider																		*/
/*-----------------------------------------------------------------------------------*/
function itransform_ibanner_slider () {    

	$itrans_slogan = esc_attr(get_theme_mod('banner_text', of_get_option('itrans_slogan')));
	$sliderscpeed = get_theme_mod('itrans_sliderspeed', (of_get_option('sliderspeed', 6000)/1000))*1000;

	$upload_dir = wp_upload_dir();
	$upload_base_dir = $upload_dir['basedir'];
	$upload_base_url = $upload_dir['baseurl'];
					
	$arrslidestxt = array();
	$template_dir = get_template_directory_uri();
    for($slideno=1;$slideno<=4;$slideno++){
			$strret = '';
			
			$slide_speed = esc_attr( get_theme_mod('itrans_sliderspeed')*1000 );
			$slide_title = esc_attr( get_theme_mod('itrans_slide'.$slideno.'_title', of_get_option ('itrans_slide'.$slideno.'_title', 'Multi-Purpose WP Theme')) );
			$slide_desc = esc_attr( get_theme_mod('itrans_slide'.$slideno.'_desc', of_get_option ('itrans_slide'.$slideno.'_desc', 'To start setting up i-transform go to appearance &gt; Customize. Make sure you have installed recommended plugin &#34;TemplatesNext Toolkit&#34; by going appearance > install plugin.')) );
			$slide_linktext = esc_attr( get_theme_mod('itrans_slide'.$slideno.'_linktext', of_get_option ('itrans_slide'.$slideno.'_linktext', 'Know More')) );
			$slide_linkurl = esc_attr( get_theme_mod('itrans_slide'.$slideno.'_linkurl', of_get_option ('itrans_slide'.$slideno.'_linkurl', 'http://templatesnext.org/icraft/?page_id=783')) );			
			$slide_image = esc_attr( get_theme_mod('itrans_slide'.$slideno.'_image', of_get_option ('itrans_slide'.$slideno.'_image', get_template_directory_uri() . '/images/slide-'.$slideno.'.jpg')) );				
 
			$slider_image_id = itransform_get_attachment_id_from_url( $slide_image );			
			$slider_resized_image = wp_get_attachment_image( $slider_image_id, "slider-thumb" );

			
			if ($slider_resized_image=="")
			{
				$slider_resized_image = '<img src="'.get_template_directory_uri() . '/images/missing-thumb.png">';
			}
			
			//echo "<h3>Original Image URL : ".$slide_image."</h3>";
			//echo "<h3>Image ID : ".$slider_image_id."</h3>";
			//echo "<h3>Resized Image URL : ".$slider_resized_image."</h3>";						
			
			if (!$slide_linktext)
			{
				$slide_linktext = __('Read more', 'i-transform');
			}
			
			if ($slide_title) {
			$strret .= '<h2>'.$slide_title.'</h2>';
			$strret .= '<p>'.$slide_desc.'</p>';
			$strret .= '<a href="'.$slide_linkurl.'" class="da-link">'.$slide_linktext.'</a>';
			
				if( $slide_image!='' ){
					if( file_exists( str_replace($upload_base_url,$upload_base_dir,$slide_image) ) ){
						$strret .= '<div class="da-img">' . $slider_resized_image .'</div>';
					}
					else
					{
						//$slide_image = $template_dir.'/images/no-image.png';
						$slide_image = $template_dir.'/images/slide-'.$slideno.'.jpg';
						$strret .= '<div class="da-img noslide-image-1"><img src="'.$slide_image.'" alt="'.$slide_title.'" /></div>';					
					}
				}
				else
				{					
					$slide_image = $template_dir.'/images/no-image.png';
					$strret .= '<div class="da-img noslide-image"><img src="'.$slide_image.'" alt="'.$slide_title.'" /></div>';
				}
			}
			if ($strret !=''){
				$arrslidestxt[$slideno] = $strret;
			}
					
	}
	if(count($arrslidestxt)>0){
		echo '<div class="ibanner" data-sliderspeed="'.$sliderscpeed.'">';
        echo '	<div class="slidexnav">';
		echo '		<a href="#" class="sldprev genericon genericon-leftarrow"></a>';
		echo '		<a href="#" class="sldnext genericon genericon-rightarrow"></a>';
		echo '	</div>';
		echo '	<div id="da-slider" class="da-slider" role="banner">';
			
		foreach ( $arrslidestxt as $slidetxt ) :
			echo '<div class="da-slide">';	
			echo	$slidetxt;
			echo '</div>';

		endforeach;
		
		echo '		<nav class="da-arrows">';
		echo '			<span class="da-arrows-prev"></span>';
		echo '			<span class="da-arrows-next"></span>';
		echo '		</nav>';
		echo '	</div>';
		echo '</div>';
	} else
	{
        echo '<div class="iheader front">';
        echo '    <div class="titlebar">';
        echo '        <h1>';
		
		if ( $itrans_slogan ) {
						//bloginfo( 'name' );
			echo $itrans_slogan;
		} 
		//else {
			//printf( __( 'Welcome To ', 'i-transform' ) );  bloginfo( 'name' );
		//}
        echo '        </h1>';
		echo ' 		  <h2>';
			    		//bloginfo( 'description' );
						//echo of_get_option('itrans_sub_slogan');
		echo '		</h2>';
        echo '    </div>';
        echo '</div>';
	}
    
}

/*-----------------------------------------------------------------------------------*/
/* find attachment id from url																	*/
/*-----------------------------------------------------------------------------------*/
function itransform_get_attachment_id_from_url( $attachment_url = '' ) {

    global $wpdb;
    $attachment_id = false;

    // If there is no url, return.
    if ( '' == $attachment_url )
        return;

    // Get the upload directory paths
    $upload_dir_paths = wp_upload_dir();

    // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
    if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

        // If this is the URL of an auto-generated thumbnail, get the URL of the original image
        $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

        // Remove the upload path base directory from the attachment URL
        $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

        // Finally, run a custom database query to get the attachment ID from the modified attachment URL
        $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

    }

    return $attachment_id;
}
