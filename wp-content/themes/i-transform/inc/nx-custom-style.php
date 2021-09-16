<?php
	
	/*
	*
	*	nx Theme Functions
	*	------------------------------------------------
	*	nx Framework v 1.0
	*
	*	nx_custom_styles()
	*	nx_custom_script()
	*	nx_go_to_top()		
	*
	*/

 	/* CUSTOM CSS OUTPUT
 	================================================== */
 	if (!function_exists('itransform_custom_styles')) { 
		function ione_custom_styles() {
			
			global  $ione_data;
			global $post;	
			
			$custom_css = "";
			$body_font_size = "13";
			$body_line_height = "24";
			$menu_font_size = "13";
			$primary_color = "#3787be";

			$primary_color = get_theme_mod('primary_color', of_get_option('itrans_color_scheme'));
			
			if ( $primary_color == 'blue' )
			{
				$primary_color = "#3787be";
			} elseif ( $primary_color == 'red' )
			{
				$primary_color = "#c44044";
			} elseif ( $primary_color == 'green' )
			{
				$primary_color = "#95c440";				
			} elseif ( $primary_color == 'yellow' )
			{
				$primary_color = "#c4b041";				
			} elseif ( $primary_color == 'purple' )
			{
				$primary_color = "#6f40c4";				
			}
			
			// Custom page Color
			$custom_page_color = '';
			$topbar_bg_color = '';			
			if ( function_exists( 'rwmb_meta' ) ) {
				$custom_page_color = rwmb_meta('itrans_page_color', '');
				$topbar_bg_color = rwmb_meta('itrans_topbar_bg_color', '');
			}
			if( !empty($custom_page_color) )
			{
				$primary_color = $custom_page_color;
			}

			echo '<style type="text/css" id="custom-style">'. "\n";
			
			//echo 'a,a:visited,.blog-columns .comments-link a:hover {color: '.$primary_color.';}';

			echo 'a { color: '.$primary_color.';}';

			echo 'a:visited { color: '.$primary_color.';}';

			echo 'input:focus, textarea:focus {border: 1px solid '.$primary_color.';}';

			/* Buttons */
			echo 'button,input[type="submit"],input[type="button"],input[type="reset"] {background: '.$primary_color.'; }';

			echo '.nav-container .current_page_item > a > span,.nav-container .current_page_ancestor > a > span,.nav-container .current-menu-item > a span,.nav-container .current-menu-ancestor > a > span,.nav-container li a:hover span {	background-color: '.$primary_color.'; /* variable color */}';

			echo '.nav-container li:hover > a,.nav-container li a:hover {	color: '.$primary_color.';}';

			/* variable color */
			echo '.nav-container .sub-menu,.nav-container .children {	border: 1px solid #e7e7e7;	border-top: 2px solid '.$primary_color.'; /* variable color */}';

			echo '.ibanner {	background-color: '.$primary_color.';}';

			echo '.ibanner,.tx-folio-img .folio-links .folio-linkico, .tx-folio-img .folio-links .folio-zoomico {	background-color: '.$primary_color.';}';

			echo '.da-dots span.da-dots-current { background-color: '.$primary_color.';}';

			echo 'div#ft-post div.entry-thumbnail:hover > div.comments-link { background-color: '.$primary_color.';}';

			echo '.entry-header h1.entry-title a:hover { color: '.$primary_color.'; }';

			echo '.entry-header > div.entry-meta a:hover { color: '.$primary_color.'; }';

			echo '.featured-area div.entry-summary > p > a.moretag:hover {	background-color: '.$primary_color.';}';

			echo '.site-content .post div.meta-img div.entry-thumbnail img {	border-top: 2px solid '.$primary_color.';}';

			echo '.site-content div.entry-thumbnail .stickyonimg,.site-content div.entry-thumbnail .dateonimg {	background-color: '.$primary_color.';}';

			echo '.site-content div.entry-nothumb .stickyonimg,.site-content div.entry-nothumb .dateonimg {	background-color: '.$primary_color.';}';

			echo '.entry-meta a {	color: '.$primary_color.';}';

			echo '.entry-content a,.comment-content a {	color: '.$primary_color.';}';

			echo '.format-status .entry-content .page-links a,.format-gallery .entry-content .page-links a,.format-chat .entry-content .page-links a,.format-quote .entry-content .page-links a,.page-links a {	background: '.$primary_color.';	border: 1px solid '.$primary_color.';	color: #ffffff;}';

			echo '.format-gallery .entry-content .page-links a:hover,.format-audio .entry-content .page-links a:hover,.format-status .entry-content .page-links a:hover,.format-video .entry-content .page-links a:hover,.format-chat .entry-content .page-links a:hover,.format-quote .entry-content .page-links a:hover,.page-links a:hover {	color: '.$primary_color.';}';

			echo '.iheader {background-color: '.$primary_color.';}';

			echo '.iheader.front { background-color: '.$primary_color.';}';

			echo '.navigation a { color: '.$primary_color.';}';

			echo '.paging-navigation div.navigation > ul > li a:hover,.paging-navigation div.navigation > ul > li.active > a {	color: '.$primary_color.';	border-color: '.$primary_color.';}';

			echo '.comment-author .fn,.comment-author .url,.comment-reply-link,.comment-reply-login {	color: '.$primary_color.';}';

			echo '.comment-body a,.comment-meta,.comment-meta a {	color: '.$primary_color.';}';

			echo '.widget a:hover {	color: '.$primary_color.';}';

			echo '.widget_calendar a:hover {	background-color: '.$primary_color.';	color: #ffffff;	}';

			echo '.widget_calendar td#next a:hover,.widget_calendar td#prev a:hover {	background-color: '.$primary_color.';	color: #ffffff;	}';

			echo '.site-footer div.widget-area .widget a:hover {	color: '.$primary_color.';}';

			echo '.site-main div.widget-area .widget_calendar a:hover,.site-footer div.widget-area .widget_calendar a:hover {	background-color: '.$primary_color.';	color: #ffffff;	}';

			echo '.da-dots > span > span {background-color: '.$primary_color.';}';

			echo '.widget a:visited,.entry-header h1.entry-title a:visited {	color: #474747;}';

			echo '.widget a:hover,.entry-header h1.entry-title a:hover {	color: '.$primary_color.';}';

			echo '.error404 .page-title:before {	color: '.$primary_color.';}';

			echo '.format-status {	background-color: '.$primary_color.';}';

			echo '.content-area .tx-service .tx-service-icon span {	color: '.$primary_color.';	border-color:  '.$primary_color.';}';

			echo '.content-area .tx-service:hover .tx-service-icon span {	background-color: '.$primary_color.';}';

			echo '.content-area .tx-service .tx-service-icon span i {	color: '.$primary_color.';	}';
			echo '.content-area .tx-service:hover .tx-service-icon span i {	color: #FFFFFF;	}';
			
			echo '.post .post-mainpart .entry-summary a.moretag {color: #FFFFFF; background-color: '.$primary_color.';}';
			
			if( !empty($topbar_bg_color) ) {
				echo '.utilitybar { background-color: '.$topbar_bg_color.'; color: #FFFFFF; border-bottom: 1px solid '.$topbar_bg_color.';}';
				echo '.utilitybar .ubarinnerwrap .topphone { color: #FFFFFF;}';	
				echo '.utilitybar .ubarinnerwrap .topbarico	{ color: #FFFFFF;}';
				echo '.utilitybar .ubarinnerwrap .socialicons ul.social li a i.genericon { background-color: rgba(255, 255, 255, 0.2); color: #FFF; }';	
				echo '.utilitybar .ubarinnerwrap .socialicons ul.social li a:hover i.genericon { background-color: rgba(255, 255, 255, 0.0); color: #FFF; }';								
			}
			
			if ($custom_css) {
			echo "\n".'/* =============== user styling =============== */'."\n";
			echo $custom_css;
			}
			
			// CLOSE STYLE TAG
			echo "</style>". "\n";
		}
	
		add_action('wp_head', 'ione_custom_styles');
	}
	
	/* CUSTOM JS OUTPUT
	================================================== 
	function nx_custom_script() {
		
		global  $ione_data;
		
		$custom_js = $ione_data['custom_js'];
		
		if ($custom_js) {			
			echo "\n<script>\n".$custom_js."\n</script>\n";			
		}
	}
	
	add_action('wp_footer', 'nx_custom_script');
		
*/



