<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package i-transform
 * @since i-transform 1.0
 */
 
$top_phone = '';
$top_email = '';

$top_phone = esc_attr(get_theme_mod('top_phone', of_get_option('top_bar_phone', '1-000-123-4567')));
$top_email = esc_attr(get_theme_mod('top_email', of_get_option('top_bar_email', 'email@i-create.com')));
$itrans_logo = get_theme_mod( 'logo', of_get_option('itrans_logo_image', get_template_directory_uri() . '/images/logo.png') );
$itrans_slogan = esc_attr(get_theme_mod('banner_text', of_get_option('itrans_slogan')));

$turn_front_slider = esc_attr(get_theme_mod('slider_stat', 1));
$other_front_slider = esc_attr(get_theme_mod('other_front_slider', ''));

global $post; 

$no_page_header = 0;
if ( function_exists( 'rwmb_meta' ) ) { 
	$no_page_header = rwmb_meta('itrans_no_page_header');
}
 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<?php    
    if ( ! function_exists( '_wp_render_title_tag' ) ) :
        function itrans_render_title() {
    ?>
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php
        }
        add_action( 'wp_head', 'itrans_render_title' );
    endif;    
    ?> 
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
    	
        <?php if ( $top_phone || $top_email || itransform_social_icons() ) : ?>
    	<div id="utilitybar" class="utilitybar">
        	<div class="ubarinnerwrap">
                <div class="socialicons">
                    <?php echo itransform_social_icons(); ?>
                </div>
                <?php if ( $top_phone ) : ?>
                <div class="topphone">
                    <i class="topbarico genericon genericon-phone"></i>
                    <?php if ( $top_phone ) : ?>
                        <?php echo $top_phone; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ( $top_email ) : ?>
                <div class="topphone">
                    <i class="topbarico genericon genericon-mail"></i>
                    <?php if ( $top_email ) : ?>
                        <?php echo $top_email; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>                
            </div> 
        </div>
        <?php endif; ?>
        
        <?php if ( $no_page_header == 0 ) : ?>
        <div class="headerwrap">
            <header id="masthead" class="site-header" role="banner">
         		<div class="headerinnerwrap">
					<?php if ( $itrans_logo ) : ?>
                        <a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                            <span><img src="<?php echo $itrans_logo; ?>" alt="<?php bloginfo( 'name' ); ?>" /></span>
                        </a>
                    <?php else : ?>
                        <span id="site-titlendesc">
                            <a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                                <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                                <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>   
                            </a>
                        </span>
                    <?php endif; ?>	
        
                    <div id="navbar" class="navbar">
                        <nav id="site-navigation" class="navigation main-navigation" role="navigation">
                            <h3 class="menu-toggle"><?php _e( 'Menu', 'i-transform' ); ?></h3>
                            <a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'i-transform' ); ?>"><?php _e( 'Skip to content', 'i-transform' ); ?></a>
                            <?php 
								if ( has_nav_menu(  'primary' ) ) {
										wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'container_class' => 'nav-container', 'container' => 'div' ) );
									}
									else
									{
										wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-container' ) ); 
									}
								?>
							
                        </nav><!-- #site-navigation -->
                        <div class="topsearch">
                            <?php get_search_form(); ?>
                        </div>
                    </div><!-- #navbar -->
                    <div class="clear"></div>
                </div>
            </header><!-- #masthead -->
        </div>
        <?php endif; ?>

        <?php
			$hide_title = $show_slider = $other_slider = $hide_bread = $smart_slider_3 = '';
			
			if ( function_exists( 'rwmb_meta' ) ) { 			
				$hide_title = rwmb_meta('itrans_hidetitle');
				$show_slider = rwmb_meta('itrans_show_slider');
				$other_slider = rwmb_meta('itrans_other_slider');
				$hide_bread = rwmb_meta('itrans_hide_breadcrumb');
				$smart_slider_3 = rwmb_meta('itrans_smart_slider');
			}			
        ?>
        <!-- #Banner -->
        <?php 
		if( !empty($smart_slider_3) ) {
			$other_slider = '[smartslider3 slider='.$smart_slider_3.']';
		}		
		
		if( $other_slider )
		{
			?>
                <div class="other-slider">
                	<div class="other-slider-innerwrap">
                    	<?php echo do_shortcode( $other_slider ) ?>
                    </div>
                </div>            
            <?php
		}
		//if ( is_home() && ! is_paged() || is_front_page() ) 
		elseif ( is_home() && ! is_paged() || $show_slider ) 
		{
			if ( !empty($other_front_slider) )
			{
				echo do_shortcode( $other_front_slider );
			} elseif ( $turn_front_slider == 1 )
			{				
				itransform_ibanner_slider();
			}
		} elseif ( !$hide_title )
		{
        ?>	
			<div class="iheader">
				<div class="titlebar">
					<?php 
						if( function_exists('bcn_display')  && !$hide_bread )
						{
					?>
						<div class="breadcrumb">
					<?php
							bcn_display();
					?>
						</div>
					<?php		
						} else {
					?>               
					<h1>
						<?php if ( $itrans_slogan ) : ?>
							<?php echo $itrans_slogan; ?>
						<?php //else : ?> 
							<?php //printf( __( 'Welcome To ', 'i-transform' ) ); ?><?php //bloginfo( 'name' ); ?>   
						<?php endif; ?>
					</h1>
					<?php
						}
					?>

				</div>
			</div>
        	
		<?php
		}
        ?>
		<div id="main" class="site-main">
