<?php
/**
 * Page/post/archive title with header image background
 */
if(!function_exists( 'trusted_header_title' )){
	function trusted_header_title() { 
		$header_light = get_theme_mod( 'header_light', 'dark' );
		if ( $header_light == 'light' ) {
			$main_header_class = ' light';
		} else {
			$main_header_class = '';
		}
		$header_layout = get_theme_mod( 'header_layout', 'behind' );
		if ( $header_layout == 'below' ) {
			$header_layout_class = ' below';
		} else {
			$header_layout_class = '';
		}
		$page_title_align = get_theme_mod( 'page_title_align', 'left' );
		?>
	<header class="main-header<?php echo $main_header_class . $header_layout_class; ?>">
		<div class="container">
			<div class="header-title align-<?php echo esc_html( $page_title_align); ?>">
		<?php
		if ( class_exists( 'WooCommerce' ) && in_array( 'woocommerce-page' , get_body_class() ) ) {
			$shop_title_icon = get_theme_mod( 'shop_title_icon', 'fa fa-shopping-cart' );
			if ( is_product() ) {
				if ( has_post_thumbnail() ) {
					$product_thumbnail = get_the_post_thumbnail_url( '', 'shop_thumbnail' );
					the_title( '<h1 class="main-title fadeInDown"><span class="main-title-img"><img src="' . $product_thumbnail . '"></span>', '</h1>' );
				} else {
					the_title( '<h1 class="main-title fadeInDown"><i class="' . $shop_title_icon . '"></i>', '</h1>' );
				}				
			} else {
				if ( is_shop() || !is_singular() ) {
					echo '<h1 class="main-title fadeInDown"><i class="' . esc_html($shop_title_icon) . '"></i>';
					woocommerce_page_title();
					echo '</h1>';
				} else {
					the_title( '<h1 class="main-title fadeInDown"><i class="' . esc_html($shop_title_icon) . '"></i>', '</h1>' );
				}
				if ( is_shop() ) {
					$shop_page_id = get_post( wc_get_page_id( 'shop' ) );
					if ( $shop_page_id ) {
						$trusted_excerpt = wp_kses_post( wpautop( get_post_field( 'post_excerpt', $shop_page_id ) ) );
						if ( $trusted_excerpt ) {
							echo '<div class="main-excerpt fadeInUp">';
							echo $trusted_excerpt;
							echo '</div>';
						}
					}
				} else {
					do_action( 'trusted_woocommerce_archive_description' );
				}
			}
		} else {
			if ( is_home() && 'page' == get_option( 'show_on_front' ) ) {
				$blog_page_id = get_option( 'page_for_posts' );
				$blog_title_icon = get_theme_mod( 'blog_title_icon', 'fa fa-newspaper-o' );
				echo '<h1 class="main-title fadeInDown"><i class="' . esc_html( $blog_title_icon ) . '"></i>' . get_the_title( $blog_page_id ) . '</h1>';
				$trusted_excerpt = wp_kses_post( wpautop( get_post_field( 'post_excerpt', $blog_page_id ) ) );
				if ( $trusted_excerpt ) {
					echo '<div class="main-excerpt fadeInUp">';
					echo $trusted_excerpt;
					echo '</div>';
				}
			} elseif ( is_singular() ) {
				if ( is_page() ) {
					if ( in_array( 'woocommerce-page' , get_body_class() ) ) {
    					$page_title_icon = get_theme_mod( 'shop_title_icon', 'fa fa-shopping-cart' );
					} else {
    					$page_title_icon = get_theme_mod( 'page_title_icon', 'fa fa-check' );
					}
					if ( is_front_page() && !get_theme_mod( 'custom_logo' ) ) {
						the_title( '<h2 class="main-title fadeInDown"><i class="' . $page_title_icon . '"></i>', '</h2>' );
					} else {
						the_title( '<h1 class="main-title fadeInDown"><i class="' . $page_title_icon . '"></i>', '</h1>' );
					}
					$trusted_excerpt = wp_kses_post( wpautop( get_post_field( 'post_excerpt' ) ) );
					if ( $trusted_excerpt ) {
						echo '<div class="main-excerpt fadeInUp">';
						echo $trusted_excerpt;
						echo '</div>';
					}
				} else {
					$format = get_post_format();
					if ( $format == 'aside' ) {
						$blog_title_icon = 'fa fa-file';
					} elseif ( $format == 'image' ) {
						$blog_title_icon = 'fa fa-image';
					} elseif ( $format == 'video' ) {
						$blog_title_icon = 'fa fa-video-camera';
					} elseif ( $format == 'quote' ) {
						$blog_title_icon = 'fa fa-quote-right';
					} elseif ( $format == 'link' ) {
						$blog_title_icon = 'fa fa-link';
					} elseif ( $format == 'gallery' ) {
						$blog_title_icon = 'fa fa-image';
					} elseif ( $format == 'audio' ) {
						$blog_title_icon = 'fa fa-music';
					} elseif ( $format == 'status' ) {
						$blog_title_icon = 'fa fa-comment';
					} elseif ( $format == 'chat' ) {
						$blog_title_icon = 'fa fa-comments';
					} else {
						$blog_title_icon = get_theme_mod( 'blog_title_icon', 'fa fa-newspaper-o' );
					}
					the_title( '<h1 class="main-title fadeInDown"><i class="' . $blog_title_icon . '"></i>', '</h1>' );
					$trusted_excerpt = wp_kses_post( wpautop( get_post_field( 'post_excerpt' ) ) );
					if ( $trusted_excerpt ) {
						echo '<div class="main-excerpt fadeInUp">';
						echo $trusted_excerpt;
						echo '</div>';
					}
				}
			} elseif ( is_archive() ) {
				$blog_title_icon = get_theme_mod( 'blog_title_icon', 'fa fa-newspaper-o' );
				the_archive_title( '<h1 class="main-title fadeInDown"><i class="' . $blog_title_icon . '"></i>', '</h1>' );
				the_archive_description( '<div class="taxonomy-description fadeInUp">', '</div>' );
			} elseif ( is_search() ) {
				echo '<h1 class="main-title fadeInDown"><i class="fa fa-search"></i>';
				printf( esc_html__( 'Search Results for: %s', 'trusted' ), '<span>' . get_search_query() . '</span>' );
				echo'</h1>';
			} elseif ( is_404() ) {
				echo '<h1 class="main-title fadeInDown"><i class="fa fa-exclamation-triangle"></i>' . esc_html__( '404 Error', 'trusted' ) . '</h1>';
			}
		}
		?>
			</div>
		</div><!-- .container -->
	</header><!-- .entry-header -->
	<div class="container clearfix">
	<?php }
}
